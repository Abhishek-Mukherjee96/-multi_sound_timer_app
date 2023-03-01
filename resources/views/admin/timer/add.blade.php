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
                                <h3 class="card-title">Add Timer</h3>
                            </div>
                            <div class="card-body pb-2">
                                <form action="{{route('add_timer_action')}}" enctype="multipart/form-data" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label><strong>Timer Title</strong> <span class="required_star">*</span></label>
                                            <input class="form-control mb-4" value="{{old('timer_title')}}" placeholder="Timer Title" type="text" name="timer_title">
                                            @error('timer_title')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label><strong>Timer Subhead <span class="required_star">*</span></strong></label>
                                            <input class="form-control mb-4" value="{{old('timer_subhead')}}" placeholder="Timer Subhead" type="text" name="timer_subhead">
                                            @error('timer_subhead')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label><strong>Start Sound <span class="required_star">*</span></strong></label>
                                            <input class="form-control mb-4" value="{{old('start_sound')}}" placeholder="Start Sound" type="text" name="start_sound">
                                            @error('start_sound')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="example1" class="table table-bordered text-nowrap key-buttons">
                                                <thead>
                                                    <tr>
                                                        <th class="wd-15p border-bottom-0">Segment Name</th>
                                                        <th class="wd-15p border-bottom-0">Duration</th>
                                                        <th class="wd-15p border-bottom-0">End Sound</th>
                                                        <th class="wd-20p border-bottom-0">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="addmore[0][segment_name]" class="form-control" placeholder="Segment Name">
                                                        </td>
                                                        <td>
                                                            <input type="time" name="addmore[0][duration]" class="form-control" placeholder="Duration">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="addmore[0][end_sound]" class="form-control">
                                                        </td>
                                                        <td>
                                                            <button type="button" name="add" id="add" class="btn btn-success"><i class="fa fa-plus"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="submit" class="btn btn-info" value="Save">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Row-1 -->
                </div>
            </div>
            <!-- End app-content-->
        </div>
        @endsection
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                var i = 0;
                $("#add").click(function() {
                    //console.log("ok");
                    ++i;
                    $("#example1").append('<tr><td><input type="text" name="addmore[' + i + '][segment_name]" placeholder="Segment Name" class="form-control" /></td><td><input type="time" name="addmore[' + i + '][duration]" placeholder="Duration" class="form-control" /></td><td><input type="text" name="addmore[' + i + '][end_sound]" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr"><i class="fa fa-trash"></i></button></td></tr>');
                });

                $(document).on('click', '.remove-tr', function() {
                    $(this).parents('tr').remove();
                });
            });
        </script>