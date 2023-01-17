<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Group_member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PhpParser\Node\Stmt\TryCatch;

class groupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userWiseGroupView()
    {
        try {
            $authId = Auth::user()->id;
            $member = Group_member::where('user_id', $authId)
                ->with('group','group.groupCreator')
                ->latest()
                ->get();
            $data = [
                'status' => true,
                'message' => 'Group list.',
                'status code' => 200,
                'data' => $member,
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
    public function singalGroup ($id)
    {
        $group = Group::where('id', $id)
            ->with('user')
            ->first();
        

        $data = [
            'status' => true,
            'message' => 'Group list.',
            'status code' => 200,
            'data' => $group,
        ];
        return response()->json($data);
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
    public function updateGroup(Request $request, $id)
    {
       
      
         
         try {

     

            $group = Group::findOrFail($id);
            $authId = Auth::user()->id;

            $request->validate([
                'name' => 'required',
                'description' => 'required',

            ]);

            $filename = "";

            $destination = public_path("images" . $group->image);

            if ($image = $request->file('image')) {
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $filename = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $filename);
            } else {
                $filename = $request->image;
            }


            $group->name = $request->name;
            $group->description = $request->description;
            $group->image = $filename;
            $group->save();

          


            
            $userHasPerDel = Group_member::where('group_id', $group->id)->get();
            foreach ($userHasPerDel as $key => $value) {
                $value->delete();
            }


             $groupArr = json_decode($request->member);

 

           


            if ($groupArr > 0) {
                foreach ($groupArr as $key => $userId) {
                    $groupMember[] = [
                        'group_id' => $group->id,
                        'user_id' => $userId,
                    ];
                }
                Group_member::insert($groupMember);
            }else{
                Group_member::create([
                    'group_id' => $group->id,
                    'user_id' => $authId,
                ]);
            }


            $data = [
                'status' => true,
                'message' => 'group Update Successfully.',
                'status code' => 200,
                'data' => $group,
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
    public function destroyGroup($id)
    {
        try {
            $group = Group::findOrFail($id);
            $deleteImage = public_path("images" . $group->image);
            if (File::exists($deleteImage)) {
                File::delete($deleteImage);
            }
            $result = $group->delete();
            $data = [
                'status' => true,
                'message' => 'Group Delate Successfully.',
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
