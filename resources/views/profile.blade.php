<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- Bootstrap Css -->
    <link href="{{asset('/public/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('/public/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('/public/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />

</head>

<body class="bg-grey bg-pattern">
        
        <div class="account-pages my-2 pt-sm-5">
            <div class="container">
                <!-- end row -->

            <div class="row justify-content-center">
                <div class="col-xl-5 col-sm-8">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="p-2">
                            @if(session('success_msg'))
                            <div id="alert_message" class="alert alert-success alert-dismissible">
                                <button class="close" type="button" data-dismiss="alert">×</button>
                                <strong>{{session('success_msg')}}</strong>
                            </div>
                            @endif
                            @if(session('error_msg'))
                            <div id="alert_message" class="alert alert-danger alert-dismissible">
                                <button class="close" type="button" data-dismiss="alert">×</button>
                                <strong>{{session('error_msg')}}</strong>
                            </div>
                            @endif
                                <h5 class="mb-5 text-center">Profile</h5>
                                <form class="form-horizontal" method="POST" action="{{url('submit_login')}}">
                                 @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group form-group-custom mb-4">
                                                <input type="text" value="{{Auth::user()->email}}" name="email" class="form-control" id="username" required>
                                                <label for="username">Email</label>
                                                @if($errors->first('email'))
                                                <div id="alert_message" class="alert alert-danger alert-dismissible">
                                                    <button class="close" type="button" data-dismiss="alert">×</button>
                                                    <strong>{{$errors->first('email')}}</strong>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="form-group form-group-custom mb-4">
                                                <input type="text" value="{{Auth::user()->name}}" name="name" class="form-control" id="username" required>
                                                <label for="username">Name</label>
                                                @if($errors->first('name'))
                                                <div id="alert_message" class="alert alert-danger alert-dismissible">
                                                    <button class="close" type="button" data-dismiss="alert">×</button>
                                                    <strong>{{$errors->first('name')}}</strong>
                                                </div>
                                                @endif
                                            </div>

                                            <div class="form-group mb-4">
                                                <!-- <input type="password" name="password" class="form-control" id="userpassword" required> -->
                                                <label >Select User Role</label>
                                                <select class="form-control" name="user_type">
                                                 <option value="user">User</option>
                                                 <option value="admin">Admin</option>
                                                </select>
                                                @if($errors->first('password'))
                                                <div id="alert_message" class="alert alert-danger alert-dismissible">
                                                    <button class="close" type="button" data-dismiss="alert">×</button>
                                                    <strong>{{$errors->first('password')}}</strong>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="mt-4">
                                                <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Log In</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
    <!-- end Account pages -->

    <!-- JAVASCRIPT -->
</body>

</html>