<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\sub_catagory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class subCatagoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = sub_catagory::with('catagory')->with('user')->paginate(5);
        return response()->json($data);
    }

 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      

        try {
            $subCatagory = new sub_catagory();
            $request->validate([
                'name' => 'required',
                'user_id' => 'required',
                'catagory_id' => 'required',
                'description' => 'required',
                
                'image' => 'image|mimes:jpg,png,jpeg,gif,svg',
            ]);

            $filename = "";
            if ($request->hasFile('image')) {
                $filename = $request->file('image')->store('images', 'public');
            } else {
                $filename = Null;
            }
            $subCatagory->name = $request->name;
            $subCatagory->user_id = $request->user_id;
            $subCatagory->catagory_id = $request->catagory_id;
            $subCatagory->description = $request->description;
            // $subCatagory->status = $request->status;
            $subCatagory->image = $filename;
            $result = $subCatagory->save();

            $data = [
                'status' => true,
                'message' => 'Sub Catagory created successfully.',
                'status code' => 200,
            ];
            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $data = sub_catagory::with('catagory')->with('user')->find($id);
        return response()->json($data);
    }
        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSubSubCatagory($id)
    {
       
        $data = sub_catagory::with('SubSubCatagory')->with('user')->find($id);
        return response()->json($data);
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data  = sub_catagory::findOrFail($id);
        return response()->json($data);
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
        try {
            $subCatagory = sub_catagory::findOrFail($id);

            $destination = public_path("storage\\" . $subCatagory->image);
            $filename = "";
            if ($request->hasFile('image')) {
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $filename = $request->file('image')->store('images', 'public');
            } else {
                $filename = $request->image;
            }

            $subCatagory->name = $request->name;
            $subCatagory->description = $request->description;
            $subCatagory->status = $request->status;
            $subCatagory->image = $filename;
            $data = $subCatagory->save();


            $data = [
                'status' => true,
                'message' => 'Sub Catagory Update Successfully.',
                'status code' => 200,
                'data' => $subCatagory,
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
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
        try {
            $subCatagory = sub_catagory::findOrFail($id);
            $destination = public_path("storage\\" . $subCatagory->image);
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $result = $subCatagory->delete();
            $data = [
                'status' => true,
                'message' => 'Sub Catagory Delate Successfully.',
                'status code' => 200,
            ];
            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
