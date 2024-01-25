<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddData;
use \Validator;

class DataController extends Controller
{
    public function add(Request $request){

       

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()){
            return response()->json(['status' => 500 ,'errors' => $validator->errors()->first()], 500);
            return $this->errorResponse($validator->errors());
        }

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        $imageName = url('/images'). '/' .$imageName;

        $mobileData = new AddData([
            'name' => $request->get('name'),
            'image' => $imageName,
        ]);

        $mobileData->save();

        return response()->json(['status' => 200 , 'message' => 'Data added successfully'], 200);

    }

    public function view(){

        $data = AddData::all();

        return response()->json(['status' => 200 , 'data' => $data], 200);

    }

    public function details(Request $request, $id = 0){

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json(['status' => 500 ,'errors' => $validator->errors()->first()], 500);
            return $this->errorResponse($validator->errors());
        }

        $data = AddData::find($request->id);

        return response()->json(['status' => 200 , 'data' => $data], 200);

    }

    
}
