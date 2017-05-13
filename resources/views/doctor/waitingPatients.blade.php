<div id="waitingPatients">
    <table id="waitingPatientsTable" class="table table-hover table-responsive">
        <thead>
        <tr>
            <th width="10%">Title</th>
            <th width="30%">Name</th>
            <th width="10%">Gender</th>
            <th width="10%">Date of Birth</th>
            <th width="10%">Hair Color</th>
            <th width="10%">Eye Color</th>
            <th width="10%">Height</th>
            <th width="10%">Weight</th>

        </tr>
        </thead>
        <tbody>
        @foreach($waiting as $patient)
            <tr>
            <tr class="patient" id="{{$patient->patient->patient_id}}">
                {{--<tr class="clickable-row memberSearchResults" data-href="{{ route('memberSettings',$member['id'])}}">--}}
                <td class="title">{{$patient->patient->title}}</td>
                <td class="nameOfPatient">{{$patient->patient->user->name}} {{$patient->patient->user->last_name}}</td>
                <td class="gender">{{$patient->patient->gender}}</td>
                <td class="dob">{{$patient->patient->birthday}}</td>
                <td class="hair">{{$patient->patient->hair_color}}</td>
                <td class="eye">{{$patient->patient->eye_color}}</td>
                <td class="height">{{$patient->patient->height}}</td>
                <td class="weight">{{$patient->patient->weight}}</td>
            </tr>
        @endforeach
        {{--{!! $active->links() !!}--}}
        </tbody>
    </table>
</div>