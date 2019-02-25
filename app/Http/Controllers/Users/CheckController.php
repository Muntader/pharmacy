<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class CheckController extends Controller {

    public function checkUsername(Request $request) {
        $request->validate([
            'username' => 'required|alpha_dash|max:70'
        ]);
        
        $checkUsername = User::where('username', $request->username)->first();
        
        if($checkUsername) {
            return response()->json([
                 'status' => 'success', 
                 'data' => [
                     'username' => $checkUsername->username,
                     'name' => $checkUsername->name,
                     'image' => $checkUsername->image
                     
                     ] 
                 ]);       
        } else {
            return response()->json(['status' => 'error', 'message' => 'No username found' ]);       
        }
    }
    /* 1000 = payment step
     * 1001 = cancel alert
     * 1002 = payment reactive
     * 1003 = mail confirm
     * 1004 = blocked
     * 1005 = active
     * ***********************************/
    public function checkUsernameStatus(Request $request) {

        if ($user->hasStripeId()) {
            if ($user->period_end >= date("Y-m-d")) {
             
             $cancel_time = $user->subscription('main')->ends_at;

             if ( $cancel_time !== null) {
                    $user->period_end = $cancel_time;
                    $user->save();
                    return response()->json([
                    'name' => $user->name,
                    'image' => $user->image,
                    'email' => $user->email,
                    'language' => $user->language,
                    'status' => 1001,
                    'cancel_time' =>  $user->subscription('main')->ends_at
                    ]);

                 } else {

                // When the mail is not confirmed
                if ($user->confirmed === 0) {
                    return response()->json([
                        'name' => Auth::user()->name,
                        'image' => Auth::user()->image,
                        'email' => Auth::user()->email,
                        'status' => 1003,
                        'language' => $user->language
                        ]);
           
                // Blocked
                } elseif ($user->confirmed === 2){
                    return response()->json([
                        'name' => Auth::user()->name,
                        'image' => Auth::user()->image,
                        'email' => Auth::user()->email,
                        'status' => 1004,
                        'language' => $user->language
                        ]);
                // Check if canceld or not
                } elseif ( $user->subscription('main')->ends_at !== null) {
                    return response()->json([
                    'name' => $user->name,
                    'image' => $user->image,
                    'email' => $user->email,
                    'language' => $user->language,
                    'status' => 1001,
                    'cancel_time' =>  $user->subscription('main')->ends_at
                    ]);
               
                }else{
                    return response()->json([
                        'name' => $user->name, 
                        'image' => $user->image, 
                        'email' => $user->email, 
                        'status' => 1005,
                        'language' => $user->language
                        ]);
                 }
              }
            } else {
                // Get subscription details

                $payment_details = Auth::user()->subscription('main')->asStripeSubscription();
                $period_end_date = date('Y-m-d', strtotime(\Carbon\Carbon::createFromTimestamp($payment_details->current_period_end)->toDateTimeString()));
                $detect_status = $payment_details->status;


                //When an invoice payment on a subscription fails, the subscription becomes past_due.
                //After Stripe exhausts all payment retry attempts, the subscription remains past_due or moves to a status of either canceled or unpaid, depending upon your retry settings.
                //https://stripe.com/docs/subscriptions/lifecycle

                if ($detect_status === 'past_due' || $detect_status === 'canceled' || $detect_status === 'unpaid') {
                    $user->status = 1002;
                    $user->save();
                    if ($payment_details->plan->id === 'monthly') {
                        $plan = 1;
                    } else {
                        $plan = 2;
                    }
                    return response()->json([
                        'plan' => $plan,
                        'email' => $user->email,
                        'name' => $user->name,
                        'status' => 'payment_reactive',
                        'language' => 'en'
                       ]);
                }

                // When subscription is active

                if ($detect_status === 'active'|| $detect_status === 'trialing' && $period_end_date >= date("Y-m-d")) {
                    $user->period_end = $period_end_date;
                    $user->status = 1005;
                    $user->save();
                    return response()->json([
                        'name' => $user->name, 
                        'image' => $user->image, 
                        'email' => $user->email, 
                        'status' => 1005,
                        'language' => $user->language
                         ]);
                }
            }
        }else{
        return response()->json([
            'name' => Auth::user()->name,
            'image' => Auth::user()->image,
            'email' => Auth::user()->email,
            'status' => 1000,
            'language' => 'en'
            ]);     
        }
    }
}