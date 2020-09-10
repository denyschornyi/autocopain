<?php

namespace App\Http\Controllers\ProviderResources;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Document;
use App\ProviderDocument;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $VehicleDocuments = Document::vehicle()->get();
        $DriverDocuments = Document::driver()->get();

        $Provider = \Auth::guard('provider')->user();

        return view('provider.document.index', compact('DriverDocuments', 'VehicleDocuments', 'Provider'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'document' => 'mimes:jpg,jpeg,png,pdf',
        ]);

        try {
            $providerDoc = new ProviderDocument;
            $providerDoc->provider_id = \Auth::guard('provider')->user()->id;
            $providerDoc->document_id = $request->documentType;
            $providerDoc->url = $request->document->store('provider/documents');
            $providerDoc->status = 'ASSESSING';
            $providerDoc->verification_status = 0;
            $providerDoc->save();

            return back();
        } catch (ModelNotFoundException $e) {
            ProviderDocument::create([
                'url' => $request->document->store('provider/documents'),
                'provider_id' => \Auth::guard('provider')->user()->id,
                // 'document_id' => $id,
                'status' => 'ASSESSING',
                'verification_status' => 0
            ]);
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'document' => 'mimes:jpg,jpeg,png,pdf',
        ]);

        try {

            $Document = ProviderDocument::where('provider_id', \Auth::guard('provider')->user()->id)
                    ->where('document_id', $id)
                    ->firstOrFail();

            $Document->update([
                'url' => $request->document->store('provider/documents'),
                'status' => 'ASSESSING',
                'verification_status' => 0
            ]);

            return back();
        } catch (ModelNotFoundException $e) {

            ProviderDocument::create([
                'url' => $request->document->store('provider/documents'),
                'provider_id' => \Auth::guard('provider')->user()->id,
                'document_id' => $id,
                'status' => 'ASSESSING',
                'verification_status' => 0
            ]);
        }

        return back();
    }

    public function documentupdate($image, $id, $provider_id) {

        try {

            $Document = ProviderDocument::where('provider_id', $provider_id)
                    ->where('document_id', $id)->with('provider')->with('document')
                    ->firstOrFail();

            Storage::delete($Document->url);

            // $filename=str_replace(" ","",$Document->document->name);
            // $ext = $image->guessExtension();
            // $path = $image->storeAs(
            //     "provider/documents/".$Document->provider_id, $filename.'.'.$ext
            // );
            // $Document->update([
            //     'url' => $path,
            //     'status' => 'ASSESSING',
            // ]);            
            $Document->update([
                'url' => $image->store('provider/documents'),
                'status' => 'ASSESSING',
                'verification_status' => 0
            ]);
        } catch (ModelNotFoundException $e) {

            $document = Document::find($id);
            // $filename=str_replace(" ","",$document->name);
            // $ext = $image->guessExtension();
            // $path = $image->storeAs(
            //     "provider/documents/".$provider_id, $filename.'.'.$ext
            // );
            // ProviderDocument::create([
            //         'url' => $path,
            //         'provider_id' => $provider_id,
            //         'document_id' => $id,
            //         'status' => 'ASSESSING',
            //     ]);
            ProviderDocument::create([
                'url' => $image->store('provider/documents'),
                'provider_id' => $provider_id,
                'document_id' => $id,
                'status' => 'ASSESSING',
                'verification_status' => 0
            ]);
        }

        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
