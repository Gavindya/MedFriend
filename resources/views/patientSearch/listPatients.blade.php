<div class="panel-body" id="patientsTable">
    <table id="membersTable" class="table table-hover table-responsive">
        <thead>
        <tr>
            <th width="15%">Member ID</th>
            <th width="50%">Name</th>
            <th width="15%">Duration</th>
            <th width="20%">Mobile No</th>
        </tr>
        </thead>
        <tbody>
        @foreach($patients as $patient)
            <tr>
            <tr class="memberSearchResults" id="{{$patient['id']}}">
                {{--<tr class="clickable-row memberSearchResults" data-href="{{ route('memberSettings',$member['id'])}}">--}}
                <td class="id">{{$patient['id']}}</td>
                <td class="nameOfMem">{{$patient['first_name']}}&nbsp;{{$patient['last_name']}}</td>
                <td>{{$patient['duration']}}</td>
                <td>{{$patient['mobile']}}</td>
                <td> </td>
            </tr>
        @endforeach
        {!! $patients->links() !!}
        </tbody>
    </table>
</div>