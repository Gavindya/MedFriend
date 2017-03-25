@extends('layouts.app')
@if(Auth::user()->role_id===4)
@section('head')
    <link href="/css/custom.css" rel="stylesheet">
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
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

                    @if($patients!=null)
                        <hr>
                    @include('patientSearch.listPatients')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@endsection