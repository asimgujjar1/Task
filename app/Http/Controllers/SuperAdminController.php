<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SuperAdminController extends Controller
{
    public function dashboard()
    {
      return view('superAdmin.dashboard');
    }

    public function send_invite(Request $request)
    {
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

            return redirect('dashboard')->with('success_msg', 'Invite Send successfully');
    }
}
