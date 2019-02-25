<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Subuser;
use App\User;
use App\Role;
use Auth;

class UsersController extends Controller
{
    public function get()
    {
        $subusers =  Subuser::selectRaw('id, name, image, email, permission, status ,created_at, "subuser" as type ')->where('pharmacy_id', Auth::id())->get();
   
        if (count($subusers) > 0) {
            return response()->json(['status' => 'success', 'data' => $subusers], 201);
        } else {
            return response()->json(['status' => 'empty'], 204);
        }
    }
    
    
    /*
     *  Create subuser
     *  @return json response
     * **********************************/

    public function create(Request $request)
    {
        $request->validate([
                'subuser.name' => 'max:25|alpha_dash|required',
                'subuser.email' => 'max:25|email|required',
                'subuser.permission' => 'max:25|numeric|required',
                'subuser.status' => 'max:25|numeric|required',
                'subuser.password' => 'max:25|required',
            ]);
            
        $check = Subuser::where('email', $request->input('subuser.email'))->first();

        if (! is_null($check)) {
            return response()->json(['status' => 'error', 'message' => 'The email is already'], 422);
        }

        $store = new Subuser();
        $store->name = $request->input('subuser.name');
        $store->email = $request->input('subuser.email');
        $store->permission = $request->input('subuser.permission');
        $store->status = $request->input('subuser.status');
        $store->password = Hash::make($request->input('subuser.password'));

        $store->pharmacy_id = Auth::id();

        $store->save();
    
        return response()->json(['status' => 'success', 'data' => $store], 201);
    }



    /*
     *  Update subuser
     *  @return json response
     * **********************************/

    public function update(Request $request)
    {
        $request->validate([
                'id' => 'required|uuid',
                'name' => 'max:25|alpha|nullable',
                'email' => 'max:25|email|nullable',
                'permission' => 'max:25|numeric|nullable',
                'status' => 'max:25|numeric|nullable',
                'password' => 'max:25|nullable',
            ]);

        $check = Subuser::where('id', $request->input('id'))->where('pharmacy_id', Auth::id())->first();
            
        if (! is_null($check)) {
            if ($request->has('name')) {
                $check->name = $request->input('name');
            }
            if ($request->has('email')) {
                $check->email = $request->input('email');
            }
            if ($request->has('permission')) {
                $check->permission = $request->input('permission');
            }
            if ($request->has('status')) {
                $check->status = $request->input('status');
            }
            if ($request->has('password')) {
                $check->password = Hash::make($request->input('password'));
            }


            $check->pharmacy_id = Auth::id();
            
            $check->save();

            return response()->json(['status' => 'success', 'data' => $check], 201);
        }
    }


    /*
     *  Delete user
     *  @return json response 
     * **********************************/

     public function delete($id){

            $check = Subuser::where('id',$id)->where('pharmacy_id', Auth::id())->first();

            if(! is_null($check)){

                $check->delete();

             return response()->json(['status' => 'success'], 201);

            }
    
            return response()->json(['status' => 'error', 'message'  => 'There is no id'], 422);


     }


}
