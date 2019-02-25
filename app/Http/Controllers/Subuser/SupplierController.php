<?php

namespace App\Http\Controllers\Subuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Supplier;
use DB;
use Auth;
class SupplierController extends Controller
{
   /*
     *  Get all supplier
     *  @return json response 
     * **********************************/

     public function get(){
         $getSuppliers = DB::table('suppliers')->where('uid',Auth::user()->pharmacy_id)->get();
        if(count($getSuppliers) > 0 ) {
            return response()->json(['status' => 'success', 'data' => $getSuppliers],201);
        }else{
            return response()->json(['status' => 'empty'],204);
        }
     }


    /*
     *  Create supplier
     *  @return json response 
     * **********************************/

     public function create(Request $request){
            
            $request->validate([
                'supplier.name' => 'max:25|alpha_dash|required',
                'supplier.address' => 'max:25|alpha_dash|nullable',
                'supplier.telephone' => 'max:25|alpha_dash|nullable',
                'supplier.fax' => 'max:25|alpha_dash|nullable',

            ]);


            $store = new Supplier();
            $store->name = $request->input('supplier.name');
            $store->address = $request->input('supplier.address');
            $store->telephone = $request->input('supplier.telephone');
            $store->fax = $request->input('supplier.fax');
            $store->uid = Auth::user()->pharmacy_id;
            
            $store->save();
     

    
            return response()->json(['status' => 'success', 'data' => $store], 201);


     }



    /*
     *  Update supplier
     *  @return json response 
     * **********************************/

     public function update(Request $request){
            
            $request->validate([
                'name' => 'max:25|alpha_dash|required',
                'address' => 'max:25|alpha_dash|nullable',
                'telephone' => 'max:25|alpha_dash|nullable',
                'fax' => 'max:25|alpha_dash|nullable',
            ]);

            $check = Supplier::where('id', $request->input('id'))->where('uid', Auth::user()->pharmacy_id)->first();
            
            if (! is_null($check)) {

                $check->name = $request->input('name');
                $check->address = $request->input('address');
                $check->telephone = $request->input('telephone');
                $check->fax = $request->input('fax');
                $check->uid = Auth::user()->pharmacy_id;
            
                $check->save();

                return response()->json(['status' => 'success', 'data' => $check], 201);
            }

     }



    /*
     *  Delete supplier
     *  @return json response 
     * **********************************/

     public function delete($id){

            $check = Supplier::where('id',$id)->where('uid', Auth::user()->pharmacy_id)->first();

            if(! is_null($check)){

                $check->delete();

             return response()->json(['status' => 'success'], 201);

            }
    
            return response()->json(['status' => 'error', 'message'  => 'There is no id'], 422);


     }


}
