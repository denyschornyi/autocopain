<?php

namespace App\Http\Controllers\ProviderResources;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Controllers\SendPushNotification;
use Auth;
use Setting;
use Storage;
use Carbon\Carbon;

use App\User;
use App\Helpers\Helper;
use App\RequestFilter;
use App\UserRequests;
use App\ProviderService;
use App\PromocodeUsage;
use App\Provider;
use App\Promocode;
use App\UserRequestRating;
use App\UserRequestPayment;
use Mockery\Exception;
use App\ProviderWallet;
use Illuminate\Support\Facades\DB;
use App\WalletRequest;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            if ($request->ajax()) {
                $Provider = Auth::user();
            } else {
                $Provider = Auth::guard('provider')->user();
            }

            $provider = $Provider->id;

            $AfterAssignProvider = RequestFilter::with(['request.user', 'request.payment', 'request', 'request.service_type'])
                ->where('provider_id', $provider)
                ->whereHas('request', function ($query) use ($provider) {
                    $query->where('status', '<>', 'CANCELLED');
                    $query->where('status', '<>', 'SCHEDULED');
                    $query->where('provider_id', $provider);
                    $query->where('current_provider_id', $provider);
                });

            $BeforeAssignProvider = RequestFilter::with(['request.user', 'request.payment', 'request', 'request.service_type'])
                ->where('provider_id', $provider)
                ->whereHas('request', function ($query) use ($provider) {
                    $query->where('status', '<>', 'CANCELLED');
                    $query->where('status', '<>', 'SCHEDULED');
                    $query->where('current_provider_id', $provider);
                });

            $IncomingRequests = $BeforeAssignProvider->union($AfterAssignProvider)->get();


            if (!empty($request->latitude)) {
                $Provider->update([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            }

            $Timeout = Setting::get('provider_select_timeout', 180);
            if (!empty($IncomingRequests)) {
                for ($i = 0; $i < sizeof($IncomingRequests); $i++) {
                    $IncomingRequests[$i]->time_left_to_respond = $Timeout - (time() - strtotime($IncomingRequests[$i]->request->assigned_at));
                    if ($IncomingRequests[$i]->request->status == 'SEARCHING' && $IncomingRequests[$i]->time_left_to_respond < 0) {
                        $this->assign_next_provider($IncomingRequests[$i]->request->id);
                    }
                }
            }

            $Response = [
                'account_status' => $Provider->status,
                'service_status' => $Provider->service ? Auth::user()->service->status : 'offline',
                'requests' => $IncomingRequests,
            ];

            return $Response;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Quelque chose a mal tourné']);
        }
    }

    /**
     * Cancel given request.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request)
    {
        try {

            $UserRequest = UserRequests::findOrFail($request->id);
            $Cancellable = ['SEARCHING', 'ACCEPTED', 'ARRIVED', 'STARTED', 'CREATED', 'SCHEDULED'];

            if (!in_array($UserRequest->status, $Cancellable)) {
                return back()->with(['flash_error' => 'Impossible d\'annuler la demande à ce stade!']);
            }

            $UserRequest->status = "CANCELLED";
            $UserRequest->cancelled_by = "PROVIDER";
            $UserRequest->save();

            RequestFilter::where('request_id', $UserRequest->id)->delete();

            ProviderService::where('provider_id', $UserRequest->provider_id)->update(['status' => 'active']);

            // Send Push Notification to Provider
            (new SendPushNotification)->ProviderCancellRide($UserRequest);

            // Send Push Notification to User
            (new SendPushNotification)->UserCancellRide($UserRequest);

            return $UserRequest;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Quelque chose a mal tourné']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rate(Request $request, $id)
    {

        $this->validate($request, [
            'rating' => 'required|integer|in:1,2,3,4,5',
            'comment' => 'max:255',
        ]);

        try {

            $UserRequest = UserRequests::where('id', $id)
                ->where('status', 'COMPLETED')
                ->firstOrFail();

            if ($UserRequest->rating == null) {
                UserRequestRating::create([
                    'provider_id' => $UserRequest->provider_id,
                    'user_id' => $UserRequest->user_id,
                    'request_id' => $UserRequest->id,
                    'provider_rating' => $request->rating,
                    'provider_comment' => $request->comment,
                ]);
            } else {
                $UserRequest->rating->update([
                    'provider_rating' => $request->rating,
                    'provider_comment' => $request->comment,
                ]);
            }

            $UserRequest->update(['provider_rated' => 1]);

            // Delete from filter so that it doesn't show up in status checks.
            RequestFilter::where('request_id', $id)->delete();

            // Send Push Notification to Provider 
            $base = UserRequestRating::where('user_id', $UserRequest->user_id);
            $average = $base->avg('user_rating');
            $average_count = $base->count();

            $UserRequest->user->update(['rating' => $average, 'user_rating' => $average_count]);

            ProviderService::where('provider_id', $UserRequest->provider_id)->update(['status' => 'active']);

            return response()->json(['message' => 'Demande terminée!']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Demande pas encore terminée!'], 500);
        }
    }

    /**
     * Get the trip history of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function history(Request $request)
    {
        if ($request->ajax()) {
            $Jobs = UserRequests::where('provider_id', Auth::user()->id)->orderBy('created_at', 'desc')->with('user', 'service_type', 'payment', 'rating')->get();
            if (!empty($Jobs)) {
                $map_icon = asset('asset/marker.png');
                foreach ($Jobs as $key => $value) {
                    $Jobs[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?autoscale=1&size=320x130&maptype=terrian&format=png&visual_refresh=true&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude . "&key=" . env('GOOGLE_API_KEY');
                }
            }
            return $Jobs;
        }
        $Jobs = UserRequests::where('provider_id', Auth::guard('provider')->user()->id)->with('user', 'service_type', 'payment', 'rating')->get();
        return view('provider.trip.index', compact('Jobs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request, $id)
    {
        try {

            $UserRequest = UserRequests::findOrFail($id);

            if ($UserRequest->status != "SEARCHING") {
                return response()->json(['error' => 'Demande déjà en cours!']);
            }

            $UserRequest->provider_id = Auth::user()->id;

            if ($UserRequest->schedule_at != "") {

                $beforeschedule_time = strtotime($UserRequest->schedule_at . "- 1 hour");
                $afterschedule_time = strtotime($UserRequest->schedule_at . "+ 1 hour");

                $CheckScheduling = UserRequests::where('status', 'SCHEDULED')
                    ->where('provider_id', Auth::user()->id)
                    ->whereBetween('schedule_at', [$beforeschedule_time, $afterschedule_time])
                    ->count();

                if ($CheckScheduling > 0) {
                    if ($request->ajax()) {
                        return response()->json(['error' => trans('api.ride.request_already_scheduled')]);
                    } else {
                        return redirect('dashboard')
                            ->with('flash_error', 'Si le trajet est déjà programmé, nous ne pouvons pas programmer / demander un autre trajet après 1 heure ou avant 1 heure.');
                    }
                }


                RequestFilter::where('request_id', $UserRequest->id)->where('provider_id', Auth::user()->id)->update(['status' => 2]);

                $UserRequest->status = "SCHEDULED";
                $UserRequest->save();

                // Send Push Notification to User
                (new SendPushNotification)->RideScheduled($UserRequest);
            } else {


                $UserRequest->status = "STARTED";
                $UserRequest->save();


                ProviderService::where('provider_id', $UserRequest->provider_id)->update(['status' => 'riding']);

                $Filters = RequestFilter::where('request_id', $UserRequest->id)->where('provider_id', '!=', Auth::user()->id)->get();
                // dd($Filters->toArray());
                foreach ($Filters as $Filter) {
                    $Filter->delete();
                }
            }

            $UnwantedRequest = RequestFilter::where('request_id', '!=', $UserRequest->id)
                ->where('provider_id', Auth::user()->id)
                ->whereHas('request', function ($query) {
                    $query->where('status', '<>', 'SCHEDULED');
                });

            if ($UnwantedRequest->count() > 0) {
                $UnwantedRequest->delete();
            }

            // Send Push Notification to User
            (new SendPushNotification)->RideAccepted($UserRequest);

            return $UserRequest->with('user')->get();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Incapable d\'accepter, veuillez réessayer plus tard']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur de connexion']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|in:ACCEPTED,STARTED,ARRIVED,PICKEDUP,DROPPED,PAYMENT,COMPLETED',
            'before_image' => 'mimes:jpeg,jpg,bmp,png',
            'after_image' => 'mimes:jpeg,jpg,bmp,png',
            'after_comment' => 'max:255',
            'before_comment' => 'max:255',
        ]);

        try {

            $UserRequest = UserRequests::with('user')->findOrFail($id);

            if ($request->has('before_comment')) {
                $UserRequest->before_comment = $request->before_comment;
            }

            if ($request->has('after_comment')) {
                $UserRequest->after_comment = $request->after_comment;
            }

            if ($request->hasFile('before_image')) {
                $UserRequest->before_image = $request->before_image->store('service');
            }

            if ($request->hasFile('after_image')) {
                $UserRequest->after_image = $request->after_image->store('service');
            }

            if ($request->status == 'DROPPED' && $UserRequest->payment_mode != 'CASH') {
                $UserRequest->status = 'COMPLETED';
            } else if ($request->status == 'COMPLETED' && $UserRequest->payment_mode == 'CASH') {
                $UserRequest->status = $request->status;
                $UserRequest->paid = 1;
                ProviderService::where('provider_id', $UserRequest->provider_id)->update(['status' => 'active']);
            } else {
                $UserRequest->status = $request->status;
                if ($request->status == 'ARRIVED') {
                    (new SendPushNotification)->Arrived($UserRequest);
                }
                if ($request->status == 'PICKEDUP') {
                    $UserRequest->started_at = Carbon::now();
                    (new SendPushNotification)->StartService($UserRequest);
                    // $UserRequest->save();
                }
            }

            $UserRequest->save();

            if ($request->status == 'DROPPED') {
                $UserRequest->with('user')->findOrFail($id);
                $UserRequest->finished_at = Carbon::now();
                $UserRequest->save();
                $UserRequest->invoice = $this->invoice($id);
                $this->createProviderWallet($UserRequest, $UserRequest->invoice);
                (new SendPushNotification)->EndService($UserRequest);
                return $UserRequest;
            }

            // Send Push Notification to User

            return $UserRequest;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Impossible de mettre à jour, veuillez réessayer plus tard']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur de connexion']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $UserRequest = UserRequests::find($id);

        try {
            $this->assign_next_provider($UserRequest->id);
            return $UserRequest->with('user')->get();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Impossible de rejeter, veuillez réessayer ultérieurement.']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur de connexion']);
        }
    }

    public function assign_next_provider($request_id)
    {

        try {
            $UserRequest = UserRequests::findOrFail($request_id);
        } catch (ModelNotFoundException $e) {
            // Cancelled between update.
            return false;
        }

        $RequestFilter = RequestFilter::where('provider_id', $UserRequest->current_provider_id)
            ->where('request_id', $UserRequest->id)
            ->delete();

        try {

            $next_provider = RequestFilter::where('request_id', $UserRequest->id)
                ->orderBy('id')
                ->firstOrFail();

            $UserRequest->current_provider_id = $next_provider->provider_id;
            $UserRequest->assigned_at = Carbon::now();
            $UserRequest->save();

            // incoming request push to provider
            (new SendPushNotification)->IncomingRequest($next_provider->provider_id);
        } catch (ModelNotFoundException $e) {
            UserRequests::where('id', $UserRequest->id)->update(['status' => 'CANCELLED']);

            // No longer need request specific rows from RequestMeta
            RequestFilter::where('request_id', $UserRequest->id)->delete();

            //  request push to user provider not available
            (new SendPushNotification)->ProviderNotAvailable($UserRequest->user_id);
        }
    }

    public function invoice($request_id)
    {
        try {
            $UserRequest = UserRequests::findOrFail($request_id);

            $hourdiff = round((strtotime($UserRequest->finished_at) - strtotime($UserRequest->started_at)) / 3600, 1);

            $Fixed = $UserRequest->service_type->fixed ?: 0;

            $TimePrice = ceil($hourdiff) * $UserRequest->service_type->price;
            $Discount = 0; // Promo Code discounts should be added here.

            if ($PromocodeUsage = PromocodeUsage::where('user_id', $UserRequest->user_id)->where('status', 'ADDED')->first()) {
                if ($Promocode = Promocode::find($PromocodeUsage->promocode_id)) {
                    $Discount = $Promocode->discount;
                    $PromocodeUsage->status = 'USED';
                    $PromocodeUsage->save();
                }
            }
            $Wallet = 0;


            $Total = $Fixed + $TimePrice - $Discount;

            $Commision = $Total * (Setting::get('commission_percentage', 10) / 100);
            $Tax = $Total * (Setting::get('tax_percentage', 10) / 100);

            $Total += $Tax;

            if ($Total < 0) {
                $Total = 0.00; // prevent from negative value
            }

            $Payment = new UserRequestPayment;
            $Payment->request_id = $UserRequest->id;
            $Payment->payment_mode = $UserRequest->payment_mode;
            $Payment->fixed = $Fixed;
            $Payment->time_price = $TimePrice;
            $Payment->commision = $Commision;
            if ($Discount != 0 && $PromocodeUsage) {
                $Payment->promocode_id = $PromocodeUsage->promocode_id;
            }
            $Payment->discount = $Discount;

            if ($UserRequest->use_wallet == 1 && $Total > 0) {

                $User = User::find($UserRequest->user_id);

                $Wallet = $User->wallet_balance;

                if ($Wallet != 0) {

                    if ($Total > $Wallet) {

                        $Payment->wallet = $Wallet;
                        $Payable = $Total - $Wallet;
                        User::where('id', $UserRequest->user_id)->update(['wallet_balance' => 0]);
                        $Payment->total = abs($Payable);

                        // charged wallet money push 
                        (new SendPushNotification)->ChargedWalletMoney($UserRequest->user_id, currency($Wallet));
                    } else {

                        $Payment->total = 0;
                        $WalletBalance = $Wallet - $Total;
                        User::where('id', $UserRequest->user_id)->update(['wallet_balance' => $WalletBalance]);
                        $Payment->wallet = $Total;

                        // charged wallet money push 
                        (new SendPushNotification)->ChargedWalletMoney($UserRequest->user_id, currency($Total));
                    }
                }
            } else {
                $Payment->total = abs($Total);
            }

            $Payment->tax = $Tax;
            $Payment->save();

            return $Payment;
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    // when provider served completely
    public function createProviderWallet($user_request, $user_request_payment)
    {
        if ($user_request_payment == false) return false;
        if (empty($user_request)) return false;
        try {
            $provider_wallet = new ProviderWallet;
            $provider_wallet->provider_id = $user_request->provider_id;
            $provider_wallet->transaction_id = $user_request->id;
            $provider_wallet->transaction_alias = $user_request->booking_id;
            $provider_wallet->payment_mode = $user_request_payment->payment_mode;
            $provider_wallet->open_balance = 0.0;
            $provider_wallet->close_balance = 0.0;
            if ($provider_wallet->payment_mode == 'CASH') {
                $provider_wallet->transaction_desc = 'commission amount';
                $provider_wallet->amount = 0 - $user_request_payment->commision;
                $provider_wallet->save();

                $provider_wallet1 = new ProviderWallet;
                $provider_wallet1->provider_id = $user_request->provider_id;
                $provider_wallet1->transaction_id = $user_request->id;
                $provider_wallet1->transaction_alias = $user_request->booking_id;
                $provider_wallet1->payment_mode = $user_request_payment->payment_mode;
                $provider_wallet1->open_balance = 0.0;
                $provider_wallet1->close_balance = 0.0;
                $provider_wallet1->transaction_desc = 'montant de la TVA crédité';
                $provider_wallet1->amount = 0 - $user_request_payment->tax;
                $provider_wallet1->save();

                $Provider = Provider::where('id', $user_request->provider_id)->first();
                if (!empty($Provider)) {
                    $Provider->wallet_balance -= $user_request_payment->commision;
                    $Provider->wallet_balance -= $user_request_payment->tax;
                    $Provider->save();
                }
            } else {
                $provider_wallet->amount = $user_request_payment->total - $user_request_payment->commision - $user_request_payment->tax;
                $provider_wallet->transaction_desc = 'card amount';
                $provider_wallet->save();

                $Provider = Provider::where('id', $user_request->provider_id)->first();
                if (!empty($Provider)) {
                    $Provider->wallet_balance += $provider_wallet->amount;
                    $Provider->save();
                }
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get the trip history details of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function history_details(Request $request)
    {
        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id',
        ]);

        if ($request->ajax()) {

            $Jobs = UserRequests::where('id', $request->request_id)
                ->where('provider_id', Auth::user()->id)
                ->orderBy('created_at', 'desc')
                ->with('payment', 'service_type', 'user', 'rating')
                ->get();
            if (!empty($Jobs)) {
                $map_icon = asset('asset/marker.png');
                foreach ($Jobs as $key => $value) {
                    $Jobs[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?autoscale=1&size=320x130&maptype=terrian&format=png&visual_refresh=true&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude . "&key=" . env('GOOGLE_API_KEY');
                }
            }

            return $Jobs;
        }
    }

    /**
     * Get the trip history details of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming_details(Request $request)
    {
        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id',
        ]);

        if ($request->ajax()) {

            $Jobs = UserRequests::where('id', $request->request_id)
                ->where('provider_id', Auth::user()->id)
                ->with('service_type', 'user')
                ->get();
            if (!empty($Jobs)) {
                $map_icon = asset('asset/marker.png');
                foreach ($Jobs as $key => $value) {
                    $Jobs[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?autoscale=1&size=320x130&maptype=terrian&format=png&visual_refresh=true&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude . "&key=" . env('GOOGLE_MAP_KEY');
                }
            }

            return $Jobs;
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function upcoming_trips()
    {

        try {
            $UserRequests = UserRequests::ProviderUpcomingRequest(Auth::user()->id)->get();
            if (!empty($UserRequests)) {
                $map_icon = asset('asset/marker.png');
                foreach ($UserRequests as $key => $value) {
                    $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?autoscale=1&size=320x130&maptype=terrian&format=png&visual_refresh=true&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude . "&key=" . env('GOOGLE_MAP_KEY');
                }
            }
            return $UserRequests;
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }


    /**
     * Get the trip history details of the provider
     *
     * @return \Illuminate\Http\Response
     */
    public function summary(Request $request)
    {
        try {
            if ($request->ajax()) {

                $rides = UserRequests::where('provider_id', Auth::user()->id)->count();
                $revenue = UserRequestPayment::whereHas('request', function ($query) {
                    $query->where('provider_id', Auth::user()->id);
                })
                    ->sum('total');
                $cancel_rides = UserRequests::where('status', 'CANCELLED')->where('provider_id', Auth::user()->id)->count();
                $scheduled_rides = UserRequests::where('status', 'SCHEDULED')->where('provider_id', Auth::user()->id)->count();

                return response()->json([
                    'rides' => $rides,
                    'revenue' => $revenue,
                    'cancel_rides' => $cancel_rides,
                    'scheduled_rides' => $scheduled_rides,
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }


    /**
     * help Details.
     *
     * @return \Illuminate\Http\Response
     */

    public function help_details(Request $request)
    {

        try {

            if ($request->ajax()) {
                return response()->json([
                    'contact_number' => Setting::get('contact_number', ''),
                    'contact_email' => Setting::get('contact_email', ''),
                    'contact_text' => Setting::get('contact_text', ''),
                    'contact_title' => Setting::get('site_title', ''),
                ]);
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    /**
     * Wallet APIS
     *
     * 
     */

    function date_sort($a, $b)
    {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    }

    function getWalletHistory($provider_id = null)
    {
        $ProviderWallets = [];
        $WalletRequest = [];
        try {
            if ($provider_id == null) {
                $ProviderWallets = ProviderWallet::select('transaction_id', 'transaction_alias', 'created_at', DB::raw('SUM(amount) as total_amount'), DB::raw('"0" as type'))
                    ->groupBy('transaction_alias', 'transaction_id', 'transaction_alias', 'created_at')
                    ->get()->toArray();
                $WalletRequest = WalletRequest::select(DB::raw('id as transaction_id'), DB::raw('alias_id as transaction_alias'), DB::raw('updated_at as created_at'), DB::raw('amount as total_amount'), DB::raw('"1" as type'))
                    ->where('status', 'APPROVED')
                    ->get()->toArray();
            } else {
                $ProviderWallets = ProviderWallet::select('transaction_id', 'transaction_alias', 'created_at', DB::raw('SUM(amount) as total_amount'), DB::raw('"0" as type'))
                    ->where('provider_id', $provider_id)
                    ->groupBy('transaction_alias', 'transaction_id', 'transaction_alias', 'created_at')
                    ->get()->toArray();
                $WalletRequest = WalletRequest::select(DB::raw('id as transaction_id'), DB::raw('alias_id as transaction_alias'), DB::raw('updated_at as created_at'), DB::raw('amount as total_amount'), DB::raw('"1" as type'))
                    ->where('from_id', $provider_id)
                    ->where('status', 'APPROVED')
                    ->get()->toArray();
            }
            $result = [];
            if (is_array($ProviderWallets))
                $result = array_merge($result, $ProviderWallets);
            if (is_array($WalletRequest))
                $result = array_merge($result, $WalletRequest);
            usort($result, array($this, "date_sort"));
            return $result;
        } catch (Exception $e) {
            return [];
        }
    }

    function getWalletHistory2($provider_id = null)
    {
        $ProviderWallets = [];
        $WalletRequest = [];
        try {
            if ($provider_id == null) {
                $ProviderWallets = ProviderWallet::select('transaction_id', 'transaction_alias', 'created_at', DB::raw('amount as total_amount'), DB::raw('"0" as type'), DB::raw('transaction_desc as description'), DB::raw('payment_mode as status'))
                    ->get()->toArray();
                $WalletRequest = WalletRequest::select(DB::raw('id as transaction_id'), DB::raw('alias_id as transaction_alias'), DB::raw('updated_at as created_at'), DB::raw('amount as total_amount'), DB::raw('"1" as type'), DB::raw('from_desc as description'), DB::raw('type as status'))
                    ->where('status', 'APPROVED')
                    ->get()->toArray();
            } else {
                $ProviderWallets = ProviderWallet::select('transaction_id', 'transaction_alias', 'created_at', DB::raw('amount as total_amount'), DB::raw('"0" as type'), DB::raw('transaction_desc as description'), DB::raw('payment_mode as status'))
                    ->where('provider_id', $provider_id)
                    ->get()->toArray();
                $WalletRequest = WalletRequest::select(DB::raw('id as transaction_id'), DB::raw('alias_id as transaction_alias'), DB::raw('updated_at as created_at'), DB::raw('amount as total_amount'), DB::raw('"1" as type'), DB::raw('from_desc as description'), DB::raw('type as status'))
                    ->where('from_id', $provider_id)
                    ->where('status', 'APPROVED')
                    ->get()->toArray();
            }
            $result = [];
            if (is_array($ProviderWallets))
                $result = array_merge($result, $ProviderWallets);
            if (is_array($WalletRequest))
                $result = array_merge($result, $WalletRequest);
            usort($result, array($this, "date_sort"));
            return $result;
        } catch (Exception $e) {
            return [];
        }
    }

    public function wallet_history(Request $request)
    {
        $result = $this->getWalletHistory(Auth::user()->id);
        $total_amount = 0;
        $provider = Provider::where('id', Auth::user()->id)->firstOrFail();
        if ($provider->wallet_balance != null) {
            $total_amount = $provider->wallet_balance;
        }
        // foreach ($result as $transaction) {
        //     $total_amount += $transaction['total_amount'];
        // }
        return response()->json(['histories' => $result, 'total_wallet' => $total_amount]);
    }

    public function wallet_detail(Request $request)
    {
        $this->validate($request, [
            'transaction_id' => 'required|integer',
            'type' => 'required|integer',
        ]);
        try {
            if ($request->type == 0) {
                $ProviderWallet = ProviderWallet::select(DB::raw('transaction_desc as transaction_desc'), DB::raw('payment_mode as payment_mode'), DB::raw('amount as amount'))
                    ->where('transaction_id', $request->transaction_id)
                    ->get();
                return response()->json(['result' => $ProviderWallet]);
            } else if ($request->type == 1) {
                $WalletRequest = WalletRequest::select(DB::raw('from_desc as transaction_desc'), DB::raw('type as payment_mode'), DB::raw('amount as amount'))
                    ->where('id', $request->transaction_id)
                    ->get();
                return response()->json(['result' => $WalletRequest]);
            } else {
                return response()->json(['result' => []]);
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    public function transaction_request(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|between:0,9999.99',
        ]);

        $WalletRequestPending = WalletRequest::select(DB::raw('SUM(amount) as total_amount'))
            ->where('from_id', Auth::user()->id)
            ->whereIn('status', ['PENDING'])
            ->get();
        $amount = count($WalletRequestPending) > 0 ? $WalletRequestPending[0]->total_amount : 0;  // negotive money -89.00 Euro pending
        $provider = Provider::where('id', Auth::user()->id)->firstOrFail();
        if ($provider->wallet_balance != null) {
            $amount = $provider->wallet_balance + $amount;
        }

        if ($amount < $request->amount) {
            //     return response()->json(['success' => false, 'message' => 'your wallet is no enough.']);
            // } else if ($amount < $request->amount) {
            return response()->json(['success' => false, 'message' => 'your wallet is ' . currency($amount)]);
        } else {
            $wallet = new WalletRequest;
            $wallet->alias_id = 'PSET' . round(microtime(true) * 1000);
            $wallet->request_from = 'provider';
            $wallet->from_id = Auth::user()->id;
            $wallet->type = 'CREDIT';
            $wallet->from_desc = $wallet->type . ' du règlement';
            $wallet->amount = 0 - $request->amount;
            $wallet->send_by = 'online';
            $wallet->status = 'PENDING';
            $wallet->save();

            $WalletRequest = WalletRequest::where('from_id', Auth::user()->id)
                ->where('status', 'PENDING')
                ->orderBy('created_at', 'DESC')
                ->get();
            return response()->json(['success' => true, 'result' => $WalletRequest]);
        }
        return response()->json(['success' => false, 'message' => trans('api.something_went_wrong')]);
    }

    public function transaction_history(Request $request)
    {
        $WalletRequest = WalletRequest::where('from_id', Auth::user()->id)
            ->where('status', 'PENDING')
            ->orderBy('created_at', 'DESC')
            ->get();
        return response()->json(['success' => true, 'result' => $WalletRequest]);
    }

    public function migratingWalletData(Request $request)
    {
        $UserRequests = UserRequests::select([
            'user_requests.provider_id as provider_id',
            'user_requests.id as id',
            'user_requests.booking_id as booking_id',
            'user_request_payments.payment_mode as payment_mode',
            'user_request_payments.commision as commision',
            'user_request_payments.total as total',
            'user_request_payments.tax as tax',
            'user_requests.updated_at as updated_at'
        ])
            ->leftJoin('user_request_payments', 'user_request_payments.request_id', '=', 'user_requests.id')
            ->where('user_requests.status', 'COMPLETED')
            ->get();

        foreach ($UserRequests as $user_request) {
            try {
                $provider_wallet = new ProviderWallet;
                $provider_wallet->provider_id = $user_request->provider_id;
                $provider_wallet->transaction_id = $user_request->id;
                $provider_wallet->transaction_alias = $user_request->booking_id;
                $provider_wallet->payment_mode = $user_request->payment_mode;
                $provider_wallet->open_balance = 0.0;
                $provider_wallet->close_balance = 0.0;
                $provider_wallet->created_at = $user_request->updated_at;
                $provider_wallet->updated_at = $user_request->updated_at;
                $provider_wallet->transaction_desc = 'commission amount';
                $provider_wallet->amount = $provider_wallet->payment_mode == 'CASH' ? 0 - $user_request->commision : $user_request->total - $user_request->commision - $user_request->tax;
                if ($provider_wallet->payment_mode == 'CASH') {
                    $provider_wallet->save();

                    $provider_wallet1 = new ProviderWallet;
                    $provider_wallet1->provider_id = $user_request->provider_id;
                    $provider_wallet1->transaction_id = $user_request->id;
                    $provider_wallet1->transaction_alias = $user_request->booking_id;
                    $provider_wallet1->payment_mode = $user_request->payment_mode;
                    $provider_wallet1->open_balance = 0.0;
                    $provider_wallet1->close_balance = 0.0;
                    $provider_wallet1->transaction_desc = 'montant de la TVA crédité';
                    $provider_wallet1->amount = 0 - $user_request->tax;
                    $provider_wallet1->created_at = $user_request->updated_at;
                    $provider_wallet1->updated_at = $user_request->updated_at;
                    $provider_wallet1->save();

                    $Provider = Provider::where('id', $user_request->provider_id)->first();
                    if (!empty($Provider)) {
                        $Provider->wallet_balance -= $user_request->commision;
                        $Provider->wallet_balance -= $user_request->tax;
                        $Provider->save();
                    }
                } else {
                    $provider_wallet->transaction_desc = 'card amount';
                    $provider_wallet->save();

                    $Provider = Provider::where('id', $user_request->provider_id)->first();
                    if (!empty($Provider)) {
                        $Provider->wallet_balance += $provider_wallet->amount;
                        $Provider->save();
                    }
                }
            } catch (Exception $e) { }
        }
    }

    public function settlement_provider($id, $send_by)
    {
        $WalletRequest = WalletRequest::where('id', $id)->first();
        if ($WalletRequest == null) return false;
        try {
            $WalletRequest->type = 'CREDIT';
            // $WalletRequest->type = $send_by == 'online' ? 'CREDIT' : 'DEBIT';
            $WalletRequest->from_desc = $WalletRequest->type . ' du règlement';
            $WalletRequest->status = 'APPROVED';
            $WalletRequest->save();

            $Provider = Provider::where('id', $WalletRequest->from_id)->first();
            if (!empty($Provider)) {
                $Provider->wallet_balance += $WalletRequest->amount;
                $Provider->save();
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function cancel_settlement($id)
    {
        $WalletRequest = WalletRequest::where('id', $id)->first();
        if ($WalletRequest == null) return false;
        try {
            $WalletRequest->update(['status' => 'CANCEL']);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
