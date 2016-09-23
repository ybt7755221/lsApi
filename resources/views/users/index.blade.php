@extends('layouts.app')
@section('content')
  <div class="container">
    <div class="row">
      @include('alert')
      <!--the user table div-->
      <div class="col-md-12">
        <div class="panel panel-default">
          <!--the user table header-->
          <div class="panel-heading bg-black text-grey">
            {{trans('system.dashboard')}} -- {{trans('system.user_information')}}
            <a id="field_button" class="text-grey position-right" role="button" data-toggle="collapse"
               href="#field_table" aria-expanded="true" aria-controls="field_table">{{trans('system.hidden')}}</a>
            <span id="show" class="hidden">{{trans('system.show')}}</span> <span id="hidden"
                                                                                 class="hidden">{{trans('system.hidden')}}</span>
          </div>
          <!--the user table header-->
          <!--the user table body-->
          <div id="field_table" class="panel-body table-responsive panel-collapse collapse in">
            <table class="table table-striped table-hover">
              <thead>
              <tr>
                <th><input class="check_all" type="checkbox" value="0"/></th>
                <th>{{trans('database.user.name')}}</th>
                <th>{{trans('database.user.email')}}</th>
                <th>{{trans('database.user.status')}}</th>
                <th>{{trans('database.created_at')}}</th>
                <th>{{trans('database.user.updated_at')}}</th>
                <th>{{trans('database.option')}}</th>
              </tr>
              </thead>
              <tbody>
              <form>
                {{ csrf_field() }}
                @foreach($userObj as $user)
                  <tr id="tr_{{$user->id}}">
                    <td><input type="checkbox" class="checkbox" name="id_{{$user->id}}" value="{{$user->id}}"/></td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td class="user_status">{{trans('database.statusValue.'.$user->status)}}</td>
                    <td>{{$user->created_at}}</td>
                    <td>{{$user->updated_at}}</td>
                    <td id="td_operation">
                      <input type="hidden" name="all_data"
                             value="{{ base64_encode($user->id.'||'.$user->name.'||'.$user->email.'||'.$user->status.'||'.$user->password) }}"
                             readonly>
                      <a data-toggle="modal" data-target="#myModal" class="db-href db-edit">{{trans('system.edit')}}</a>&nbsp;|&nbsp;
                      <a class="db-href db-removed">{{trans('system.remove')}}</a>&nbsp;|&nbsp;
                      <a class="db-href db-oauth">{{trans('system.oauth')}}</a>
                    </td>
                  </tr>
                @endforeach
                <tr>
                  <td><input class="check_all" type="checkbox" value="0"/></td>
                  <td colspan="6" class="text-center">
                    ||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="select_href" id="disable">{{trans('system.disable_select')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="select_href" id="remove">{{trans('system.remove_select')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="click_create" data-toggle="modal" data-target="#myModal">{{trans('system.create')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||
                  </td>
                </tr>
              </form>
              </tbody>
            </table>
          </div>
          <!--the user table body-->
        </div>
      </div>
      <!--the user table div-->
    </div>
  </div>
@endsection
@include('users.create', ['title' => trans('system.user_dialog'),'error' => $errors])
@if ( count($errors) > 0)
  @push('ls-js')
  <script>
    $(function () {
      $('#myModal').modal('show');
    })
  </script>
  @endpush
@endif



