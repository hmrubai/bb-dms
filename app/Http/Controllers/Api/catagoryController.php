<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\catagory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class catagoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authId = Auth::user()->id;
        $data = catagory::where("user_id", "=", $authId)
            ->with('user')
            ->latest()
            ->paginate(5);
        return response()->json($data);
    }





    public function allCategory()
    {
        $authId = Auth::user()->id;
        $data = catagory::where("user_id", "=", $authId)
            // ->where("status","=","Active")
            ->get();;
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            $catagory = new catagory();

            $request->validate([
                'name' => 'required',
                'user_id' => 'required',
                'description' => 'required',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg',
            ]);

            $filename = "";
            if ($image = $request->file('image')) {
                $filename = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $filename);
            } else {
                $filename = Null;
            }

            $catagory->name = $request->name;
            $catagory->user_id = $request->user_id;
            $catagory->description = $request->description;
            // $catagory->status = $request->status;
            $catagory->image = $filename;
            $catagory->save();

            $data = [
                'status' => true,
                'message' => 'Catagory created successfully.',
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
        $data = catagory::with('user')->find($id);
        return response()->json($data);
    }


    public function categoryList(Request $request)
    {
        $data = catagory::all();
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSubCatagory($id)
    {
        $authId = Auth::user()->id;

        $data = catagory::where("user_id", "=", $authId)

            ->with('subCatagory')
            ->with('subSubCatagory')
            ->find($id);

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
        $data  = catagory::findOrFail($id);
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

        // return response($request);

        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
            
            ]);
            $filename = "";
            $catagory = catagory::findOrFail($id);
            $destination = public_path("images" . $catagory->image);
           
            if ($image = $request->file('image')) {
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $filename = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $filename);
            } else {
                $filename = $request->image;
            }

            $catagory->name = $request->name;
            $catagory->description = $request->description;
            $catagory->status = $request->status;
            $catagory->image = $filename;
            $catagory->save();


            $data = [
                'status' => true,
                'message' => 'Catagory Update Successfully.',
                'status code' => 200,
                // 'data' => $catagory,
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
            $catagory = catagory::findOrFail($id);
            $deleteImage = public_path("images" . $catagory->image);
            if (File::exists($deleteImage)) {
                File::delete($deleteImage);
            }
            $result = $catagory->delete();
            $data = [
                'status' => true,
                'message' => 'Catagory Delate Successfully.',
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
