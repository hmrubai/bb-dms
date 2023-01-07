<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Group_member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class groupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function createGroup(Request $request)
    {
        try {
            $authId = Auth::user()->id;

            $group = new Group();
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'member' => 'required',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg',


            ]);

            $filename = "";
            if ($image = $request->file('image')) {
                $filename = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $filename);
            } else {
                $filename = Null;
            }


            $group->name = $request->name;
            $group->user_id = $authId;
            $group->description = $request->description;
            $group->image = $filename;
            $group->save();

            $groupArr = json_decode($request->member);

            Group_member::create([
                'group_id' => $group->id,
                'user_id' => $authId,
            ]);

            if ($groupArr) {
                foreach ($groupArr as $key => $userId) {
                    $groupMember[] = [
                        'group_id' => $group->id,
                        'user_id' => $userId,
                    ];
                }
                Group_member::insert($groupMember);
            }



            $data = [
                'status' => true,
                'message' => 'Group created successfully.',
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
