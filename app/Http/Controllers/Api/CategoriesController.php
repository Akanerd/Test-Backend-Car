<?php

namespace App\Http\Controllers\Api;
// import Model "Categories"
use App\Models\Categories;
// import Resource "CategoriesResource"
use App\Http\Resources\CategoriesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all categories from Model
        $category = Categories::join('users', 'categories.user_id', '=', 'users.id')
        ->get(['categories.*', 'users.name AS Nama Pemilik Mobil']);
        $category->makeHidden(['user_id']);;

        //return collection of category as a resource
        return new CategoriesResource(true, 'List Data Category', $category);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'user_id' => 'required|numeric',
        ]);

        //check if validation fails 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //Create Category
        $category = Categories::create([
            'name' => $request->name,
            'user_id' => $request->user_id,
        ]);

        //success save to database
        if ($category) {
            return new CategoriesResource(true, 'Category Data saved to database', $category);
        }
        
        // failed save to database
        return response()->json([
            'success' => false,
            'message'=> 'Category Failed to save',
        ],409);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //find category by id
        $category = Categories::join('users', 'categories.user_id', '=', 'users.id')
        ->get(['categories.*', 'users.name AS Nama Pemilik Mobil'])->find($id);

        //return single category as a resource
        return new CategoriesResource(true, 'Show Category Data by id ='.''.$id, $category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'user_id' => 'required|numeric',
        ]);

        //check if validation fails 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find category by id
        $category = Categories::join('users', 'categories.user_id', '=', 'users.id')
        ->get(['categories.*', 'users.name AS Nama Pemilik Mobil'])->find($id);
        
        //update category
        $category->update([
            'name' => $request->name,
            'user_id' => $request->user_id,
        ]);

        //return single category as a resource
        return new CategoriesResource(true, 'updated Category Data', $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //find category by ID
        $category = Categories::find($id);

        //Delete data
        $category->delete();

        //return single category as a resource
        return new CategoriesResource(true, 'Deleted Category Data', $category);
    }
}
