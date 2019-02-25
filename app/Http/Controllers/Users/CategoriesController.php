<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use App\Categorie;
class CategoriesController extends Controller
{
    /*
     *  Get all categories
     *  @return json response 
     * **********************************/

     public function get(){
         $getCategories = DB::table('categories')->where('uid',Auth::id())->get();

        if(count($getCategories) > 0 ) {
            return response()->json(['status' => 'success', 'data' => $getCategories],201);
        }else{
            return response()->json(['status' => 'empty'],204);
        }
     }


    /*
     *  Get all categories
     *  @return json response 
     * **********************************/

     public function create(Request $request){
            
            $request->validate([
                'items.one' => 'max:25|alpha_dash|nullable',
                'items.two' => 'max:25|alpha_dash|nullable',
                'items.three' => 'max:25|alpha_dash|nullable',
                'items.four' => 'max:25|alpha_dash|nullable',
                'items.fife' => 'max:25|alpha_dash|nullable',
                'items.six' => 'max:25|alpha_dash|nullable',

            ]);

            $array = [];

            foreach($request->input('items') as $a => $index) {
                if (! is_null($index)) {
                    $store = new Categorie();
                    $store->name = $index;
                    $store->uid = Auth::id();
                    $store->save();
                    array_push($array,['id' => $store->id, 'name' => $store->name]);
                }
            }

    
            return response()->json(['status' => 'success', 'data' => $array], 201);


     }




    /*
     *  Get all categories
     *  @return json response 
     * **********************************/

     public function delete($id){

            $check = Categorie::where('id',$id)->where('uid', Auth::id())->first();

            if(! is_null($check)){

                $check->delete();

             return response()->json(['status' => 'success'], 201);

            }
    
            return response()->json(['status' => 'error', 'message'  => 'There is no id'], 422);


     }


    /*
     *  Update category
     *  @return json response 
     * **********************************/

     public function update(Request $request){
            
            $request->validate([
                'id' => 'required|numeric',
                'name' => 'required|max:25',

            ]);
          
          
          
            $check = Categorie::where('id', $request->input('id'))->where('uid', Auth::id())->first();

            if (! is_null($check)) {
                $check->name = $request->input('name');
                $check->save();

                return response()->json(['status' => 'success'], 201);
            }

    


     }

}
