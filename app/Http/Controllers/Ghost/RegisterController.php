<?php

namespace App\Http\Controllers\ghost;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgetAccount;
use Carbon\Carbon;
use App\User;
use Auth;
class RegisterController extends Controller
{
 // Users Status
    // 1000 = payment_step
    // 1001 = payment_cancel 
    // 1002 = payment_reactive 
    // 1003 = confirm_mail 
    // 1004 = active


    /* Register 
     * @return json response 
     *  
     *************************************/
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:30',
            'username' => 'required|alpha_dash|max:70',
            'email' => 'required|email|max:70',
            'password' => 'required|confirmed|min:8',
        ]);
        $check = User::where('email', $request->email)->where('username', $request->username)->first();
        if (! is_null($check)) {
            return response()->json(['status' => 'error', 'message' => 'Email has already been taken.']);
        }
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->confirmation_code = str_random(30);
        $user->status = '1000';
        $user->save();


        // Setting
        $setting = new User();
        $setting->ph_name      = $user->name;
        $setting->ph_address   =  'Tx';
        $setting->ph_telephone =  '00000000';
        $setting->ph_email     =  $user->email;
        $setting->ph_language     = 'en';
        $setting->ph_invoice      = 'default';
        $setting->ph_currency     = 'USD';
        $setting->ph_barcode_type = 'CODE128'; 
        $setting->uid             = $user->id;

        $setting->save();
        
        return response()->json(['status' => 'success', 'username' => $user->name]);

    }

    /* Send mail confirm
     * @return json response 
     *  
     *************************************/
    public function sendActivityMail()
    {
        $user = Auth::user();
        $user->confirmation_code = str_random(30);
        $user->save();
        Mail::to(Auth::user())
            ->send(new ActivityAccount());
        return response(['status' => 'success']);
    }


    /* If token of confirm mail is match
     * @return json response 
     *  
     *************************************/
    public function codeConfirmed($token)
    {
        $user = User::where('confirmation_code', $token)->first();

        if (!$user) {
            return redirect()->route('home');
        }

        $user->confirmation_code = null;
        $user->confirmed = 1;
        $user->save();
        return redirect('/');
        
    }


    /* Send mail to restore password 
     * @return json response 
     *  
     *************************************/
    public function sendForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:100',
        ]);
        $user = User::where('email', $request->email)->first();
        if (is_null($user)) {
            return response(['status' => 'error','message' => 'E-mail is incorrect']);
        }
        $user->forget_params_hash = str_random(50);
        $user->save();
        Mail::to($user)->send(new ForgetAccount($user));
        
        return response(['status' => 'success', 'message' => 'Successful send, please check your mail']);
    }

    
    /* Check token of forget password 
     * @return json response 
     *  
     *************************************/ 
    public function forgetCheckHash(Request $request)
    {
        $request->validate([
            'hash' => 'required|alpha_dash|max:50',
        ]);

        $hash = User::where('forget_params_hash', $request->hash)->first();

        if (!is_null ($hash)) {
            return response()->json(['status' => 'success', 'data' => ['email' => $hash->email,'image' => $hash->image]]);
        }else{
            return abort(401);
        }

    }


    //
    // Recover password
    //
    /* Confirmed Code
     * @return json response 
     *  
     *************************************/ 
    public function recoverPassword(Request $request)
    {
          $request->validate([
            'hash' => 'required|alpha_dash',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('forget_params_hash', $request->hash)->where('email',$request->email)->first();
        if (! is_null($user)) {
            $user->forget_params_hash = null;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['status' => 'success']);
        }
    }
    
    /* Payment
     * @return json response 
     *  
     *************************************/ 
    public function payment(Request $request){
        $request->validate([
            'plan_id' => 'numeric|min:1|max:4'
        ]);
        if(Auth::check()){
        $user = Auth::user();     
        // Monthly and one weak free;
        // IF you need to add new plan just deplicate esleif and change the request plan id and plan id of stripe.
        // $user->newSubscription('main', 'STRIPE ID')->create($request->token);
        
        if($request->plan_id === 1){
          $user->newSubscription('main', 'standard')->create($request->token);
        // Yearly  
        }elseif($request->plan_id === 2){
          $user->newSubscription('main', 'premium')->create($request->token);  
        }else{
            return abort(401);
        }
        $details =  Auth::user()->subscription('main')->asStripeSubscription();
        $date = date('l jS F Y', strtotime(Carbon::createFromTimestamp($details->trial_end)->toDateTimeString()));   
        $user->period_end = date('Y-m-d', strtotime(\Carbon\Carbon::createFromTimestamp($details->current_period_end)->toDateTimeString()));
        $user->status = 1005;
        $user->save();
        return response()->json(['status' => 'success',
        'data' => [
            'name' => $user->name, 
            'email' => $user->email,
            'trail_end' => $date, 
            'card_number' => $user->card_last_four,
            'card_brand' => $user->card_brand]
        ]);
      }
    }
}
