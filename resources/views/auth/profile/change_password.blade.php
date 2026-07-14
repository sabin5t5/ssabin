@extends('admin.includes.layout')

@section('content')
@include('admin.includes.breadcrumb',[
    'base_route' => $base_route,
    'page'=> "Change Password"
    ])

<div id="content" class="padding-20">
        <div class="col-md-12">
            @include('admin.includes.flash-notification')
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="{{ route('submit-password') }}" method="POST" class="row" name="update_password">
                        {{ csrf_field() }}
                        <div class="col-md-8 col-md-offset-2">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="current_password" class="grey-text">Current Password: <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="current_password" required="required">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="password" class="grey-text">New Password: <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" required="required">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="grey-text">Retype New Password: <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password_confirmation" required="required">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-sm-3 col-xs-6">
                                            <button type="submit" name="submit" class="btn btn-primary btn-block formSubmitBtn">Update</button>
                                        </div>
                                        <div class="col-sm-3 col-xs-6">
                                            <a href="#" name="cancel" class="btn btn-default btn-block singleClick">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
