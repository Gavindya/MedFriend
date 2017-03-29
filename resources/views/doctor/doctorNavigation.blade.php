@section('navbarItems')
    {{--<a class="navbar-brand" id="info" href="#infoSec" data-toggle="collapse">Additional Info</a>--}}
    {{--<a class="navbar-brand" id="work" href="#workoutSec" data-toggle="collapse">Workout Schedule</a>--}}
    {{--<a class="navbar-brand" id="pay" href="#paySec" data-toggle="collapse">Payments</a>--}}
    {{--<a class="navbar-brand" href="{{route('reportsPage')}}" >Reports</a>--}}
    {{--<a class="navbar-brand" href="{{route('newMemberPage')}}">New Member</a>--}}

    <a class="navbar-brand" href="#">Home</a>
    <li class="dropdown">
        <a href="#" class="navbar-brand dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            Patients <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href="#">Waiting Patients</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Active Patients</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Expired Patients</a></li>
        </ul>
    </li>
    @if($waiting!=null)
        <li class="dropdown">
            <a href="#" class="navbar-brand dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                Notifications <span class="caret"></span></a>
            <ul class="dropdown-menu">
                {{--@foreach($waiting as $notification)--}}

                {{--@endforeach--}}
                <li><a href="#">Member Settings</a></li>
                <li><a href="#">Deactivate Member</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">Package Settings</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">Payment Settings</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">Add Location</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">Settings</a></li>
            </ul>
        </li>
    @endif


@endsection
