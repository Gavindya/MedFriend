@extends('layouts.app')
@section('head')
    <script src="{{ asset('js/jquery-3.1.1.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#askPermission').click(function(){
                $('#permissionSec').removeClass('hidden');
            });
{{--             $('#{{$specialization_field_id}}').addClass('btn-danger');--}}
            @foreach($allowedFieldsList as $aF)
                $('#{{$aF}}').addClass('btn-danger');
            @endforeach
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Patient Details
                    </div>

                    <div class="panel-body panel-success">

                        <p>Name : {{$patient->title}}.{{$patient->user->name}} {{$patient->user->middele_name}} {{$patient->user->last_name}}</p>
                        <p>Gender : {{$patient->gender}}</p>
                        <p>Birthday : {{$patient->birthday}}</p>


                        <form method="post" action="{{route('viewReport')}}" enctype="multipart/form-data" >
                            {{method_field('PATCH')}}
                            {{csrf_field()}}
                        <div class="row">
                            <div class="hidden form-group">
                                <input type="text" class="form-control" name="doctor_id" id="doctor_id" value="{{$doctor_id}}">
                            </div>
                            <div class="hidden form-group">
                                <input type="text" class="form-control" name="user_id" id="user_id" value="{{$patient->user->id}}">
                            </div>
                            <div class="hidden form-group">
                                <input type="text" class="form-control" name="specialization_field_id"
                                       id="specialization_field_id" value="{{$specialization_field_id}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn-primary form-control" type="submit">View Reports</button>
                        </div>
                        </form>
                        <div class="form-group ">
                            <button id="askPermission" class="button btn-primary form-control">View More Reports</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="msgArea">
            @if(Session::has('requested'))
                <p class="alert {{ Session::get('alertType') }}">{{ Session::get('requested') }}
                    <button id="m" class="glyphicon glyphicon-remove pull-right"></button></p>
            @endif
        </div>
        <div id="permissionSec" class="hidden">
            @include('doctor.requestPermission')
        </div>
    </div>
@endsection
