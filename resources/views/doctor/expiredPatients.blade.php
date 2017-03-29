<div id="expPatients">
    <table id="expPatientsTable" class="table table-hover table-responsive">
        <thead>
        <tr>
            <th width="10%">Title</th>
            <th width="30%">Name</th>
            <th width="10%">Gender</th>
            <th width="10%">Age</th>
            <th width="10%">Hair Color</th>
            <th width="10%">Eye Color</th>
            <th width="10%">Height</th>
            <th width="10%">Weight</th>

        </tr>
        </thead>
        <tbody>
        @foreach($expired as $patient)
            <tr>
            <tr class="patient" id="{{$patient['id']}}">
                {{--<tr class="clickable-row memberSearchResults" data-href="{{ route('memberSettings',$member['id'])}}">--}}
                <td class="id">{{$patient->patient->title}}</td>
                <td class="id">{{$patient->patient->user->name}} {{$patient->patient->user->last_name}}</td>
                <td class="id">{{$patient->patient->gender}}</td>
                <td class="id">{{$patient->patient->birthday}}</td>
                <td class="id">{{$patient->patient->hair_color}}</td>
                <td class="id">{{$patient->patient->eye_color}}</td>
                <td class="id">{{$patient->patient->height}}</td>
                <td class="id">{{$patient->patient->weight}}</td>
            </tr>
        @endforeach
        {{--{!! $active->links() !!}--}}
        </tbody>
    </table>
</div>