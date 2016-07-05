<div id="create_table" class="well bg-white collapse col-md-8 col-md-offset-2{{$errors->has() ? ' in' : ''}}">
  <form id="content-form" enctype="multipart/form-data" class="form-horizontal" role="form" method="POST" action="{{url('/content')}}">
    {{ csrf_field() }}
    @if ($errors->has() && Session::has('op'))
        @if (Session::has('edit_id'))
          <input type="hidden" name="id" value="{{Session::get('edit_id')}}" readonly="true">
          <input type="hidden" name="_method" value="put" />
        @endif
    @endif
    <div class="input-group{{ $errors->has('title') ? ' has-error' : '' }} col-md-12">
      <div class="input-group-addon">{{trans('database.content.title')}}</div>
      <input type="text" class="form-control" name="title" value="{{ old('title') }}" minlength="6" max="255"/>
    </div>
    @if ($errors->has('title'))
      <div class="help-block">
        <strong class="text-danger">{{ $errors->first('title') }}</strong>
      </div>
    @endif
    <p></p>
    <div class="form-group{{$errors->has('thumb') ? 'has-error' : '' }} col-md-5 text-center">
        <label for="thumb">{{trans('database.content.thumb')}}</label><br />
        <img id="thumbnail" src="{{url('/images/default.png')}}" width="180px" />
        <div class="clearfix" style="min-height:5px;"></div>
        <input class="form-control" type="file" name="thumb" id="thumb">
        <span id="helpBlock" class="help-block">{{trans('validation.user.format_error')}}</span>
      @if ($errors->has('thumb'))
        <div class="help-block">
          <strong class="text-danger">{{ $errors->first('thumb') }}</strong>
        </div>
      @endif
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-6">
      <div class="form-group{{$errors->has('comment_status') ? 'has-error' : '' }}">
        <label class="control-label" for="comment_status">{{trans('database.content.comment_status')}}</label>
        <select class="form-control" name="comment_status" id="comment_status">
          @foreach(trans('database.commentValue') as $key => $val)
            <option value="{{$key}}">{{$val}}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group{{$errors->has('state') ? 'has-error' : '' }}">
        <label class="control-label" for="state">{{trans('database.content.state')}}</label>
        <select class="form-control" name="state" id="state">
          @foreach(trans('database.stateValue') as $key => $val)
            <option value="{{$key}}" {{$key == 1 ? 'selected="selected"' : ''}}>{{$val}}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group{{$errors->has('cat_id') ? 'has-error' : '' }}">
        <label class="control-label" for="state">{{trans('database.content.cat_id')}}</label>
        <select class="form-control" name="cat_id" id="cat_id">
          @foreach($categoryObj as $category)
            <option value="{{$category->id}}">{{$category->cat_name}}</option>
          @endforeach
        </select>
      </div>
      @if ($errors->has('comment_status'))
        <div class="help-block">
          <strong class="text-danger">{{ $errors->first('comment_status') }}</strong>
        </div>
      @endif
      @if ($errors->has('state'))
        <div class="help-block">
          <strong class="text-danger">{{ $errors->first('state') }}</strong>
        </div>
      @endif
      @if ($errors->has('cat_id'))
        <div class="help-block">
          <strong class="text-danger">{{ $errors->first('cat_id') }}</strong>
        </div>
      @endif
    </div>
    <p class="clearfix"></p>
    @if ($errors->has('body'))
      <div class="help-block">
        <strong class="text-danger">{{ $errors->first('body') }}</strong>
      </div>
    @endif
    <textarea id="summernote" name="body" class="summernote form-control">{{old('body')}}</textarea>
    <p class="clearfix"></p>
    <p class="text-center">
      <button type="submit" class="btn btn-primary">{{trans('system.save')}}</button>
      <button type="button" class="btn btn-default collapse-close">{{trans('system.cancel')}}</button>
    </p>
  </form>

</div>
@push('ls-style')
<link href="{{url('summernote/summernote.css')}}" rel="stylesheet">
@endpush
@push('ls-js')
<script src="{{url('summernote/summernote.js')}}"></script>
<script>
  $(document).ready(function() {
    $('#summernote').summernote({
      height: 300
    });
  });
</script>
@endpush