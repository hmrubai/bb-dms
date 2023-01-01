<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\userHasPermission;
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
        $data = User::where('id', '!=', 1)
        ->latest()
        ->paginate(5);
        return response()->json($data);
    }

    public function allUser()
    {
        $data = User::with('userHasPermission', 'userHasPermission.permission')
        ->get();
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
            $user = new User();
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'username' => 'required',
                'password' => [
                    'required',
                    'min:6',
                    'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                ],
                'image' => ' nullable|image|mimes:jpg,png,jpeg,gif,svg',
                'gender' => 'required',
                'number' => 'required',
            ]);

            $filename = null;
            if ($request->hasFile('image')) {
                $filename = $request->file('image')->store('images', 'public');
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->number = $request->number;
            $user->password = bcrypt($request->password);
            $user->status = $request->status;
            $user->gender = $request->gender;
            $user->image = $filename;
            $user->save();
            $permissionArr = json_decode($request->permission);

            if ($permissionArr) {
                // $permission_list = explode(",", $request->permission);
                foreach ($permissionArr as $key => $permissionId) {
                    $userHasPer[] = [
                        'user_id' => $user->id,
                        'permission_id' => $permissionId,
                    ];
                }
                userHasPermission::insert($userHasPer);
            }
            $data = [
                'status' => true,
                'message' => 'User created successfully.',
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
        $data = User::with('userHasPermission', 'userHasPermission.permission')->find($id);
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

            $user = User::findOrFail($id);
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $user->id,
                // 'username' => 'required',
 
            ]);
            $destination = public_path("storage\\" . $user->image);
            $filename = "";
            if ($request->hasFile('image')) {
                if (File::exists($destination)) {
                    File::delete($destination);
                }
                $filename = $request->file('image')->store('images', 'public');
            } else {
                $filename = $request->image;
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->number = $request->number;

            $user->status = $request->status;
            $user->gender = $request->gender;
            $user->image = $filename;
            $user->save();


            
            $userHasPerDel = userHasPermission::where('user_id', $user->id)->get();
            foreach ($userHasPerDel as $key => $value) {
                $value->delete();
            }

            $permissionArr = json_decode($request->permission);

            if ($permissionArr) {
                foreach ($permissionArr as $key => $permissionId) {
                    $userHasPer[] = [
                        'user_id' => $user->id,
                        'permission_id' => $permissionId,
                    ];
                }
                userHasPermission::insert($userHasPer);
            }

            $data = [
                'status' => true,
                'message' => 'User Update Successfully.',
                'status code' => 200,
                'data' => $user,
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
