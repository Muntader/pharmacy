<?php

namespace App\Http\Controllers\Subuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Sale;
use DB;
use Auth;
class SalesController extends Controller
{
    /*
     *  Get all sales of user
     *  @return json response 
     * **********************************/

    public function get(){
        $getAllSales = DB::table('sales')
                         ->select('billing_number','total_price','customer_name','customer_phone','customer_orders','updated_at')
                         ->where('uid', Auth::user()->pharmacy_id)
                         ->paginate(15);
        if (count($getAllSales->all()) > 0) {
            return response()->json(['status' => 'success', 'data' => $getAllSales], 201);
        } else {
            return response()->json(['status' => 'empty'], 204);
        }
    }


    /*
     *  Search 
     *  @return json response 
     * **********************************/

    public function search(Request $request){
        $request->validate([
            'query' => 'required|max:70|regex:/^[a-z0-9 .\-]+$/i',
        ]);
        
        $getAllSales = DB::table('sales')
                         ->select('billing_number','total_price','customer_name','customer_phone','customer_orders','updated_at')
                         ->where('uid', Auth::user()->pharmacy_id)
                         ->Where('billing_number', 'LIKE', $request->input('query').'%')
                         ->orWhere('customer_name', 'LIKE', $request->input('query').'%')
                         ->orWhere('customer_phone', 'LIKE', $request->input('query').'%')
                         ->limit(15)
                         ->get();
        
       
        if(count($getAllSales->all()) > 0 ) {
            return response()->json(['status' => 'success', 'data' => $getAllSales],201);
        }else{
            return response()->json(['status' => 'empty'],204);
        }
    }



            
    /*
     *  Get Billing 
     *  @return json response 
     * **********************************/

    public function getBilling($id){
        $getBilling = DB::table('sales')
                         ->select('billing_number','total_price','customer_name','customer_phone','customer_orders','updated_at')
                         ->where('uid', Auth::user()->pharmacy_id)
                         ->where('billing_number',$id)
                         ->first();
        if(! is_null($getBilling) ) {
            return response()->json(['status' => 'success', 'data' => $getBilling],200);
        }else{
            return response()->json(['status' => 'empty'],204);
        }
    }

    /*
     *  Sort By
     *  @return json response
     * **********************************/

    public function update(Request $request)
    {

        $request->validate([
            'customer_name' => 'nullable|max:70|regex:/^[a-z0-9 .\-]+$/i',
            'customer_phone' => 'nullable|min:5|max:15',
            'total_price' => 'max:70|regex:/^[a-z0-9 .\-]+$/i',
            'billing_number' => 'nullable|max:70|regex:/^[a-z0-9 .\-]+$/i',
            'customer_orders.id' => 'numeric',
            'customer_orders.discount' => 'numeric',
            'customer_orders.name' => 'max:70|regex:/^[a-z0-9 .\-]+$/i',
            'customer_orders.price' => 'float',
            'customer_orders.quantity' => 'numeric',

        ]);


        $orderData = json_encode($request->input('customer_orders'));

        $storeInvoice = Sale::where('billing_number',$request->input('billing_number'))->first();   
        
        if (! is_null($storeInvoice)) {

            $storeInvoice->customer_name   = $request->input('customer_name');
            $storeInvoice->customer_phone  = $request->input('customer_phone');
            $storeInvoice->customer_orders = $orderData;
            $storeInvoice->total_price     = $request->input('total_price');
            $storeInvoice->billing_number  = $request->input('billing_number');
            $storeInvoice->uid             = Auth::user()->pharmacy_id;
        
            $storeInvoice->save();
            return response()->json(['status'  => 'success'], 200);
        }
    }


    /*
     *  Create new medicinal
     *  @return json response 
     * **********************************/

    public function export(Request $request) {

       $name = str_random(20);
       \Excel::create($name, function($excel) {

            $excel->sheet('Sales', function($sheet) {
                
                $data = Sale::where('uid', Auth::user()->pharmacy_id)->get();
                $sheet->fromArray($data);

            });

        })->store('xls', public_path('upload/files/'.Auth::user()->name.'/exports'));
       
        return response()->json(['status' => 'success', 'download_url' => url('/upload/files/'.Auth::user()->name.'/exports/'.$name.'.xls') ]);
    }

}
