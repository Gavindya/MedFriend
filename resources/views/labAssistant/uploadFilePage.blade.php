@extends('layouts.app')

@section('head')
    <link href="/css/custom.css" rel="stylesheet">
    <script type="text/javascript">

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
                        <form method="post" action="{{route('uploadFile')}}" enctype="multipart/form-data" >
                            {{method_field('PATCH')}}
                            {{csrf_field()}}
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="file" name="doc" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="hidden form-group">
                                <input type="text" class="form-control" name="patient_id" id="patient_id" value="{{$patientID}}">
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-lg-offset-1 ">
                                    <div class="form-group">
                                        <button class="btn-primary form-control" type="submit">Upload</button>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-lg-offset-4">
                                    <div class="form-group">
                                        <button class="btn-primary form-control" type="reset" value="Reset">Reset</button>
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