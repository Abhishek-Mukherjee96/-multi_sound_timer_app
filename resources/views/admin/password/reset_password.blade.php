<!DOCTYPE html>
<html lang="en" dir="ltr">

<!-- Mirrored from laravel.spruko.com/admitro/Vertical-IconSidedar-Light/login-3 by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 21 Nov 2021 07:34:30 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <!-- Meta data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta content="Admitro - Laravel Bootstrap Admin Template" name="description">
    <meta content="Spruko Technologies Private Limited" name="author">
    <meta name="keywords" content="laravel admin dashboard, best laravel admin panel, laravel admin dashboard, php admin panel template, blade template in laravel, laravel dashboard template, laravel template bootstrap, laravel simple admin panel,laravel dashboard template,laravel bootstrap 4 template, best admin panel for laravel,laravel admin panel template, laravel admin dashboard template, laravel bootstrap admin template, laravel admin template bootstrap 4" />

    <!-- Title -->
    <title>Reset Password | Multi Sound Timer App</title>

    <!--Favicon -->
    <link rel="icon" href="{{asset('public/admin')}}/assets/images/brand/logo.png" type="image/x-icon" />

    <!--Bootstrap css -->
    <link href="{{asset('public/admin')}}/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Style css -->
    <link href="{{asset('public/admin')}}/assets/css/style.css" rel="stylesheet" />
    <link href="{{asset('public/admin')}}/assets/css/dark.css" rel="stylesheet" />
    <link href="{{asset('public/admin')}}/assets/css/skin-modes.css" rel="stylesheet" />

    <!-- Animate css -->
    <link href="{{asset('public/admin')}}/assets/css/animated.css" rel="stylesheet" />

    <!---Icons css-->
    <link href="{{asset('public/admin')}}/assets/css/icons.css" rel="stylesheet" />


    <!-- Color Skin css -->
    <link id="theme" href="{{asset('public/admin')}}/assets/colors/color1.css" rel="stylesheet" type="text/css" />
</head>

<body class="h-100vh page-style1">
    <div class="page">
        <div class="page-single">
            <div class="p-5">
                <div class="row">
                    <div class="col mx-auto">
                        <div class="row justify-content-center">
                            <div class="col-lg-9 col-xl-8">
                                <div class="card-group mb-0">
                                    <div class="card p-4">
                                        <div class="card-body">
                                            <div class="text-center title-style mb-6">
                                                <img src="{{asset('public/admin/assets/images/brand/logo.png')}}" class="img-fluid img-responsive mb-5">
                                                <h1 class="text-center">Multi Sound Timer App</h1>
                                                <hr>
                                                <h5 class="text-muted">Reset your password</h5>
                                            </div>
                                            @if(Session::has('error'))
                                            <p class="alert alert-danger">{{ Session::get('error') }}</p>
                                            @elseif(Session::has('success'))
                                            <p class="alert alert-success">{{ Session::get('success') }}</p>
                                            @endif
                                            <form method="post">
                                                @csrf
                                                <input type="text" name="id" value="{{ $user[0]['id'] }}">
                                                <div class="input-group mb-4">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fe fe-lock"></i>
                                                        </div>
                                                    </div>
                                                    <input type="password" name="password" class="form-control" placeholder="New Password">
                                                </div>
                                                @error('password')
                                                <span class="text text-red">{{$message}}</span>
                                                @enderror
                                                <div class="input-group mb-4">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fe fe-lock"></i>
                                                        </div>
                                                    </div>
                                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-info btn-block px-4">Submit</button>
                                                    </div>
                                                    <div class="col-12 text-center">
                                                        <!-- <a href="forgot-password-3.html" class="btn btn-link box-shadow-0 px-0">Forgot password?</a> -->
                                                    </div>
                                                </div>
                                            </form>
                                            {{--<hr class="divider my-6">
                                            <div class="btn-list d-flex">
                                                <a href="https://www.google.com/gmail/" class="btn btn-google btn-block"><i class="fa fa-google fa-1x mr-2"></i> Google</a>
                                            </div>--}}
                                        </div>
                                    </div>
                                    <div class="card text-white bg-primary py-5 d-md-down-none page-content mt-0">
                                        <div class="text-center justify-content-center page-single-content">
                                            <div class="box">
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                                <div></div>
                                            </div>
                                            <img src="{{asset('public/admin')}}/assets/images/png/login.png" alt="img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery js-->
    <script src="{{asset('public/admin')}}/assets/js/jquery-3.5.1.min.js"></script>

    <!-- Bootstrap4 js-->
    <script src="{{asset('public/admin')}}/assets/plugins/bootstrap/popper.min.js"></script>
    <script src="{{asset('public/admin')}}/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!--Othercharts js-->
    <script src="{{asset('public/admin')}}/assets/plugins/othercharts/jquery.sparkline.min.js"></script>

    <!-- Circle-progress js-->
    <script src="{{asset('public/admin')}}/assets/js/circle-progress.min.js"></script>

    <!-- Jquery-rating js-->
    <script src="assets/plugins/rating/jquery.rating-stars.js"></script>
    <!-- Custom js-->
    <script src="{{asset('public/admin')}}/assets/js/custom.js"></script>
</body>

<!-- Mirrored from laravel.spruko.com/admitro/Vertical-IconSidedar-Light/login-3 by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 21 Nov 2021 07:34:30 GMT -->

</html>