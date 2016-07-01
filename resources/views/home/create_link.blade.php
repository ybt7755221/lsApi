<div class="modal fade" id="myModal_link" tabindex="-1" role="dialog" aria-labelledby="myModal_link">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-black text-grey">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{$title}}</h4>
      </div>
      <form id="link-form" enctype="multipart/form-data" class="form-horizontal" role="form" method="POST" action="{{ url('/home/create') }}">
        {{ csrf_field() }}
        <div class="modal-body col-md-10 col-md-offset-1">
          <input type="hidden" name="table_name" value="link" readonly="true"/>
          <div class="col-md-6">
            <label>{{trans('database.link.thumb')}}</label>
            <img id="thumb" src={{url('/../storage/uploads/default.png')}} width="180px" />
            <input type="file" name="thumb" value="{{old('thumb')}}" />
          </div>
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            <label>{{trans('database.link.title')}}</label>
            <input type="text" class="form-control" name="title" value="{{ old('title') }}">
            @if ($errors->has('title'))
              <span class="help-block">
                <strong>{{ $errors->first('title') }}</strong>
              </span>
            @endif
          </div>

            <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
            <label>{{trans('database.link.url')}}</label>
            <input type="text" class="form-control" name="url" value="{{ old('url') }}">
            @if ($errors->has('url'))
              <span class="help-block">
                  <strong>{{ $errors->first('url') }}</strong>
                </span>
            @endif
          </div>

            <div class="form-group{{$errors->has('status') ? ' has-error' : '' }}">
              <label>{{trans('database.link.status')}}</label>
              <select class="form-control" name="status" id="status">
                @foreach( trans('database.categoryDisplayValue') as $key => $val )
                  <option value="{{$key}}" >{{$val}}</option>
                @endforeach
              </select>
              @if ($errors->has('status'))
                <span class="help-block">
                  <strong>{{ $errors->first('status') }}</strong>
                </span>
              @endif
          </div>

            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
              <label>{{trans('database.link.description')}}</label><br />
              <textarea id="description" name="description"  class="form-control" rows="2"></textarea>
              @if ($errors->has('description'))
                <span class="help-block">
                  <strong>{{ $errors->first('description') }}</strong>
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