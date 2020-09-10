<?php

namespace App\Http\Controllers\ProviderResources;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Setting;
use Storage;
use App\ProviderProfile;
use App\ProviderService;
use App\Document;
use App\ProviderDocument;

class ProfileController extends Controller {

    /**
     * Create a new user instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('provider.api', [
            'except' => ['show', 'store', 'available', 'location_edit', 'location_update']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try {

            Auth::user()->service = ProviderService::where('provider_id', Auth::user()->id)
                    ->with('service_type')
                    ->first();
            Auth::user()->currency = Setting::get('currency', '€');

            return Auth::user();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Veuillez nous contacter à info@autocopain.com');
        }

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'mobile' => 'required',
            'avatar' => 'mimes:jpeg,bmp,png',
            'language' => 'max:255',
            'address' => 'max:255',
            'address_secondary' => 'max:255',
            'city' => 'max:255',
            'country' => 'max:255',
            'postal_code' => 'max:255',
        ]);


        try {

            $Provider = Auth::user();

            if ($request->has('first_name'))
                $Provider->first_name = $request->first_name;

            if ($request->has('last_name'))
                $Provider->last_name = $request->last_name;

            if ($request->has('mobile'))
                $Provider->mobile = $request->mobile;

            if ($request->has('description'))
                $Provider->description = $request->description;

            if ($request->hasFile('avatar')) {
                $Provider->avatar = $request->avatar->store('provider/profile');
            }

            if ($Provider->profile) {
                $Provider->profile->update([
                    'address' => $request->address ? : $Provider->profile->address,
                ]);
            } else {
                ProviderProfile::create([
                    'provider_id' => $Provider->id,
                    'address' => $request->address,
                ]);
            }


            $Provider->save();

            return redirect(route('provider.profile.index'))->with('flash_success', 'Profil mise à jour');
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Aucun dépanneur trouvé!'], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show() {
        $Provider = ProviderService::where('provider_id', Auth::user()->id)
                ->with('service_type')
                ->get();
        $allDocuments = Document::get();
        $VehicleDocuments = Document::vehicle()->get();
        $DriverDocuments = Document::driver()->get();
//        $DriverDocuments = ProviderDocument::leftJoin('documents', 'documents.id', '=', 'provider_documents.document_id')
//                ->select('provider_documents.*', 'documents.*')
//                ->get();
        return view('provider.profile.index', compact('Provider', 'DriverDocuments', 'VehicleDocuments', 'allDocuments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        
        if (Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Veuillez nous contacter à info@autocopain.com');
        }

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'mobile' => 'required',
            'avatar' => 'mimes:jpeg,bmp,png',
            'address' => 'max:255',
        ]);

        try {
            
            $Provider = Auth::user();
            if ($request->has('first_name'))
                $Provider->first_name = utf8_encode($request->first_name);

            if ($request->has('last_name'))
                $Provider->last_name = $request->last_name;

            if ($request->has('mobile'))
                $Provider->mobile = $request->mobile;

            if ($request->has('description'))
                $Provider->description = $request->description;

            if ($request->hasFile('avatar')) {
                $Provider->avatar = $request->avatar->store('provider/profile');
            }
            
            if ($Provider->profile) {
                $Provider->profile->update([
                    'address' => $request->address ? : $Provider->profile->address,
                ]);
            } else {
                ProviderProfile::create([
                    'provider_id' => $Provider->id,
                    'address' => $request->address,
                ]);
            }


            $Provider->save();
            return response()->json($Provider);
//            return $Provider;
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Aucun dépanneur trouvé'], 404);
        }
    }

    /**
     * Update latitude and longitude of the user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function location(Request $request) {
        $this->validate($request, [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($Provider = \Auth::user()) {

            $Provider->latitude = $request->latitude;
            $Provider->longitude = $request->longitude;
            $Provider->save();

            return response()->json(['message' => 'Lieu mis à jour avec succès!']);
        } else {
            return response()->json(['error' => 'Aucun dépanneur trouvé']);
        }
    }

    /**
     * Toggle service availability of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function available(Request $request) {
        $this->validate($request, [
            'service_status' => 'required|in:active,offline',
        ]);

        $Provider = Auth::user();

        if ($Provider->service) {
            $Provider->service->update(['status' => $request->service_status]);
        } else {
            return response()->json(['error' => 'Veuillez selectionner les dépannages que vous pouvez réaliser dans les Paramètres']);
        }

        return $Provider;
    }

    /**
     * Update password of the provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function password(Request $request) {
        if (Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Veuillez nous contacter à info@autocopain.com');
        }

        $this->validate($request, [
            'password' => 'required|confirmed',
            'password_old' => 'required',
        ]);

        $Provider = \Auth::user();

        if (password_verify($request->password_old, $Provider->password)) {
            $Provider->password = bcrypt($request->password);
            $Provider->save();

            return response()->json(['message' => 'Le mot de passe a été changé avec succès!']);
        } else {
            return response()->json(['error' => 'Veuillez entrer un mot de passe correct'], 422);
        }
    }

}
