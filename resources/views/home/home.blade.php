@extends('layouts.app')
@section('content')
  <div class="container">
    <div class="row">
    @include('alert')
      <!--the field table div-->
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <!--the field table header-->
          <div class="panel-heading bg-black text-grey">
            {{trans('system.dashboard')}} -- {{trans('system.field_description')}}
            <a id="field_button" class="text-grey position-right" role="button" data-toggle="collapse"
               href="#field_table" aria-expanded="true" aria-controls="field_table">{{trans('system.hidden')}}</a>
            <span id="show" class="hidden">{{trans('system.show')}}</span> <span id="hidden"
                                                                                 class="hidden">{{trans('system.hidden')}}</span>
          </div>
          <!--the field table header-->
          <!--the field table body-->
          <div id="field_table" class="panel-body table-responsive panel-collapse collapse in">
            <table class="table table-striped table-hover">
              <thead>
              <tr>
                <th></th>
                <th>{{trans('database.id')}}</th>
                <th>{{trans('database.field.label')}}</th>
                <th>{{trans('database.field.key')}}</th>
                <th>{{trans('database.field.params')}}</th>
                <th>{{trans('database.field.publish')}}</th>
                <th>{{trans('database.field.field_type')}}</th>
                <th>{{trans('database.option')}}</th>
              </tr>
              </thead>
              <tbody>
              <form id="field_form">
                {{ csrf_field() }}
                @foreach($fieldsObj as $field)
                  <tr id="tr_{{$field->id}}">
                    <td><input type="checkbox" name="id_{{$field->id}}" value="{{$field->id}}"/></td>
                    <td>{{$field->id}}</td>
                    <td>{{$field->label}}</td>
                    <td>{{$field->key}}</td>
                    <td>{{$field->params}}</td>
                    <td>{{trans('database.publishValue.'.$field->publish)}}</td>
                    <td>{{$field->field_type}}</td>
                    <td><a >{{trans('system.edit')}}</a>&nbsp;|&nbsp;<a class="db-href-fields db-removed">{{trans('system.remove')}}</a></td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="7" class="text-center">
                    ||&nbsp;&nbsp;&nbsp;&nbsp;<a href="">{{trans('system.hidden_all')}}
                    </a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="">{{trans('system.hidden_select')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="">{{trans('system.remove_select')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="">{{trans('system.refresh')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||
                  </td>
                </tr>
              </form>
              </tbody>
            </table>
          </div>
          <!--the field table body-->
        </div>
      </div>
      <!--the field table div-->
      <div class="col-md-5 col-md-offset-1">
        <!--The Friend Link-->
        <div class="panel panel-default">
          <div class="panel-heading bg-black text-grey">
            {{trans('system.dashboard')}} -- {{trans('system.link')}}
          </div>
          <div id="link_table" class="panel-body table-responsive">
            <table class="table table-striped table-hover">
              <thead>
              <tr>
                <th></th>
                <th>{{trans('database.id')}}</th>
                <th>{{trans('database.link.title')}}</th>
                <th>{{trans('database.link.status')}}</th>
                <th>{{trans('database.option')}}</th>
              </tr>
              </thead>
              <tbody>
              <form id="link_form">
                @foreach($linkObj as $link)
                  <tr id="tr_link_{{$link->id}}">
                    <td><input type="checkbox" name="id_{{$link->id}}" value="{{$link->id}}"/></td>
                    <td>{{$link->id}}</td>
                    <td>{{$link->title}}</td>
                    <td>{{$link->status}}</td>
                    <td><a >{{trans('system.edit')}}</a>&nbsp;|&nbsp;<a class="db-href-fields db-removed">{{trans('system.remove')}}</a></td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="8" class="text-center">
                    ||&nbsp;&nbsp;&nbsp;&nbsp;<a href="">{{trans('system.hidden_all')}}
                    </a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="">{{trans('system.hidden_select')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="">{{trans('system.remove_select')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="">{{trans('system.refresh')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||
                  </td>
                </tr>
              </form>
              </tbody>
            </table>
          </div>
        </div>
        <!--The Friend Link-->
      </div>

      <div class="col-md-5">
        <!--the project information-->
        <div class="panel panel-default">
          <!--the project information header-->
          <div class="panel-heading bg-black text-grey">{{trans('system.dashboard')}} -- {{trans('system.status')}}
            & {{trans('system.notice')}}</div>
          <!--the project information header-->
          <!--the project information body-->
          <div class="panel-body">
            <p class="text-center">This Funtion is developing...</p>
          </div>
          <!--the project information body-->
        </div>
        <!--the project information-->
        <!--the system information-->
        <div class="panel panel-default">
          <ul class="list-group">
            <li class="list-group-item bg-black text-grey ">{{trans('system.dashboard')}}
              -- {{trans('system.system_infomation')}}</li>
            @foreach( $serverArr as $key => $val )
              <li class="list-group-item {{($n += 1)%2 !== 0 ? 'list-group-item-grey' : ""}}">{{trans('system.'.$key)}}:&nbsp;&nbsp;&nbsp;&nbsp;{{$val}}</li>
            @endforeach
          </ul>
        </div>
        <!--the system information-->
      </div>

    </div>
  </div>
@endsection
