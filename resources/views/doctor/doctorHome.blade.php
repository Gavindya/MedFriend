@extends('layouts.app')
@if(Auth::user()->role_id===3)
    @include('doctor.doctorNavigation')
@section('head')
    <link href="/css/custom.css" rel="stylesheet">
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            //need to get notofications

            $('#searchResults').hide();
            $('#searchInput').on('keyup', function () {
                $value = $(this).val();
//                alert($value);
                $.ajax({
                    type: 'GET',
                    url: '{{url('/searchPatients')}}',
                    data: {$value},
                    success: function (data) {
                        $('#searchResults').show();
                        $('#searchResults').html(data);
                    }
                });
            });


            $("#searchResults").on("click", 'li', function(){
                $value = this.id;
                $name =$(this).text();
                $.ajax({
                    type : 'GET',
                    url : '{{url('/getSelectedPatient')}}',
                    data:{$value},
                    success:function(data) {
                        $('#searchInput').val($name);
                        $('#selectedPatient').addClass('selectedMember').html(data);
//                    $('#btnInfo').removeClass('hidden');
                        $('#searchResults').fadeOut();
                    }
                });
            });

            $(".patient").on("click", function(){
                $value = this.id;
//                alert($value);
                $name =$(this).find('.nameOfPatient').text();
                alert($name);
                $.ajax({
                    type : 'GET',
                    url : '{{url('/getSelectedPatient')}}',
                    data:{$value},
                    success:function(data) {
                        $('#searchInput').val($name);
                        $('#selectedPatient').addClass('selectedPatient').html(data);
                        $('#searchResults').fadeOut();
                    }
                });
            });


        });
    </script>

@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Search Patient
                    </div>

                    <div class="panel-body">
                        @include('patientSearch.searchTextBox')
                    </div>
                </div>
            </div>
        </div>

        @include('patientSearch.patientDetails')
<br/>
        @if($active!=null)
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-success alert-success">
                    <div class="panel-heading">
                        Active Patients
                    </div>

                    <div class="panel-body">
                        @include('doctor.activePatients')

                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($expired!=null)
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-danger alert-danger">
                        <div class="panel-heading">
                            Expired Patients
                        </div>

                        <div class="panel-body">
                            @include('doctor.expiredPatients')
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
@endif