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
                                <h3 class="card-title">Add User</h3>
                            </div>
                            <div class="card-body pb-2">
                                <form action="{{route('edit_user_action',$edit_user->id)}}" enctype="multipart/form-data" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label><strong>Name</strong> <span class="required_star">*</span></label>
                                            <input class="form-control mb-4" value="{{$edit_user->name}}" readonly placeholder="Name" type="text" name="name">
                                            @error('name')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label><strong>Email <span class="required_star">*</span></strong></label>
                                            <input class="form-control mb-4" value="{{$edit_user->email}}" readonly placeholder="Email" type="text" name="email">
                                            @error('email')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label><strong>Account Type <span class="required_star">*</span></strong></label>
                                            <select class="form-control" id="account_type" name="account_type">
                                                <option value="" selected disabled>Select One</option>
                                                @if(isset($account_type))
                                                @foreach($account_type as $value)
                                                <option value="{{$value->id}}" {{ $value->id == $edit_user->account_type ? 'selected' : '' }}>{{$value->subscription_name}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('account_type')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label><strong>How did you find us? <span class="required_star">*</span></strong></label>
                                            <select class="form-control" name="find_us">
                                                <option value="" selected disabled>Select One</option>
                                                @if(isset($find_us))
                                                @foreach($find_us as $value)
                                                <option value="{{$value->id}}" {{ $value->id == $edit_user->find_us ? 'selected' : '' }}>{{$value->find_us}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('find_us')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label><strong>Upload Avatar (Optional)</strong></label>
                                            <input class="form-control mb-4" type="file" name="image">
                                        </div>
                                    </div>
                                    @if($edit_user->account_type == 2)
                                    <div class="row" style="display: none;" id="monthly_card_details">
                                        <div class="col-md-12"><strong>Subscription Fee: $2.99 / Month</strong>
                                            <p>With the Monthly Subscription, you can create up to 30 Timers & 10 collection folders. $2.99 fee will be automatically charged monthly. At any time you can cancel or change your Account Type in your Settings.</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label><strong>Card Name</strong></label>
                                            <input class="form-control mb-4" value="{{$edit_user->card_name}}" placeholder="Card Name" type="text" name="card_name">
                                            @error('card_name')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label><strong>Card Number</strong></label>
                                            <input class="form-control mb-4" value="{{decrypt($edit_user->card_number)}}" placeholder="Card Number" type="text" name="card_number">
                                            @error('card_number')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label><strong>Expiry Date</strong></label>
                                            <input class="form-control mb-4" value="{{decrypt($edit_user->exp_date)}}" placeholder="Expiry Date" type="month" name="exp_date">
                                            @error('exp_date')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label><strong>Security Code</strong></label>
                                            <input class="form-control mb-4" value="{{decrypt($edit_user->security_code)}}" placeholder="Security Code" type="text" name="security_code">
                                            @error('security_code')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label><strong>Zip or Postal Code</strong> <span class="required_star">*</span></label>
                                            <input class="form-control mb-4" value="{{$edit_user->zip_or_postal_code}}" placeholder="Zip or Postal Code" type="text" name="zip_or_postal_code">
                                            @error('zip_or_postal_code')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    @elseif($edit_user->account_type == 3)
                                    <div class="row" style="display: none;" id="yearly_card_details">
                                        <div class="col-md-12"><strong>Subscription Fee: $20.00 / Year</strong>
                                            <p>With the Yearly Subscription, you can create up to 30 Timers & 10 collection folders. $20.00 fee will be automatically charged monthly. At any time you can cancel or change your Account Type in your Settings.</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label><strong>Card Name</strong></label>
                                            <input class="form-control mb-4" value="{{$edit_user->card_name}}" placeholder="Card Name" type="text" name="card_name">
                                            @error('card_name')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label><strong>Card Number</strong></label>
                                            <input class="form-control mb-4" value="{{decrypt($edit_user->card_number)}}" placeholder="Card Number" type="text" name="card_number">
                                            @error('card_number')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label><strong>Expiry Date</strong></label>
                                            <input class="form-control mb-4" value="{{decrypt($edit_user->exp_date)}}" placeholder="Expiry Date" type="month" name="exp_date">
                                            @error('exp_date')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label><strong>Security Code</strong></label>
                                            <input class="form-control mb-4" value="{{decrypt($edit_user->security_code)}}" placeholder="Security Code" type="text" name="security_code">
                                            @error('security_code')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label><strong>Zip or Postal Code</strong> <span class="required_star">*</span></label>
                                            <input class="form-control mb-4" value="{{$edit_user->zip_or_postal_code}}" placeholder="Zip or Postal Code" type="text" name="zip_or_postal_code">
                                            @error('zip_or_postal_code')
                                            <span class="text text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            $(document).ready(function(e) {
                //alert("ok");
                $("#account_type").change(function(e) {
                    //e.preventDefault();
                    var data = $(this).val();
                    if (data == 2) {
                        //alert("ok");
                        $("#monthly_card_details").show();
                    } else {
                        $("#monthly_card_details").hide();
                    }
                });

            });

            $(document).ready(function(e) {
                //alert("ok");
                $("#account_type").change(function(e) {
                    //e.preventDefault();
                    var data = $(this).val();
                    if (data == 3) {
                        //alert("ok");
                        $("#yearly_card_details").show();
                    } else {
                        $("#yearly_card_details").hide();
                    }
                });

            });
        </script>