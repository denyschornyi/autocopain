<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Exception;
use Storage;
use Setting;
use Carbon\Carbon;
use App\UserRequests;
use App\User;
use App\ServiceType;
use App\ProviderService;
use App\ProviderCard;
use App\Http\Controllers\SendPushNotification;

class ProviderApiController extends Controller
{

    /**
     * Show the services.
     *
     * @return \Illuminate\Http\Response
     */
    public function services()
    {

        if ($Services = ServiceType::all()) {
            foreach ($Services as $key => $value) {

                $price = ProviderService::where('provider_id', Auth::user()->id)
                    ->where('service_type_id', $value->id)
                    ->first();

                if ($price) {
                    $Services[$key]->available = true;
                } else {
                    $Services[$key]->available = false;
                }
            }
            return $Services;
        } else {
            return response()->json(['error' => 'No Services!'], 500);
        }
    }

    public function servicess($Category_id)
    {

        if ($serviceList = ServiceType::where('category_id', $Category_id)->get()) {
            foreach ($serviceList as $key => $value) {

                $price = ProviderService::where('provider_id', Auth::user()->id)
                    ->where('service_type_id', $value->id)
                    ->first();

                if ($price) {
                    $serviceList[$key]->available = true;
                } else {
                    $serviceList[$key]->available = false;
                }
            }
            return $serviceList;
        } else {
            return response()->json(['error' => trans('api.services_not_found')], 500);
        }
    }

    /**
     * Remove all exsited services and Add the services.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_services(Request $request)
    {

        $this->validate($request, [
            'services' => 'required',
        ]);

        try {

            $checked_services = $request->services;

            ProviderService::where('provider_id', Auth::user()->id)->delete();

            foreach ($checked_services as $value) {
                $add_service = new ProviderService;
                $add_service->provider_id = Auth::user()->id;
                $add_service->service_type_id = $value;
                $add_service->save();
            }

            return response()->json(['message' => "Services Updated"]);
        } catch (Exception $e) {

            if ($request->ajax()) {
                return response()->json(['error' => "try again later"], 500);
            } else {
                return back()->with('flash_error', 'Something went wrong');
            }
        }
    }

    /**
     * Add new and Remove the services.
     *
     * @return \Illuminate\Http\Response
     */
    public function update2_services(Request $request)
    {

        $this->validate($request, [
            // 'services' => 'required',
        ]);

        try {

            $checked_services = $request->services;

            // only add new services
            foreach ($checked_services as $value) {
                $service = ProviderService::where('provider_id', Auth::user()->id)->where('service_type_id', $value)->first();
                if ($service) {
                    continue;
                }
                $add_service = new ProviderService;
                $add_service->provider_id = Auth::user()->id;
                $add_service->service_type_id = $value;
                $add_service->save();
            }
            // remove unservices
            $unchecked_services = $request->unservices;
            foreach ($unchecked_services as $value) {
                ProviderService::where('provider_id', Auth::user()->id)->where('service_type_id', $value)->delete();
            }

            return response()->json(['message' => "Services Updated"]);
        } catch (Exception $e) {

            if ($request->ajax()) {
                return response()->json(['error' => "try again later"], 500);
            } else {
                return back()->with('flash_error', 'Something went wrong');
            }
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function upcoming_request()
    {

        try {

            $Jobs = UserRequests::where('provider_id', Auth::user()->id)
                ->where('status', 'SCHEDULED')
                ->with('user', 'service_type', 'payment', 'rating')
                ->get();
            if (!empty($Jobs)) {
                $map_icon = asset('asset/marker.png');
                foreach ($Jobs as $key => $value) {
                    $Jobs[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?autoscale=1&size=320x130&maptype=terrian&format=png&visual_refresh=true&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude . "&key=" . env('GOOGLE_MAP_KEY');
                }
            }

            return $Jobs;
        } catch (Exception $e) {
            return response()->json(['error' => "Something Went Wrong"]);
        }
    }

    public function target()
    {

        try {

            $rides = UserRequests::where('provider_id', Auth::user()->id)
                ->where('status', 'COMPLETED')
                ->where('created_at', '>=', Carbon::today())
                ->with('payment', 'service_type')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'rides' => $rides,
                'rides_count' => $rides->count(),
                'target' => Setting::get('daily_target', '0')
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => "Something Went Wrong"]);
        }
    }

    /**
     * Show the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function user(Request $request)
    {

        $this->validate($request, [
            'user_id' => 'required|numeric|exists:users,id'
        ]);

        if ($User = User::find($request->user_id)) {
            return $User;
        } else {
            return response()->json(['error' => 'No User Found!'], 500);
        }
    }

    public function setDefaultCard(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);
        try {
            ProviderCard::where('user_id', Auth::user()->id)->update(['is_default' => 0]);
            $provider = ProviderCard::where('id', $request->id)->where('user_id', Auth::user()->id)->first();
            if (!empty($provider)) {
                $provider->is_default = 1;
                $provider->save();
                return response()->json(['success' => 'Success!!']);
            } else {
                return response()->json(['success' => 'Card Not Found']);
            }
        } catch (Exception $e) { 
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }

    /**
     * add wallet money for provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_money(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|integer',
            // 'card_id' => 'required|exists:provider_cards,card_id,user_id,' . Auth::user()->id
            'card_id' => 'required',
        ]);

        try {
            $card = ProviderCard::where(['user_id' => Auth::user()->id, 'card_id' => $request->card_id])->first();
            if (empty($card)) {
                return response()->json(['message' => 'Card Not Found']);
            }

            $StripeWalletCharge = $request->amount * 100;

            \Stripe\Stripe::setApiKey(Setting::get('stripe_secret_key'));
            $currency = $this->getCurrencyString(Setting::get('currency', ''));

            $Charge = \Stripe\Charge::create(array(
                "amount" => $StripeWalletCharge,
                "currency" => $currency,
                "customer" => Auth::user()->stripe_cust_id,
                "card" => $request->card_id,
                "description" => "Adding Money for " . Auth::user()->email,
                "receipt_email" => Auth::user()->email
            ));

            $update_user = Provider::find(Auth::user()->id);
            $update_user->wallet_balance += $request->amount;
            $update_user->save();

            ProviderCard::where('user_id', Auth::user()->id)->update(['is_default' => 0]);
            ProviderCard::where('card_id', $request->card_id)->update(['is_default' => 1]);

            //sending push on adding wallet money
            (new SendPushNotification)->WalletMoney(Auth::user()->id, currency($request->amount));

            if ($request->ajax()) {
                return response()->json(['message' => currency($request->amount) . trans('api.added_to_your_wallet'), 'user' => $update_user]);
            } else {
                return redirect('wallet')->with('flash_success', currency($request->amount) . ' added to your wallet');
            }
        } catch (\Stripe\StripeInvalidRequestError $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            } else {
                return back()->with('flash_error', $e->getMessage());
            }
        }
    }


}
