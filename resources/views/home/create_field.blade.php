<div class="modal fade" id="myModal_field" tabindex="-1" role="dialog" aria-labelledby="myModal_field">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-black text-grey">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{$title}}</h4>
      </div>
      <form id="field-form" class="form-horizontal" role="form" method="POST" action="{{ url('/home/create') }}">
        {{ csrf_field() }}
        <div class="modal-body col-md-10 col-md-offset-1">
          <input type="hidden" name="table_name" value="fields" readonly="true"/>
          <div class="form-group{{ $errors->has('label') ? ' has-error' : '' }}">
            <label>{{trans('database.field.label')}}</label>
            <div class="">
              <input type="text" class="form-control" name="label" value="{{ old('label') }}">
              @if ($errors->has('label'))
                <span class="help-block">
                  <strong>{{ $errors->first('label') }}</strong>
                </span>
              @endif
            </div>
          </div>

          <div class="form-group{{ $errors->has('key') ? ' has-error' : '' }}">
            <label>{{trans('database.field.key')}}</label>
              <input type="text" class="form-control" name="key" value="{{ old('key') }}">
              @if ($errors->has('key'))
                <span class="help-block">
                  <strong>{{ $errors->first('key') }}</strong>
                </span>
              @endif
          </div>

          <div class="form-group{{ $errors->has('params') ? ' has-error' : '' }}">
            <label>{{trans('database.field.params')}}</label>
              <input type="text" class="form-control" name="params" value="{{ old('params') }}">
              @if ($errors->has('params'))
                <span class="help-block">
                  <strong>{{ $errors->first('params') }}</strong>
                </span>
              @endif
          </div>

          <div class="form-group col-md-5{{$errors->has('publish') ? 'has-error' : '' }}">
            <div class="input-group">
              <label class="input-group-addon">{{trans('database.field.publish')}}</label>
              <select class="form-control" name="publish" id="publish">
                @foreach( trans('database.publishValue') as $key => $val )
                  <option value="{{$key}}" >{{$val}}</option>
                @endforeach
              </select>
              @if ($errors->has('publish'))
                <span class="help-block">
                  <strong>{{ $errors->first('publish') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="col-md-1 com-sm-0"></div>
          <div class="form-group col-md-6{{$errors->has('field_type')}} ? 'has-error' : '' ">
            <div class="input-group">
              <label class="input-group-addon">{{trans('database.field.field_type')}}</label>
              <select class="form-control" name="field_type" id="field_type">
                @foreach( trans('database.field_type_value') as $val )
                  <option value="{{$val}}" >{{$val}}</option>
                @endforeach
              </select>
              @if ($errors->has('field_type'))
                <span class="help-block">
                  <strong>{{ $errors->first('field_type') }}</strong>
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