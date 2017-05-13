<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                Request Permission to view
            </div>
            <div class="panel-body panel-success">
                @foreach($fields as $field)
                    <a href="{{route('askPermission',['doctor_id'=>$doctor_id,
                                                        'patient_id'=>$patient->user->id,
                                                        'requesting_field_id'=>$field['id']])}}"
                      id="{{$field['id']}}" class="field button btn-primary form-control" type="submit">{{$field['field']}}</a>
                    <br/>
                @endforeach
            </div>
        </div>
    </div>
</div>


