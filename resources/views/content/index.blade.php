@extends('layouts.app')
@section('content')
  <div class="container">
    <div class="row">
      @include('alert')
      <!--the Menu table div-->
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <!--the user table header-->
          <div class="panel-heading bg-black text-grey col-md-12">
            <div class="col-md-3">{{trans('system.dashboard')}} -- {{trans('system.content')}}</div>
            <div class="dropdown col-md-9 text-right">
              <a href="#" class="dropdown-toggle text-grey" data-toggle="dropdown" role="button" aria-expanded="false">
                <span id="now_state_0" data-toggle="0" class="now_state hidden">{{trans('database.stateValue.0')}}</span>
                <span id="now_state_1" data-toggle="1" class="now_state">{{trans('database.stateValue.1')}}</span>
                <span id="now_state_2" data-toggle="2" class="now_state hidden">{{trans('database.stateValue.2')}}</span>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-right bg-black" aria-labelledby="dropdownMenu1">
                @foreach(trans('database.stateValue') as $key => $val)
                  <li><a id="{{$key}}">{{$val}}</a></li>
                @endforeach
              </ul>
            </div>
          </div>
          <!--the Menu table header-->
          <!--the Menu table body-->
          <div id="field_table" class="panel-body table-responsive">
            <table class="table table-striped table-hover">
              <thead>
              <tr>
                <th><input class="check_all" type="checkbox" value="0"/></th>
                <th>{{trans('database.content.title')}}</th>
                <th>{{trans('database.user.name')}}</th>
                <th>{{trans('database.category.cat_name')}}</th>
                <th>{{trans('database.content.comment_status')}}</th>
                <th>{{trans('database.content.state')}}</th>
                <th>{{trans('database.content.updated_at')}}</th>
                <th>{{trans('database.option')}}</th>
              </tr>
              </thead>
              <tbody>
              <form>
                @foreach($contentObj as $content)
                  <tr id="tr_{{$content->id}}">
                    <td><input type="checkbox" class="checkbox" id="id_{{$content->id}}" name="id" value="{{$content->id}}"/></td>
                    <td>{{$content->title}}</td>
                    <td class="user_id" id="{{$content->user_id}}">{{$content->users->name}}</td>
                    <td>{{$content->category->cat_name}}</td>
                    <td>{{trans('database.commentValue.'.$content->comment_status)}}</td>
                    <td>{{trans('database.stateValue.'.$content->state)}}</td>
                    <td>{{$content->updated_at}}</td>
                    <td id="option">
                      <a class="db-href-content db-edit">{{trans('system.edit')}}</a><span>&nbsp;|&nbsp;</span>
                      <a class="db-href-content db-removed">{{trans('system.remove')}}</a></td>
                    &nbsp;
                    </td>
                  </tr>
                @endforeach
                <tr>
                  <td><input class="check_all" type="checkbox" value="0"/></td>
                  <td colspan="7" class="text-center">
                    ||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a role="button" data-toggle="collapse" href="#create_table" aria-expanded="true"
                       aria-controls="create_table">{{trans('system.create')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||
                  </td>
                </tr>
              </form>
              </tbody>
            </table>
          </div>
          <!--the Menu table body-->
        </div>
      </div>
      <!--the user Menu div-->
      <div class="row">
        @include('content.create', ['title' => 'content', 'categoryObj'=>$categoryObj])
      </div>
    </div>
  </div>
@endsection
