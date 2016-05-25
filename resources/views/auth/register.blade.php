@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('system.register') }}</div>
                <div class="panel-body">
                   <p>{{trans('errors.LS40301')}}</p>
                   <p><a href="{{url('/')}}">Return the login page</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
