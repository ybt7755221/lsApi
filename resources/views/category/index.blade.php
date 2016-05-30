@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
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
                                <th></th>
                                <th>{{trans('database.category.fid')}}</th>
                                <th>{{trans('database.category.cat_name')}}</th>
                                <th>{{trans('database.category.display')}}</th>
                                <th>{{trans('database.category.type')}}</th>
                                <th>{{trans('database.category.url')}}</th>
                                <th>{{trans('database.category.sort')}}</th>
                                <th>{{trans('database.option')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <form>
                                @foreach($categoryObj as $category)
                                    <tr>
                                        <td><input type="checkbox" name="id_{{$category->id}}" value="{{$category->id}}"/></td>
                                        <td>{{trans('system.top_menu')}}</td>
                                        <td>{{$category->cat_name}}</td>
                                        <td>{{$category->display}}</td>
                                        <td>{{$category->type}}</td>
                                        <td>{{$category->url}}</td>
                                        <td>{{$category->sort}}</td>
                                        <td>
                                            <a href="">{{trans('system.edit')}}</a>&nbsp;|&nbsp;
                                            <a href="">{{trans('system.disable')}}</a>&nbsp;|&nbsp;
                                            <a href="">{{trans('system.remove')}}</a></td>&nbsp;
                                        </td>
                                        <td><a href="">{{trans('system.sub_menu')}}</a></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="9" class="text-center">
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
