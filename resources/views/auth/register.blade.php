@extends('layouts.app')

@section('head')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $("#role_id").on("change", function() {
               if($('#role_id :selected').text()=="DOCTOR"){
                   $('#specField').removeClass("hidden");
                   $('#regNum').removeClass("hidden");
               }else{
                   $('#regNum').val(0);
                   $('#specField').val(0);
                   $('#regNum').addClass("hidden");
                   $('#specField').addClass("hidden");
               }
            });
        });
    </script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    {{--<form class="form-horizontal" role="form" method="POST" action="{{ route('reg') }}">--}}
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                        {{--{{method_field('PATCH')}}--}}
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="middle_name" class="col-md-4 control-label">Middle Name</label>
                            <div class="col-md-6">
                                <input id="middle_name" type="text" class="form-control" name="middle_name" value="{{ old('middle_name') }}" autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="last_name" class="col-md-4 control-label">Last Name</label>
                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" autofocus>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('role_id') ? ' has-error' : '' }}">
                            <label for="role_id" class="col-md-4 control-label">You are</label>

                            <div class="col-md-6">
                                <select id="role_id" name="role_id" class="form-control" >
                                    <option selected>--Select--</option>
                                    {{--HARD CODED VALUES CUS PATIENTS DOESNT NEED TO LOG IN FROM HERE--}}
                                    <option value=1>ADMIN</option>
                                    <option value=3>DOCTOR</option>
                                    <option value=4>LAB ASSISTANT</option>
                                    {{--@foreach($roles as $role)--}}
                                        {{--<option value={{$role['id']}}>{{$role['role']}}</option>--}}
                                    {{--@endforeach--}}
                                </select>
                            </div>
                        </div>

                        <div id="specField" class="hidden form-group">
                            <label for="field_id" class="col-md-4 control-label">Specialization </label>
                            <div class="col-md-6">
                                <select id="field_id" name="field_id" class="form-control" >
                                    <option selected>--Select--</option>
                                    @foreach($fields as $field)
                                        <option value={{$field['id']}}>{{$field['field']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="regNum" class="hidden form-group">
                            <label for="regNumber" class="col-md-4 control-label">Registration Number</label>
                            <div class="col-md-6">
                                <input id="regNumber" type="text" class="form-control" name="regNumber" >
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
