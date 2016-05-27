@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!--the field table div-->
        <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <!--the field table header-->
                    <div class="panel-heading bg-black text-grey">
                        {{trans('system.dashboard')}} -- {{trans('system.user_information')}}
                        <a id="field_button" class="text-grey position-right" role="button" data-toggle="collapse" href="#field_table" aria-expanded="true" aria-controls="field_table">{{trans('system.hidden')}}</a>
                        <span id="show" class="hidden">{{trans('system.show')}}</span> <span id="hidden" class="hidden">{{trans('system.hidden')}}</span>
                    </div>
                    <!--the field table header-->
                    <!--the field table body-->
                    <div id="field_table" class="panel-body table-responsive panel-collapse collapse in" >
                        <table class="table table-striped table-hover">
                            <thead><tr>
                                <th></th>
                                <th>{{trans('database.user.name')}}</th>
                                <th>{{trans('database.user.email')}}</th>
                                <th>{{trans('database.user.status')}}</th>
                                <th>{{trans('database.user.created_at')}}</th>
                                <th>{{trans('database.user.updated_at')}}</th>
                                <th>{{trans('database.option')}}</th>
                            </tr></thead>
                            <tbody>
                            <form>
                                @foreach($userObj as $user)
                                    <tr>
                                        <td><input type="checkbox" name="id_{{$user->id}}" value="{{$user->id}}" /></td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{trans('database.user.statusValue.'.$user->status)}}</td>
                                        <td>{{$user->created_at}}</td>
                                        <td>{{$user->updated_at}}</td>
                                        <td><a href="">{{trans('system.edit')}}</a>&nbsp;|&nbsp;<a href="">{{trans('system.disable')}}</a>&nbsp;|&nbsp;<a href="">{{trans('system.remove')}}</a></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="7" class="text-center">
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
                    <!--the field table body-->
                </div>
            </div>
        <!--the field table div-->
    </div>
</div>
@endsection
