@extends('layouts.app')
@if(Auth::user()->role_id===1)
    @section('content')
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Dashboard</div>

                        <div class="panel-body">
                            You are logged in!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

@elseif(Auth::user()->role_id===2)
    @section('content')
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Dashboard</div>

                        <div class="panel-body">
                            You are logged in!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@elseif(Auth::user()->role_id===3)
    <script type="text/javascript">
        window.location = "{{ url('/doctorHome') }}";//here double curly bracket
    </script>
@elseif(Auth::user()->role_id===4)
    {{--@section('content')--}}
        {{--@include('labAssistant.labHomePage')--}}
    {{--@endsection--}}
    <script type="text/javascript">
        window.location = "{{ url('/labHome') }}";//here double curly bracket
    </script>
@endif

