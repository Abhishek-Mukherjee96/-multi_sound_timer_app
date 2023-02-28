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
                        <h4 class="page-title mb-0">Hi! {{$greet}} {{Auth::user()->name}}</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index-2.html#"><i class="fe fe-home mr-2 fs-14"></i>Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="index-2.html#">Dashboard</a></li>
                        </ol>
                    </div>
                </div>
                <!--End Page header-->

                <!-- Row-1 -->
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                        <div class="card overflow-hidden dash1-card border-0">
                            <div class="card-body">
                                <p class=" mb-1 ">Total Projects</p>
                                <h2 class="mb-1 number-font">1</h2>
                                <span class="ratio-text text-muted"><i class="fa fa-file fa-3x text-warning" style="margin-top: -20px;"></i></span>
                            </div>
                            <div id="spark1"></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                        <div class="card overflow-hidden dash1-card border-0">
                            <div class="card-body">
                                <p class=" mb-1 ">Total User</p>
                                <h2 class="mb-1 number-font">1</h2>
                                <span class="ratio-text text-muted"><i class="fa fa-users fa-3x text-info" style="margin-top: -20px;"></i></span>
                            </div>
                            <div id="spark2"></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                        <div class="card overflow-hidden dash1-card border-0">
                            <div class="card-body">
                                <p class=" mb-1 ">Pending Tasks</p>
                                <h2 class="mb-1 number-font">1</h2>
                                <span class="ratio-text text-muted"><i class="fa fa-tasks fa-3x text-danger" style="margin-top: -20px;"></i></span>
                            </div>
                            <div id="spark3"></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                        <div class="card overflow-hidden dash1-card border-0">
                            <div class="card-body">
                                <p class=" mb-1">Completed Task</p>
                                <h2 class="mb-1 number-font">1</h2>
                                <span class="ratio-text text-muted"><i class="fa fa-tasks fa-3x text-success" style="margin-top: -20px;"></i></span>

                            </div>
                            <div id="spark4"></div>
                        </div>
                    </div>
                </div>
                <!-- End Row-1 -->
            </div>
        </div>
        <!-- End app-content-->
    </div>
    @endsection