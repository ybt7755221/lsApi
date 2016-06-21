@extends('layouts.app')
@section('content')
  <div class="container">
    <div class="row">
      <!--the Menu table div-->
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <!--the user table header-->
          <div class="panel-heading bg-black text-grey">
            {{trans('system.dashboard')}} -- {{trans('system.content')}}
            <a id="field_button" class="text-grey position-right" role="button" data-toggle="collapse"
               href="#field_table" aria-expanded="true"
               aria-controls="field_table">{{trans('system.hidden')}}</a>
            <span id="show" class="hidden">{{trans('system.show')}}</span> <span id="hidden"
                                                                                 class="hidden">{{trans('system.hidden')}}</span>
          </div>
          <!--the Menu table header-->
          <!--the Menu table body-->
          <div id="field_table" class="panel-body table-responsive panel-collapse collapse in">
            <table class="table table-striped table-hover">
              <thead>
              <tr>
                <th></th>
                <th>{{trans('database.content.title')}}</th>
                <th>{{trans('database.content.thumb')}}</th>
                <th>{{trans('database.content.user_id')}}</th>
                <th>{{trans('database.content.comment_status')}}</th>
                <th>{{trans('database.content.state')}}</th>
                <th>{{trans('database.content.updated_at')}}</th>
                <th>{{trans('database.option')}}</th>
              </tr>
              </thead>
              <tbody>
              <form>
                @foreach($contentObj as $content)
                  <tr>
                    <td><input type="checkbox" name="id_{{$content->id}}" value="{{$content->id}}"/></td>
                    <td>{{$content->title}}</td>
                    <td>{{$content->thumb}}</td>
                    <td>{{$content->users->name}}</td>
                    <td>{{trans('database.commentValue.'.$content->comment_status)}}</td>
                    <td>{{trans('database.stateValue.'.$content->state)}}</td>
                    <td>{{$content->updated_at}}</td>
                    <td>
                      <a href="">{{trans('system.edit')}}</a>&nbsp;|&nbsp;
                      <a href="">{{trans('system.disable')}}</a>&nbsp;|&nbsp;
                      <a href="">{{trans('system.remove')}}</a></td>
                    &nbsp;
                    </td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="8" class="text-center">
                    ||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="">{{trans('system.disable_select')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="">{{trans('system.remove_select')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="">{{trans('system.create')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||
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
    </div>
  </div>
@endsection
