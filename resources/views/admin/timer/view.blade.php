@extends('admin.layouts.main')
@section('content')
<!-- Page -->
<div class="page">
    <div class="page-main">
        @extends('admin.layouts.sidebar')
        <!-- App-Content -->
        <div class="app-content main-content">
            <div class="side-app">
                @extends('admin.layouts.nav')
                <!--Page header-->
                <div class="page-header">
                    <div class="page-leftheader">
                        <!-- <h4 class="page-title mb-0">Add User</h4> -->
                    </div>
                    <div class="page-rightheader">

                    </div>
                </div>
                <!--End Page header-->
                @if(Session::has('success'))
                <p class="alert alert-success">{{ Session::get('success') }}</p>
                @elseif(Session::has('error'))
                <p class="alert alert-danger">{{ Session::get('error') }}</p>
                @endif
                <!-- Row-1 -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title">View User Details</h3>
                            </div>
                            <div class="card-body pb-2">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label><strong>Name:</strong> {{$view_user_details->name}}</label>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label><strong>Email:</strong> {{$view_user_details->email}}</label>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label><strong>Account Type:</strong>
                                            @if($view_user_details->account_type == 'free_account_3_timers')
                                            <span>Free Account - 3 Timers</span>
                                            @elseif($view_user_details->account_type == 'subscription_monthly')
                                            <span>Subscription - Monthly</span>
                                            @else
                                            <span>Subscription - Yearly</span>
                                            @endif
                                        </label>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label><strong>How did you find us?</strong> {{$view_user_details->find_us}}</label>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label><strong>Upload Avatar (Optional)</strong>
                                            @if($view_user_details->image)
                                            <img src="{{asset('public/admin/assets/user-profile/'.$view_user_details->image)}}" width="50px">
                                            @else
                                            <div class="widget-user-image mx-auto mt-5"><img alt="User Avatar" class="rounded-circle" width="50px" src="{{asset('public/admin/assets/images/user-profile/avatar.png')}}"></div>
                                            @endif
                                        </label>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label><strong>Zip or Postal Code:</strong> {{$view_user_details->zip_or_postal_code}}</label>
                                    </div>
                                </div>
                                @if($view_user_details->account_type == 'subscription_monthly')
                                <div class="row">
                                    <div class="col-md-12"><strong>Subscription Fee: $2.99 / Month</strong>
                                        <p>With the Monthly Subscription, you can create up to 30 Timers & 10 collection folders. $2.99 fee will be automatically charged monthly. At any time you can cancel or change your Account Type in your Settings.</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label><strong>Card Name:</strong> {{$view_user_details->card_name}}</label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label><strong>Card Number:</strong> {{decrypt($view_user_details->card_number)}}</label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label><strong>Expiry Date:</strong> {{decrypt($view_user_details->exp_date)}}</label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label><strong>Security Code:</strong> {{decrypt($view_user_details->security_code)}}</label>
                                    </div>
                                </div>
                                @endif
                                @if($view_user_details->account_type == 'subscription_yearly')
                                <div class="row">
                                    <div class="col-md-12"><strong>Subscription Fee: $20.00 / Year</strong>
                                        <p>With the Yearly Subscription, you can create up to 30 Timers & 10 collection folders. $20.00 fee will be automatically charged monthly. At any time you can cancel or change your Account Type in your Settings.</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label><strong>Card Name:</strong> {{$view_user_details->zip_or_postal_code}}</label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label><strong>Card Number:</strong> {{decrypt($view_user_details->card_number)}}</label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label><strong>Expiry Date:</strong> {{decrypt($view_user_details->exp_date)}}</label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label><strong>Security Code:</strong> {{decrypt($view_user_details->security_code)}}</label>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    <!-- End Row-1 -->
                </div>
            </div>
            <!-- End app-content-->
        </div>
        @endsection