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
                        <!-- <h4 class="page-title mb-0">User List</h4> -->
                        <!-- <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index-2.html#"><i class="fe fe-home mr-2 fs-14"></i>Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="index-2.html#">Dashboard</a></li>
                        </ol> -->
                    </div>
                    <div class="page-rightheader">
                        <div class="btn btn-list">
                            <a href="{{route('add_sound')}}" class="btn btn-info"><i class="fe fe-plus mr-1"></i>Add Sound</a>
                        </div>
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
                                <div class="card-title">Sound List</div>
                            </div>
                            <div class="card-body">
                                <div class="">
                                    <div class="table-responsive">
                                        <table id="example1" class="table table-bordered text-nowrap key-buttons">
                                            <thead>
                                                <tr>
                                                    <th class="wd-15p border-bottom-0">#</th>
                                                    <th class="wd-15p border-bottom-0">Sound</th>
                                                    <th class="wd-15p border-bottom-0">Status</th>
                                                    <th class="wd-20p border-bottom-0">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($get_sound))
                                                @foreach($get_sound as $list)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>
                                                        <audio controls>
                                                            <source src="{{asset($list->file)}}" type="audio/mpeg">
                                                        </audio>
                                                    </td>
                                                    <td>
                                                        @if($list->status == '1')
                                                        <a href="{{route('edit_sound_status', $list->id)}}" class="btn btn-success btn-sm">Active</a>
                                                        @else
                                                        <a href="{{route('edit_sound_status', $list->id)}}" class="btn btn-danger btn-sm">Deactive</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{route('edit_sound',$list->id)}}" onclick="return confirm('Are you sure to edit?')" class="btn btn-info"><i class="fa fa-pencil"></i></a>
                                                        <a href="{{route('delete_sound',$list->id)}}" onclick="return confirm('Are you sure to delete?')" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
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