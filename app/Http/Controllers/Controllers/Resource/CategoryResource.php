<?php

namespace App\Http\Controllers\Resource;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use Exception;
use Storage;
use Setting;

class CategoryResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $services = Category::all();
        if($request->ajax()) {
            return $services;
        } else {
            return view('admin.category.index', compact('services'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$this.categories();
        return view('admin.category.create');
    }
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
  
    public function category()
    {
        //return view('admin.service.categories');
        return view('admin.service.category');
        echo 'hello and how are you this is my categories page for testing...';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */




    public function store(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@appoets.com');
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'image' => 'mimes:ico,png,jpg,jpeg'
            
        ]);

        try{

           
           $category = new Category;
            if ($request->hasFile('image')) {

                $category->image = $request->image->store('service');
            }
           $category->name = $request->name;
           $category->orderNu = $request->orderNu;
           $category->status = '1';
           $category->save();
           return back()->with('flash_success','Category Saved Successfully');

        } 

        catch (Exception $e) {
            return back()->with('flash_errors', 'Category Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return Category::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $service = Category::findOrFail($id);
            return view('admin.category.edit',compact('service'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@appoets.com');
        }

        $this->validate($request, [
            'name' => 'required|max:255',
             'image' => 'mimes:ico,png,jpg,jpeg'
        ]);

        try {

            $service = Category::findOrFail($id);

            if ($request->hasFile('image')) {
                $service->image = $request->image->store('service');
            }

            $service->name = $request->name;
            $service->orderNu = $request->orderNu;
            $service->save();

            return redirect()->route('admin.category.index')->with('flash_success', 'Category Updated Successfully');    
        } 

        catch (ModelNotFoundException $e) {
            return back()->with('flash_errors', 'Category Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Category::find($id)->delete();
            return back()->with('message', 'Category deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_errors', 'Category Not Found');
        }
    }
}
