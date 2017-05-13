@extends('layouts.app')
@section('head')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Allowed Fields
                    </div>

                    <div class="panel-body panel-success">
                        @foreach($FieldNameList as $field)
                            <div class="form-group">
                                <a href="{{route('listOutRecords',['patient_id'=>$patient_id,
                                                                'specialization_field_id'=>$field['id']])}}"
                                   class="button btn-primary form-control" value="{{$field['id']}}">{{$field['field']}}</a>
                            </div>
                        @endforeach
                     </div>
                    <div class="hidden form-group">
                        <input type="text" class="form-control" name="doctor_id" id="doctor_id" value="{{$doctor_id}}">
                    </div>
                    <div class="hidden form-group">
                        <input type="text" class="form-control" name="patient_id" id="patient_id" value="{{$patient_id}}">
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
