@extends('layouts.app')
@section('content')
  <div class="container">
    @include('alert')
    <h1 class="page-header">Dashboard For Oauth2.0</h1>
    <div class="row">
      <div class="panel col-xs-12 col-md-6">
        <div class="panel-body">
          <p><h4 style="padding: 0 10px;">Information By <strong>{{ Auth::user()->name }}</strong></h4></p>
          <ul class="list-group">
            <li class="list-group-item"><strong>{{trans('database.user.email')}} : </strong>{{Auth::user()->email}}</li>
            <li class="list-group-item"><strong>{{trans('database.user.status')}} : </strong>{{trans('database.statusValue.'.Auth::user()->status)}}</li>
            <li class="list-group-item"><strong>{{trans('database.created_at')}} : </strong>{{Auth::user()->created_at}}</li>
            <li class="list-group-item"><strong>{{trans('database.user.updated_at')}} : </strong>{{Auth::user()->updated_at}}</li>
          </ul>
        </div>
      </div>
      <div class="panel col-xs-12 col-md-6">
        <div class="panel-body">
          <div class="row">
            <span class="col-md-6"><h4 style="padding: 0 10px;">Oauth2.0 By <strong>{{ Auth::user()->name }}</strong></h4></span>
            <span class="col-md-6 text-right">
            @if(!isset($oauth_obj->secret) && empty($oauth_obj->secret) )
              <a href='Javascript:void(0);' class="db-oauth db-create" style="line-height:40px; padding: 0 10px;">{{trans('system.create_oauth')}}</a>
            @else
                <!--a href='Javascript:void(0);' class="db-oauth db-refresh" style="line-height:40px; padding: 0 10px;">{{trans('system.refresh_oauth')}}</a-->
            @endif
            </span>
          </div>
          @if($oauth_obj)
          <ul class="list-group">
            <li class="list-group-item"><strong>{{trans('database.oauth.name')}} : </strong>{{$oauth_obj->name}}</li>
            <li class="list-group-item"><strong>{{trans('database.oauth.id')}} : </strong><span id="oauth_id">{{$oauth_obj->id}}</span></li>
            <li class="list-group-item"><strong>{{trans('database.oauth.secret')}} : </strong><span id="oauth_secret">{{$oauth_obj->secret}}</span></li>
            <li class="list-group-item"><strong>{{trans('database.created_at')}} : </strong>{{$oauth_obj->created_at}}</li>
          </ul>
          @endif
        </div>
      </div>
    </div>
    <h2 class="sub-header">Section title</h2>

  </div>
@endsection