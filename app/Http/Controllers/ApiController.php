<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use Hash;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(),[    
            'email'=> 'required|email',
            'password' => 'required',
            ])->validate();

        $credentials = [
               'email' => $request->email,
               'password' => $request->password
        ];

        if(Auth::attempt($credentials)) {
           if(Auth::user()->status==1) {
            $user = Auth::user();   
            $token = $user->createToken('Personal Access Token')->accessToken;
            return response()->json(['status'=>200, 'message'=> 'login successfull', 'data'=> $user, 'token'=> $token]);
           } else {
            return response()->json(['status'=>201, 'message' => 'You need to verify your email address for login' ]);
           }
        } else {
            return response()->json(['status'=>302, 'message'=> 'Invalid Email or password']);
        }

    }

    public function send_invite(Request $request)
    {
        if(Auth::user()->user_type=="super_admin"){
        $validator = Validator::make($request->all(),[    
            'email'=> 'required|email',
            ])->validate();

            $to = $request->email;
            $subject = "Click To Sign up";
            $message = url('register');
      
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: YES PHP/";
      
            mail($to, $subject, $message);

            return response()->json(['status'=> 200, 'message'=> 'Invitation send successfully']);
        } else {
            return response()->json(['status'=>201, 'message'=> 'You can not perform this action']);
        }
    }


    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(),[    
            'email'=> 'required|email',
            'password' => 'required',
            ])->validate();

        $user = User::where(['email'=> $request->email])->first();
        if(empty($user))
        {
          $otp = rand(100000 , 999999);
          $sign_up = [
            'otp'  => Hash::make($otp),
            'name' => "",
            'email'    =>  $request->email,
            'status' => 0,
            'user_type' => '',
            'password' => Hash::make($request->password)
          ];
          $user = User::create($sign_up);

          $to = $request->email;
          $subject = "Email Verification Code";
          $message = 'Verification Code is :'.$otp ;
    
          $headers = "MIME-Version: 1.0" . "\r\n";
          $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
          $headers .= "From: YES PHP/";
    
          mail($to, $subject, $message);
          return response()->json(['status'=> 200, 'message'=> 'verification code send successfully', 'user_id'=> $user->id]);
        } else {
            return response()->json(['message'=> 201, 'message'=> 'Email already exist']);
        }
    }

    public function verify_otp(Request $request)
    { 
        $validator = Validator::make($request->all(),[    
            'verification_code'=> 'required',
            ])->validate();

        $user = User::where(['id'=> $request->user_id])->first();
        
        if(Hash::check($request->verification_code, $user->otp))
        {
            User::where(['id' => $request->user_id])->update(['status'=>1 , 'otp' => '']);
            return response()->json(['status'=> 200, 'message'=> 'Verify Successfull You can login now']);
        } else {
            return response()->json(['status'=> 201, 'message'=> 'verification code is not correct']);
        }
    }

    public function view_profile()
    {
        if(Auth::user()->user_type!="super_admin")
        {
         $profile = User::where(['id'=>Auth::user()->id])->select('name', 'email', 'phone_number', 'address', 'profile_image', 'user_type')->first();
         return response()->json(['status'=>200, 'messgae'=> 'profile available', 'data'=> $profile]);
        } else {
            return response()->json(['status'=>201, 'message'=> 'You can not perform this action']);
        }
    }

    public function update_profile(Request $request)
    {
        if(Auth::user()->user_type!="super_admin")
        {
             $validator = Validator::make($request->all(),[    
                'email'=> 'required|email',
                ])->validate();
    
           $check_email = User::where(['email'=>$request->email])->where('id','!=',Auth::user()->id)->first();
           if(!empty($check_email))
           {
               return response()->json(['status'=>201, 'message'=> 'Email already exist']);
           }
           $image = "";
           if($request->has('profile_img'))
           {
               $file = $request->file('profile_img');
               $file_name = time(). '.profile_' .$file->getClientOriginalName();
               $location = app()->basePath('public/assets/imgs');
               $file->move($location, $file_name);
               $image = 'public/assets/profile_image/'. $file_name;
           }
           $profile_data = [
                     'name' => $request->name,
                     'email' => $request->email,
                     'phone_number' => $request->phone_number,
                     'user_type' => $request->user_type,
                     'address' => $request->address,
                     'profile_image' => $image
           ];

           User::where(['id'=>Auth::user()->id])->update($profile_data);
           return response()->json(['status'=> 200, 'message'=> 'Profile Updated Successfully']);
        } else {
            return response()->json(['status'=>201, 'message'=> 'You can not perform this action']);
        }
    }
}
