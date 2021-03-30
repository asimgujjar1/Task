<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class UserController extends Controller
{
    public function user_profile()
    {
        if(Auth::user()->status==0)
        {
            return redirect('email_verification/'.base64_encode(Auth::user()->id))->with('success_msg', 'First You Need To Verify Your Email');
        } else {
          return view('profile');
        }
    }
}
