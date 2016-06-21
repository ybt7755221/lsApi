<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-black text-grey">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{$title}}</h4>
      </div>
      <form id="user-form" class="form-horizontal" role="form" method="POST" action="{{ url('/user/create') }}">
        {{ csrf_field() }}
        <div class="modal-body">
          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">{{trans('database.user.name')}}</label>

            <div class="col-md-6">
              <input type="text" class="form-control" name="name" value="{{ old('name') }}" minlength="4"
                     maxlength="32">
              @if ($errors->has('name'))
                <span class="help-block">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
              @endif
            </div>
          </div>

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">{{trans('database.user.email')}}</label>

            <div class="col-md-6">
              <input type="email" class="form-control" name="email" value="{{ old('email') }}" maxlength="128">

              @if ($errors->has('email'))
                <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
              @endif
            </div>
          </div>

          <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} user_password">
            <label class="col-md-4 control-label">{{trans('database.user.password')}}</label>

            <div class="col-md-6">
              <input type="password" class="form-control password" name="password" minlength="6" maxlength="20">
              @if ($errors->has('password'))
                <span class="help-block">
                  <strong>{{ $errors->first('password') }}</strong>
                </span>
              @endif
            </div>
          </div>

          <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} user_password">
            <label class="col-md-4 control-label">{{trans('system.confirm_password')}}</label>

            <div class="col-md-6">
              <input type="password" class="form-control password" name="password_confirmation">
              @if ($errors->has('password'))
                <span class="help-block">
                  <strong>{{ $errors->first('password') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group{{$errors->has('status')}} ? 'has-error' : '' ">
            <label class="col-md-4 control-label">{{trans('database.user.status')}}</label>
            <div class="col-md-6">
              <select class="form-control" name="status" id="status">
                @foreach( trans('database.statusValue') as $key => $val )
                  @if($key !== 4)
                    <option value="{{$key}}" {{$key == 1 ? 'selected' : ''}} >{{$val}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-btn fa-user"></i>{{trans('system.save')}}
          </button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>