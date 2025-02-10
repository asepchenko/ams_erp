<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tes;
use Validator,Redirect,Response,File;
//use Illuminate\Support\Facades\Storage;

class TesController extends Controller
{
    public function index()
    {
        return view('admin.tes.index');
    }
 
    public function store(Request $request)
    {
 
        // file validation
        $validator      =   Validator::make($request->all(),
        ['image'      =>   'required|mimes:jpeg,png,jpg,bmp|max:2048']);

        // if validation fails
        if($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        //untuk 1 file udh bisa
        
        if($file = $request->file('image')) {
            $name = time().time().'.'.$file->getClientOriginalExtension();
            $path = $request->file('image')->storeAs(
                'public/plu', $name
            );
            $tes  = Tes::create(['image_front' => $name]);
            return back()->with("success", "File uploaded successfully");
        }
    }
}
