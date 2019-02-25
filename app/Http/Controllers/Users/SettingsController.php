<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use App\User;
use App\Setting;

use Carbon\Carbon;

class SettingsController extends Controller
{
    

    public function getUserDetails() {
       
        if(Auth::check()){
            $getUserDetails = User::select('name','username','email','image')->where('id', Auth::id())->first();
            
            return response()->json(['status' => 'success', 'data' => $getUserDetails], 201);
       
        } else {
       
            return response()->json(['status' => 'error', 'message'  => 'There is no id'], 422);
       
        }
    }


    public function changeImage(Request $request) {
       $request->validate([
            'image' => 'required|mimes:jpeg,jpg,png|max:1000',
        ]);
        // upload to storage
        $name  = str_random().'.jpg';
        $upload =  $request->file('image')->move(public_path("/upload/users_image"), $name);


        if ($upload) {
            $user = Auth::user();
            $user->image = $name;
            $user->save();
            return response()->json(['status' => 'success', 'image' => $user->image]);
        }

    }


    public function updateDetails(Request $request) {
       $request->validate([
            'name' => 'required|max:40|regex:/^[a-z0-9 .\-]+$/i',
            'username' => 'required|max:40|alpha_dash',
            'email' => 'required|max:40|email',

        ]);

        if(Auth::check()) {
            $user = Auth::user();
            if($request->has('name')){
                $user->name = $request->input('name');
            }
            if($request->has('username')){
                $user->username = $request->input('username');
            }
            if ($request->has('email')) {
                $user->email = $request->input('email');
            }

            $user->save();
            return response()->json(['status' => 'success', 'name' => $user->name, 'username' => $user->username, 'email' => $request->email]);
        }
    }



   
    public function updatePassword(Request $request) {
       $request->validate([
            'password' => 'required|
               min:6|
               confirmed',

        ]);

        if(Auth::check()) {
            $user = Auth::user();
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return response()->json(['status' => 'success', 'name' => $user->name, 'username' => $user->username, 'email' => $request->email]);
        }
    } 


    /* 
     * Get payment info
     * @return json response  
     *************************************/
    public function getPaymentInfo(){
        $user = Auth::user();
        $subscription =  $user->subscription('main')->asStripeSubscription();        
        return response()->json(['status' => 'success' , 'data' => ['subscription_plan' => $subscription->plan->id,'subscription_status' => $subscription->status, 'cancel' => $subscription->cancel_at_period_end , 'card_number' => $user->card_last_four,'card_brand' => $user->card_brand] ]);
    }


    /* 
     * Cancel Membership
     * @return json response  
     *************************************/
    public function cancelMembership(){
        $user = Auth::user();
        $user->subscription('main')->cancel();
        $subscription =  $user->subscription('main')->asStripeSubscription();
        return response()->json(['status' => 'success' , 'data' => [ 'subscription_status' => $subscription->status, 'cancel' => $subscription->cancel_at_period_end]]);
    }

    /* 
     * Resume Membership
     * @return json response  
     *************************************/
    public function resumeMembership(){
        $user = Auth::user();
        $user->subscription('main')->resume();
        $subscription =  $user->subscription('main')->asStripeSubscription();
        return response()->json(['status' => 'success' , 'data' => [ 'subscription_status' => $subscription->status, 'cancel' => $subscription->cancel_at_period_end]]);
    }


    /* Update Card payment 
     * @return viod
     *  
     *************************************/
    public function updatePaymnetCard(Request $request){
        $user = Auth::user();
        $user->updateCard($request->token);
        return response()->json(['status' => 'success']);        
    }
  


    /* Get billing invoice 
     * @return json response 
     *  
     *************************************/
    public function getBillingDetails(){
        $user =  Auth::user();   
        if($user->hasStripeId()) {
            $invoices = $user->invoices()->map(function($invoice) {
                return [
                    'start'  =>  date('l jS F Y', strtotime(Carbon::createFromTimestamp($invoice->lines->data[0]->period->start)->toDateTimeString())),
                    'end'    =>  date('l jS F Y', strtotime(Carbon::createFromTimestamp($invoice->lines->data[0]->period->end)->toDateTimeString())),
                    'total'  => $invoice->total
                  ];
            });
        } else {
            $invoices = [];
        }
        $subscription =  Auth::user()->subscription('main')->asStripeSubscription();
        
        return response()->json([ 'status' => 'success', 'data' => ['invoices' => $invoices,'name' => $subscription->plan->name,'amount' => $subscription->plan->amount ] ]);
    }



    /* Get billing invoice 
     * @return json response 
     *  
     *************************************/
    public function getSettingInvocie(){

        $setting = Setting::selectRaw('ph_name AS name,
                                       ph_address AS address,
                                       ph_telephone AS telephone,
                                       ph_email AS email,
                                       ph_fax AS fax,
                                       ph_export_invoice_type AS export_type,
                                       ph_currency AS currency,
                                       ph_barcode_type AS barcode,
                                       ph_tax_rate AS taxt_rate'
                                       )->where('uid', Auth::id())->first();

        if (! is_null($setting)) {
            return response()->json([ 'status' => 'success', 'data' => $setting ]);
        }
    }


    public function updateSettingInvocie(Request $request) {


            $request->validate([
                'item.name' => 'max:40|regex:/^[a-z0-9 .\-]+$/i ',
                'item.address' => 'max:20',
                'item.telephone' => 'max:1000000000000|numeric',
                'item.email' => 'max:40|email',
                'item.fax' => 'max:1000000000000|numeric',
                'item.export_type' => 'numeric',
                'item.currency' => 'max:4|alpha_dash',
                'item.barcode' => 'max:10|alpha_dash',
                'item.taxt_rate' => 'max:2|numeric',
            ]);

            $setting = new Setting();
            $setting->ph_name = $request->input('item.name');
            $setting->ph_address = $request->input('item.address');
            $setting->ph_telephone = $request->input('item.telephone');
            $setting->ph_email = $request->input('item.email');
            $setting->ph_fax = $request->input('item.fax');
            $setting->ph_export_invoice_type = $request->input('item.export_type');
            $setting->ph_currency = $request->input('item.currency');
            $setting->ph_barcode_type = $request->input('item.barcode');
            $setting->ph_tax_rate = $request->input('item.taxt_rate'); 
            $setting->uid = Auth::id();

            $setting->save();

            return response()->json([ 'status' => 'success' ]);

    }


}
