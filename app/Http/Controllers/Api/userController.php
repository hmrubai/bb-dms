<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::all();
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = User::find($id);
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
        //
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
            $catagory = User::findOrFail($id);

            $destination = public_path("storage\\" . $catagory->image);
            $filename = "";
            if ($request->hasFile('image')) {
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $filename = $request->file('image')->store('images', 'public');
            } else {
                $filename = $request->image;
            }

            $catagory->name = $request->name;
            $catagory->image = $filename;
            $catagory->status = $request->status;

            $data = $catagory->save();


            $data = [
                'status' => true,
                'message' => 'User Update Successfully.',
                'status code' => 200,
                'data' => $catagory,
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
            $catagory = User::findOrFail($id);
            $destination = public_path("storage\\" . $catagory->image);
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $result = $catagory->delete();
            $data = [
                'status' => true,
                'message' => 'User Delate Successfully.',
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
