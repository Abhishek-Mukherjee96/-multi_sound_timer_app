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
                                <form action="{{route('edit_sound_action',$edit_sound->id)}}" enctype="multipart/form-data" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label><strong>Sound Name</strong> <span class="required_star">*</span></label>
                                            <input class="form-control mb-4" value="{{$edit_sound->sound_name}}" type="text" name="sound_name">
                                            @error('sound_name')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label><strong>Category</strong> <span class="required_star">*</span></label>
                                            <select class="form-control" name="cat_id">
                                                <option value="" disabled selected>Select One</option>
                                                @foreach($get_sound_cat as $list)
                                                <option value="{{$list->id}}" {{ $list->id == $edit_sound->cat_id ? 'selected' : '' }}>{{$list->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('cat_id')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label><strong>Upload Sound</strong> <span class="required_star">*</span></label>
                                            <input class="form-control mb-4" type="file" name="file">
                                            {{asset('public/admin/assets/sound-list/'.$edit_sound->file)}}
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
