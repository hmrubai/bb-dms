<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\sub_sub_catagory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
class subSubCatagoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authId=Auth::user()->id;
        $data = sub_sub_catagory::where("user_id","=", $authId)
        ->with('catagory')
        ->with('user')
        ->with('subCatagory')
           ->latest()
        ->paginate(5);
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
            $subSubCatagory = new sub_sub_catagory();
            $request->validate([
                'name' => 'required',
                'user_id' => 'required',
                'catagory_id' => 'required',
                'sub_catagory_id' => 'nullable',
                'description' => 'required',
                // 'status' => 'required',
                'image' => 'image|mimes:jpg,png,jpeg,gif,svg',
            ]);

            $filename = "";
            if ($request->hasFile('image')) {
                $filename = $request->file('image')->store('images', 'public');
            } else {
                $filename = Null;
            }
            $subSubCatagory->name = $request->name;
            $subSubCatagory->user_id = $request->user_id;
            $subSubCatagory->catagory_id = $request->catagory_id;
            $subSubCatagory->sub_catagory_id = $request->sub_catagory_id;
            $subSubCatagory->description = $request->description;
            // $subSubCatagory->status = $request->status;
            $subSubCatagory->image = $filename;
            $result = $subSubCatagory->save();

            $data = [
                'status' => true,
                'message' => 'Sub Sub Catagory created successfully.',
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
        $data = sub_sub_catagory::with('catagory')->with('user')->with('subCatagory')->find($id);
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
        $data = sub_sub_catagory::find($id);
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
            $subCatagory = sub_sub_catagory::findOrFail($id);

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
            // $subCatagory->user_id = $request->user_id;
            // $subCatagory->catagory_id = $request->catagory_id;
            $subCatagory->description = $request->description;
            $subCatagory->status = $request->status;
            $subCatagory->image = $filename;
            $data = $subCatagory->save();


            $data = [
                'status' => true,
                'message' => 'Sub Sub Catagory Update Successfully.',
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
            $catagory = sub_sub_catagory::findOrFail($id);
            $destination = public_path("storage\\" . $catagory->image);
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $result = $catagory->delete();
            $data = [
                'status' => true,
                'message' => 'sub Sub Catagory Delate Successfully.',
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
