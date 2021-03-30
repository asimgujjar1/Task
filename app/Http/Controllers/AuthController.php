<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Hash;
use App\User;

class AuthController extends Controller
{
     //********** login ************
     public function submit_login(Request $request)
     {
         $validator = Validator::make($request->all(),[    
             'email'=> 'required|email',
             'password' => 'required',
             ])->validate();
            
         $credentials = [
             'email' => $request->email,
             'password' => $request->password
         ];  
         if (Auth::attempt($credentials)) {
           if(Auth::user()->user_type=="super_admin")
           {
               return redirect('dashboard');
           } else {
               return redirect('user_profile');
           }
         } else {
             return redirect('/')->with('error_msg', 'Invalid email or password');
         }
     }

     public function signup(Request $request)
    {
        $validator = Validator::make($request->all(),[  
        'email'=> 'required|email',
        'password' => 'required',
        ])->validate();

        //check email exist or not
        $check_email = User::where(['email'=>$request->email])->first();

        if(empty($check_email))
        {
            $otp = rand(100000 , 999999);
            $user_data = [
                'otp'  => Hash::make($otp),
                'name' => "",
                'email'    =>  $request->email,
                'status' => 0,
                'user_type' => '',
                'password' => Hash::make($request->password)
            ];
            $user = User::create($user_data);

            $to = $request->email;
            $subject = "Email Verification Code";
            $message = 'Verification Code is :'.$otp ;
      
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: YES PHP/";
      
            mail($to, $subject, $message);
            $user_id = base64_encode($user->id);
            return redirect('email_verification/'.$user_id)->with('success_msg', 'Verification Code Send to Your Email. Please Verify');
        } else {
           return redirect('register')->with('error_msg','Email already exist');
        }

    }

    public function email_verification($user_id)
    {
        return view('verification',compact('user_id'));
    }

    public function verification(Request $request)
    {
        $validator = Validator::make($request->all(),[  
            'code'=> 'required',
            ])->validate();
    
      $user_id = base64_decode($request->user_id);
      $user = User::where(['id'=> $user_id])->first();
    //   return $user;
      if(Hash::check($request->code, $user->otp))
      {
          User::where(['id'=>$user_id])->update(['otp'=> '', 'status'=>1]);
          return redirect('/')->with('success_msg', 'Verify Successfully Now You Can Login');
      } else {
        return redirect('email_verification/'.$request->user_id)->with('success_msg', 'Invalid Verification Code');
      }
    }
}
