@extends('admin.layouts.main')
@section('content')
<!-- Page -->
<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

$profile_details = User::where('id', Auth::user()->id)->select('users.*')->first();
?>
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
                        <h4 class="page-title mb-0">User Profile</h4>
                        <!-- <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index-2.html#"><i class="fe fe-home mr-2 fs-14"></i>Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="index-2.html#">Dashboard</a></li>
                        </ol> -->
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
                    <div class="col-xl-3 col-lg-3 col-md-12">
                        <div class="card box-widget widget-user">
                            @if($profile_details->image)
                            <div class="widget-user-image mx-auto mt-5"><img alt="User Avatar" class="rounded-circle" src="{{asset('public/admin/assets/user-profile/'.$profile_details->image)}}"></div>
                            @else
                            <div class="widget-user-image mx-auto mt-5"><img alt="User Avatar" class="rounded-circle" src="{{asset('public/admin/assets/images/user-profile/avatar.png')}}"></div>
                            @endif
                            <div class="card-body text-center">
                                <div class="pro-user">
                                    <h4 class="pro-user-username text-dark mb-1 font-weight-bold">{{$profile_details->name}}</h4>
                                    <!-- <h6 class="pro-user-desc text-muted">Web Designer</h6> -->
                                    <div class="wideget-user-rating">
                                        <a href="#"><i class="fa fa-star text-warning"></i></a>
                                        <a href="#"><i class="fa fa-star text-warning"></i></a>
                                        <a href="#"><i class="fa fa-star text-warning"></i></a>
                                        <a href="#"><i class="fa fa-star text-warning"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <div class="card-title">Change Password</div>
                            </div>
                            <div class="card-body">
                                <form action="{{route('update_password')}}" enctype="multipart/form-data" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input class="form-control mb-4" placeholder="Old Password" type="password" name="old_password">
                                            @error('old_password')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <input class="form-control mb-4" placeholder="New Password" type="password" name="password">
                                            @error('password')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <input class="form-control mb-4" placeholder="Confirm Password" type="password" name="password_confirmation">
                                        </div>
                                        <div class="col-md-12">
                                            <input type="submit" class="btn btn-info" value="Save">
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <div class="card-title">Personal Details</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label><strong>Name:</strong> {{$profile_details->name}}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label><strong>Email:</strong> {{$profile_details->email}}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label><strong>Zip:</strong> {{$profile_details->zip_or_postal_code}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Row-1 -->
                </div>
            </div>
            <!-- End app-content-->
        </div>
        @endsection