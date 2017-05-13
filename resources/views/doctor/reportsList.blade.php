@extends('layouts.app')
@section('head')
    <script src="{{ asset('js/jquery-3.1.1.min.js') }}" type="text/javascript"></script>

@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Reports List
                    </div>

                    <div class="panel-body" >
                        @foreach ($reports as $f)
                            <form method="post" action="{{route('addNotes')}}" style="background-color: rgba(215, 239, 255, 0.76)">
                                {{method_field('PATCH')}}
                                {{csrf_field()}}
                                <div class="form-group">
                                    <a href="{{route('view',['user'=>$user_id,'field'=>$field_id,'file'=>$f['fileName']])}}"
                                       class="button btn-primary form-control" type="submit">{{$f['fileName']}}</a>
                                    <p>Special Notes : {{$f['description']}}</p>
                                </div>
                                @foreach($f['notes'] as $note)
                                    <div class="form-group">
                                        <p>Note : {{$note['note']}}
                                        <br/>
                                            Doctor : {{$note['doctor']}}
                                        </p>
                                    </div>
                                @endforeach
                                <div class="hidden form-group">
                                    <input type="text" class="form-control" name="file_id" id="file_id" value="{{$f['fileID']}}">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="note" id="note" placeholder="Add Note">
                                </div>
                                <div class="form-group">
                                    <button class="btn-success form-control" type="submit">Add Note</button>
                                </div>

                            </form>
                            <br/>

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
