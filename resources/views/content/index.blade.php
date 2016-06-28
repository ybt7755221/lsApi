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
            <div class="col-md-10">{{trans('system.dashboard')}} -- {{trans('system.content')}}</div>
            <div class="col-md-2">
              <form id="state">
                <select id="now_state" name="now_state" class="form-control" style="height:20px;" >
                  <option value="-1">All</option>
                  @foreach(trans('database.stateValue') as $key => $val)
                    @if (Session::has('content_state'))
                      <option value="{{$key}}" {{Session::get('content_state') == $key ? 'selected' : "" }} >{{$val}}</option>
                    @else
                      <option value="{{$key}}">{{$val}}</option>
                    @endif
                  @endforeach
                </select>
              </form>
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
