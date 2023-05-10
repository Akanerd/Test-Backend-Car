<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MobilResource;
use App\Models\Mobil;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MobilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all Mobil
        $mobil = Mobil::join('categories', 'mobils.category_id', '=', 'categories.id')
        ->join('users', 'mobils.user_id', '=', 'users.id')
        ->get(['mobils.*', 'categories.name AS Produsen','users.name AS Nama Pemilik Mobil']);
        $mobil ->makeHidden(['category_id','user_id']);

        //return collection of article as a resource
        return new MobilResource(true, 'List Data Mobil', $mobil);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'user_id' => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/mobil', $image->hashName());

        // create articles
        $mobil = Mobil::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $image->hashName(),
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
        ]);

        return new MobilResource(true, 'Data Mobil saved', $mobil);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         $mobil = Mobil::join('categories', 'mobils.category_id', '=', 'categories.id')
        ->join('users', 'mobils.user_id', '=', 'users.id')->where('mobils.id','=',$id)
        ->get(['mobils.*', 'categories.name AS Produsen','users.name AS Nama Pemilik Mobil']);
        $mobil ->makeHidden(['category_id','user_id']);

        return new MobilResource(true, 'Data Mobil By id = ' . $id, $mobil);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'user_id' => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find Mobil by ID
        $mobil = Mobil::join('categories', 'mobils.category_id', '=', 'categories.id')
        ->join('users', 'mobils.user_id', '=', 'users.id')
        ->get(['mobils.*', 'categories.name AS Produsen','users.name AS Nama Pemilik Mobil'])->find($id);

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/mobil', $image->hashName());

            //delete old image
            Storage::disk('local')->delete('public/mobil/' . basename($mobil->image));

            //update Mobil with new image
            $mobil->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content,
                'user_id' => $request->user_id,
                'category_id' => $request->category_id,
            ]);
        } else {
            //update Mobil without image
            $mobil->update([
                'title'     => $request->title,
                'content'   => $request->content,
                'user_id' => $request->user_id,
                'category_id' => $request->category_id,
            ]);
        }

        //return response
        return new MobilResource(true, 'Data Mobil updated', $mobil);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //find mobil by id
        $mobil = Mobil::find($id);

        //delete image
        Storage::disk('local')->delete('public/mobil/' .  basename($mobil->image));

        //delete mobil
        $mobil->delete();

        //return response
        return new MobilResource(true, 'Data Mobil has been deleted', $mobil);
    }
}
