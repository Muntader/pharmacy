<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Custorder;
use App\Lastcs;
use App\Medicinal;

use DB;
use Auth;

class CustomersController extends Controller
{
    /*
      *  Get all supplier
      *  @return json response
      * **********************************/

    public function get()
    {
        $getCustomers = DB::table('customers')
                    ->selectRaw('customers.customer_id ,customers.customer_name, customers.customer_address, customers.customer_phone, customers.customer_info, COUNT(lastcs.customer_id) AS customer_salesnumber')
                    ->leftJoin('lastcs', 'lastcs.customer_id', '=', 'customers.customer_id')
                    ->where('customers.uid', Auth::id())
                    ->groupBy('customers.customer_id')
                    ->paginate();
     
        if (count($getCustomers->all()) > 0) {
            return response()->json(['status' => 'success', 'data' => $getCustomers], 201);
        } else {
            return response()->json(['status' => 'empty'], 204);
        }
    }


    /*
     *  Search
     *  @return json response
     * **********************************/

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|max:70|regex:/^[a-z0-9 .\-]+$/i',
        ]);
        
        $getCustomers = DB::table('customers')
                    ->selectRaw('customers.customer_id ,customers.customer_name, customers.customer_address, customers.customer_phone, customers.customer_info, COUNT(lastcs.customer_id) AS customer_salesnumber')
                    ->leftJoin('lastcs', 'lastcs.customer_id', '=', 'customers.customer_id')
                    ->where('customers.uid', Auth::id())
                    ->where('customers.customer_name', 'LIKE', $request->input('query').'%')
                    ->orWhere('customers.customer_phone', 'LIKE', $request->input('query').'%')
                    ->orWhere('customers.customer_id', 'LIKE', $request->input('query').'%')
                    ->groupBy('customers.customer_id')
                    ->limit(10)
                    ->get();

        
       
        if (count($getCustomers->all()) > 0) {
            return response()->json(['status' => 'success', 'data' => $getCustomers], 201);
        } else {
            return response()->json(['status' => 'empty'], 204);
        }
    }


    /*
     *  Get some medicinal
     *  @return json response
     * **********************************/

    public function getCustomerOrders($id)
    {
        $getCustomer = DB::table('custorders')
                         ->selectRaw('custorders.customer_info, custorders.customer_totalprice, custorders.customer_orders, custorders.customer_invoiceno, custorders.customer_id, COUNT(lastcs.id) AS order_sale_number')
                         ->join('customers', 'customers.customer_id', '=', 'custorders.customer_id')
                         ->leftJoin('lastcs', 'lastcs.customer_invoiceno', '=', 'custorders.customer_invoiceno')
                         ->where('custorders.uid', Auth::id())
                         ->where('custorders.customer_id', $id)
                         ->orderBy('custorders.id', 'ASC')
                         ->groupBy('custorders.id')
                         ->get();
        if (count($getCustomer->all()) > 0) {
            return response()->json(['status' => 'success', 'data' => $getCustomer], 200);
        } else {
            return response()->json(['status' => 'empty'], 204);
        }
    }


    /*
     *  Create supplier
     *  @return json response
     * **********************************/

    public function create(Request $request)
    {
        $request->validate([
                'customer.name' => 'max:25|regex:/^[a-z0-9 .\-]+$/i|required',
                'customer.address' => 'max:25|regex:/^[a-z0-9 .\-]+$/i|nullable',
                'customer.telephone' => 'max:25|regex:/^[a-z0-9 .\-]+$/i|nullable',
                'customer.info' => 'max:25|regex:/^[a-z0-9 .\-]+$/i|nullable',

            ]);


        $store = new Customer();
        $store->customer_name = $request->input('customer.name');
        $store->customer_address = $request->input('customer.address');
        $store->customer_phone = $request->input('customer.telephone');
        $store->customer_info = $request->input('customer.fax');
        $store->customer_id = str_random(15);
        $store->uid = Auth::id();
            
        $store->save();
     

    
        return response()->json(['status' => 'success', 'data' => $store], 201);
    }



    /*
     *  Update supplier
     *  @return json response
     * **********************************/

    public function update(Request $request)
    {
        $request->validate([
                'name' => 'max:25|regex:/^[a-z0-9 .\-]+$/i|nullable',
                'address' => 'max:25|regex:/^[a-z0-9 .\-]+$/i|nullable',
                'telephone' => 'max:25|regex:/^[a-z0-9 .\-]+$/i|nullable',
                'info' => 'max:25|regex:/^[a-z0-9 .\-]+$/i|nullable',
                'customer_id' => 'required|alpha_dash|max:15',
            ]);

        $check = Customer::where('customer_id', $request->input('customer_id'))->where('uid', Auth::id())->first();
            
        if (! is_null($check)) {
            $check->customer_name = $request->input('name');
            $check->customer_address = $request->input('address');
            $check->customer_phone = $request->input('telephone');
            $check->customer_info = $request->input('info');
            $check->uid = Auth::id();

            $check->save();

            return response()->json(['status' => 'success', 'data' => $check], 201);
        }
    }



    /*
     *  Delete supplier
     *  @return json response
     * **********************************/

    public function delete($id)
    {
        $check = Customer::where('customer_id', $id)->where('uid', Auth::id())->first();

        if (! is_null($check)) {
            $check->delete();

            return response()->json(['status' => 'success'], 201);
        }
    
        return response()->json(['status' => 'error', 'message'  => 'There is no id'], 422);
    }


    /*
     *  Make New Payemnt
     *  @return json response
     * **********************************/

    public function newPayment(Request $request)
    {
        $request->validate([
            'id'  =>  'required|max:15|alpha_dash',
            'customer_order' => 'required',
            'total_price' => 'required|numeric'
        ]);
       
       
        foreach ($request->input('customer_order') as $key) {
            $check = Medicinal::where('m_id', $key['id'])->where('uid', Auth::id())->first();

            if (! is_null($check)) {
                $quantity = $check->m_quantity - $key['quantity'];

                if ($quantity < 0) {
                    return response()->json(['status'  => 'error' , 'message' => 'The product is in out stock'], 422);
                }

                $check->m_quantity = $check->m_quantity - $key['quantity'];
                $check->save();
            } else {
                return response()->json(['status'  => 'error' , 'message' => 'There is no product'], 422);
            }
        }



        $check = Customer::where('customer_id', $request->input('id'))->where('uid', Auth::id())->first();
        
        if (! is_null($check)) {
            $orderData = json_encode($request->input('customer_order'));

            $storeInvoice = new Custorder();
            $storeInvoice->customer_id          = $request->input('id');
            $storeInvoice->customer_totalprice  = $request->input('total_price');
            $storeInvoice->customer_invoiceno   = rand(5, 1000000000);
            $storeInvoice->customer_orders      = $orderData;
            $storeInvoice->uid                  = Auth::id();
            $storeInvoice->save();

            $lastcs = new Lastcs();
            $lastcs->customer_id = $request->input('id');
            $lastcs->customer_invoiceno = $storeInvoice->customer_invoiceno;
            $lastcs->uid =  Auth::id();
            $lastcs->save();


            return response()->json(['status'  => 'success', 'customer_invoiceno' => $storeInvoice->customer_invoiceno], 200);
        } else {
            return response()->json(['status'  => 'error' , 'message' => 'There is no customer'], 422);
        }
    }


    /*
     *  Get Billing
     *  @return json response
     * **********************************/

    public function getCustomerBilling(Request $request) {
      
      
        $getCustomerBilling = DB::table('custorders')
                         ->selectRaw('custorders.customer_info, custorders.customer_totalprice, custorders.customer_orders, custorders.customer_invoiceno, custorders.customer_id, customers.customer_name, customers.customer_phone')
                         ->join('customers', 'customers.customer_id', '=', 'custorders.customer_id')
                         ->where('custorders.uid', Auth::id())
                         ->where('custorders.customer_id', $request->input('customer_id'))
                         ->where('custorders.customer_invoiceno', $request->input('invoice_number'))
                         ->orderBy('custorders.id', 'ASC')
                         ->groupBy('custorders.id')
                         ->get();
          
        $getCustomerBillingSalesNumber = DB::table('lastcs')
                         ->select('created_at', 'customer_invoiceno')
                         ->where('uid', Auth::id())
                         ->where('customer_id', $request->input('customer_id'))
                         ->where('customer_invoiceno', $request->input('invoice_number'))
                         ->get();
                                            
        return response()->json(['status' => 'success', 'data' => ['orderDate' => $getCustomerBilling , 'orderSalesNumner' =>  $getCustomerBillingSalesNumber]], 200);
    }

   /*
     *  Sell Already Order
     *  @return json response
     * **********************************/

    public function sellAlreadyOrder(Request $request) {
      
        $check = Customer::where('customer_id', $request->input('customer_id'))->where('uid', Auth::id())->first();
    
        if (! is_null($check)) {

           $lastcs = new Lastcs();
           $lastcs->customer_id = $request->input('customer_id');
           $lastcs->customer_invoiceno = $request->input('invoice_number');
           $lastcs->uid =  Auth::id();
           $lastcs->save();


           return response()->json(['status'  => 'success'], 200);
       } else {
           return response()->json(['status'  => 'error' , 'message' => 'There is no customer'], 422);
       }



        return response()->json(['status' => 'success', 'data' => ['orderDate' => $getCustomerBilling , 'orderSalesNumner' =>  $getCustomerBillingSalesNumber]], 200);
    }
}
