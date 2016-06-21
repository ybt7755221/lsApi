<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-black text-grey">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{$title}}</h4>
      </div>
      <form id="user-form" class="form-horizontal" role="form" method="POST" action="{{ url('/menu/create') }}">
        {{ csrf_field() }}
        <div class="modal-body">
          <div class="form-group{{ $errors->has('cat_name') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">{{trans('database.category.cat_name')}}</label>

            <div class="col-md-6">
              <input type="text" class="form-control" name="cat_name" value="{{ old('cat_name') }}" minlength="4" maxlength="32">
              @if ($errors->has('cat_name'))
                <span class="help-block">
                  <strong>{{ $errors->first('cat_name') }}</strong>
                </span>
              @endif
            </div>
          </div>

          <div class="form-group{{$errors->has('fid')}} ? 'has-error' : '' ">
            <label class="col-md-4 control-label">{{trans('database.category.fid')}}</label>
            <div class="col-md-6">
              <select class="form-control" name="fid" id="fid">
                  <option value=0" >{{trans('system.top_menu')}}</option>
                @foreach( $categoryObj as $category )
                  <option value="{{$category->id}}" >{{$category->cat_name}}</option>
                @endforeach
              </select>
              @if ($errors->has('fid'))
                <span class="help-block">
                  <strong>{{ $errors->first('fid') }}</strong>
                </span>
              @endif
            </div>
          </div>

          <div class="form-group{{$errors->has('type')}} ? 'has-error' : '' ">
            <label class="col-md-4 control-label">{{trans('database.category.type')}}</label>
            <div class="col-md-6">
              <select class="form-control" name="type" id="type">
                @foreach( trans('database.categoryTypeValue') as $key => $val )
                    <option value="{{$key}}" >{{$val}}</option>
                @endforeach
              </select>
              @if ($errors->has('type'))
                <span class="help-block">
                  <strong>{{ $errors->first('type') }}</strong>
                </span>
              @endif
            </div>
          </div>

          <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label">{{trans('database.category.url')}}</label>

            <div class="col-md-6">
              <div class="input-group">
              <div class="input-group-addon">http://</div>
              <input type="text" class="form-control" name="url" value="{{ old('url') }}" maxlength="128">
              </div>
              @if ($errors->has('url'))
                <span class="help-block">
                  <strong>{{ $errors->first('url') }}</strong>
                </span>
              @endif
            </div>
          </div>

          <div class="form-group{{$errors->has('display')}} ? 'has-error' : '' ">
            <label class="col-md-4 control-label">{{trans('database.category.display')}}</label>
            <div class="col-md-6">
              <select class="form-control" name="display" id="display">
                @foreach( trans('database.categoryDisplayValue') as $key => $val )
                    <option value="{{$key}}" >{{$val}}</option>
                @endforeach
              </select>
              @if ($errors->has('display'))
                <span class="help-block">
                  <strong>{{ $errors->first('display') }}</strong>
                </span>
              @endif
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