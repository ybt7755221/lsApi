@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading bg-black text-grey">{{trans('system.dashboard')}}</div>

                <div class="panel-body">
                    @foreach($fields as $field)
                        <p>{{$field->label}} || {{$field->key}} || {{$field->params}} || {{$field->publish}}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
