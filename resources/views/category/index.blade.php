@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
    @include('alert')
    <!--the Menu table div-->
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <!--the user table header-->
          <div class="panel-heading bg-black text-grey">
            {{trans('system.dashboard')}} -- {{trans('system.category')}}
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
                <th><input class="check_all" type="checkbox" value="0"/></th>
                <th>{{trans('database.category.fid')}}</th>
                <th>{{trans('database.category.cat_name')}}</th>
                <th>{{trans('database.category.display')}}</th>
                <th>{{trans('database.category.type')}}</th>
                <th>{{trans('database.category.url')}}</th>
                <th>{{trans('database.option')}}</th>
              </tr>
              </thead>
              <tbody>
              <form>
                {{ csrf_field() }}
                @foreach($categoryObj as $category)
                  <tr id="tr_{{$category->id}}">
                    <td><input type="checkbox" class="checkbox" name="id" value="{{$category->id}}"/></td>
                    <td>{{trans('system.top_menu')}}</td>
                    <td class="cat_name">{{$category->cat_name}}</td>
                    <td class="db-display">{{$category->display}}</td>
                    <td>{{trans('database.categoryTypeValue.'.$category->type)}}</td>
                    <td>{{$category->url}}</td>
                    <td>
                      <input type="hidden" name="all_data"
                             value="{{ base64_encode($category->id.'||'.$category->cat_name.'||'.$category->display.'||'.$category->type.'||'.$category->url.'||'.$category->sort.'||'.$category->fid) }}"
                             readonly>
                      <a data-toggle="modal" data-target="#myModal"
                         class="db-href-fields db-edit">{{trans('system.edit')}}</a>&nbsp;|&nbsp;
                      @if($category->display == 'show')
                        <a class="db-href-menu db-disabled">{{trans('system.disable')}}</a>&nbsp;|&nbsp;
                      @else
                        <a class="db-href-menu db-enabled">{{trans('system.enable')}}</a>&nbsp;|&nbsp;
                      @endif
                      <a class="db-href-menu db-removed">{{trans('system.remove')}}</a>&nbsp;
                    </td>
                    <td><a class="sub_menu" id="sub_menu_{{$category->id}}">{{trans('system.sub_menu')}}</a></td>
                  </tr>
                @endforeach
                <tr>
                  <td><input class="check_all" type="checkbox" value="0"/></td>
                  <td colspan="7" class="text-center">
                    ||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="select_href" id="disabled">{{trans('system.disable_select')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="select_href" id="enabled">{{trans('system.enable_select')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="select_href" id="removed">{{trans('system.remove_select')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||&nbsp;&nbsp;&nbsp;&nbsp;
                    <a data-toggle="modal" data-target="#myModal">{{trans('system.create')}}</a>&nbsp;&nbsp;&nbsp;&nbsp;||
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
@include('category.create', ['title' => trans('system.category_dialog'), 'categoryObj' => $categoryObj])
@if ( count($errors) > 0)
  @push('ls-js')
  <script>
    $(function () {
      $('#myModal').modal('show');
    })
  </script>
  @endpush
@endif
