<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Sale;
use App\Medicinal;

class PosController extends Controller
{

    /*
     *  Get all medicinal of user
     *  @return json response
     * **********************************/

    public function get()
    {
        $getAllMedicinal = DB::table('medicinals')
                         ->select('m_id AS id', 'm_gname As gname', 'm_bname AS bname', 'm_price AS price', 'm_seffect AS side_effect', 'm_discount AS product_discount', 'm_quantity AS product_quantity', 'm_barcodeg AS product_barcode', 'categories.name AS category')
                         ->join('categories', 'categories.id', '=', 'medicinals.m_cat')
                         ->where('medicinals.uid', Auth::id())
                         ->limit(40)
                         ->get();
        if (count($getAllMedicinal->all()) > 0) {
            return response()->json(['status' => 'success', 'data' => $getAllMedicinal], 201);
        } else {
            return response()->json(['status' => 'empty'], 204);
        }
    }



    /*
    *  Sort By
    *  @return json response
    * **********************************/

    public function sortby(Request $request)
    {
        $request->validate([
            'type' => 'max:70|regex:/^[a-z0-9 .\-]+$/i',
            'category_id' => 'numeric',
        ]);

        if ($request->input('type') === 'category') {
            $getAllMedicinal = DB::table('medicinals')
                         ->select('m_id AS id', 'm_gname As gname', 'm_bname AS bname', 'm_price AS price', 'm_seffect AS side_effect', 'm_discount AS product_discount', 'm_quantity AS product_quantity', 'm_barcodeg AS product_barcode', 'categories.name AS category')
                         ->join('categories', 'categories.id', '=', 'medicinals.m_cat')
                         ->where('medicinals.uid', Auth::id())
                         ->where('m_cat', '=', $request->input('category_id'))
                         ->limit(40)
                         ->get();
        } elseif ($request->input('type') === 'outstock') {
            $getAllMedicinal = DB::table('medicinals')
                         ->select('m_id AS id', 'm_gname As gname', 'm_bname AS bname', 'm_price AS price', 'm_seffect AS side_effect', 'm_discount AS product_discount', 'm_quantity AS product_quantity', 'm_barcodeg AS product_barcode', 'categories.name AS category')
                         ->join('categories', 'categories.id', '=', 'medicinals.m_cat')
                         ->where('medicinals.uid', Auth::id())
                         ->where('m_quantity', '=', '0')
                         ->limit(40)
                         ->get();
        } elseif ($request->input('type') === 'expire') {
            $getAllMedicinal = DB::table('medicinals')
                         ->select('m_id AS id', 'm_gname As gname', 'm_bname AS bname', 'm_price AS price', 'm_seffect AS side_effect', 'm_discount AS product_discount', 'm_quantity AS product_quantity', 'm_barcodeg AS product_barcode', 'categories.name AS category')
                         ->join('categories', 'categories.id', '=', 'medicinals.m_cat')
                         ->where('medicinals.uid', Auth::id())
                         ->where('m_exdate', '<=', 'NOW()')
                         ->limit(40)
                         ->get();
        }


        if (count($getAllMedicinal->all()) > 0) {
            return response()->json(['status' => 'success', 'data' => $getAllMedicinal], 201);
        } else {
            return response()->json(['status' => 'empty'], 204);
        }
    }



    /*
     *  Sort By
     *  @return json response
     * **********************************/

    public function create(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|max:70|regex:/^[a-z0-9 .\-]+$/i',
            'customer_phone' => 'nullable|min:5|max:15',
            'total_price' => 'max:70|regex:/^[a-z0-9 .\-]+$/i',
            'billing_number' => 'nullable|max:70|regex:/^[a-z0-9 .\-]+$/i',
            'customer_order.id' => 'numeric',
            'customer_order.discount' => 'numeric',
            'customer_order.name' => 'max:70|regex:/^[a-z0-9 .\-]+$/i',
            'customer_order.price' => 'float',
            'customer_order.quantity' => 'numeric',

        ]);
        
        foreach($request->input('customer_order') as $key){
           $check = Medicinal::where('m_id', $key['id'])->where('uid', Auth::id())->first();

           if(! is_null($check)){
              $quantity = $check->m_quantity - $key['quantity'];

              if($quantity < 0){
                    return response()->json(['status'  => 'error' , 'message' => 'The product is in out stock'], 422);
              }

              $check->m_quantity = $check->m_quantity - $key['quantity'];
              $check->save();
        
           } else {

              return response()->json(['status'  => 'error' , 'message' => 'There is no product'], 422);

           }

        }

        $orderData = json_encode($request->input('customer_order'));

        $storeInvoice = new Sale();
        $storeInvoice->customer_name   = $request->input('customer_name');
        $storeInvoice->customer_phone  = $request->input('customer_phone');
        $storeInvoice->customer_orders = $orderData;
        $storeInvoice->total_price     = $request->input('total_price');
        $storeInvoice->billing_number  = rand(5, 1000000000);
        $storeInvoice->uid             = Auth::id();
        
        $storeInvoice->save();

        return response()->json(['status'  => 'success', 'invoice_number' => $storeInvoice->billing_number], 200);
    }
}
