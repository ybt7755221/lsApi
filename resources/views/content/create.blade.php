<div id="create_table" class="well bg-white collapse col-md-8 col-md-offset-2" >
  <form id="content-form" class="form-inline" role="form" method="POST" action="{{ url('/menu/create') }}">

    <div class="input-group{{ $errors->has('title') ? ' has-error' : '' }} col-md-12">
      <div class="input-group-addon">{{trans('database.content.title')}}</div>
      <input type="text" class="form-control" name="title" value="{{ old('title') }}" minlength="6" max="255"/>
    </div>
    @if ($errors->has('title'))
      <div class="col-md-offset-1 help-block">
        <strong>{{ $errors->first('title') }}</strong>
      </div>
    @endif

    <p></p>
    <div class="form-group{{$errors->has('comment_status') ? 'has-error' : '' }} col-md-4">
      <label class="control-label" for="comment_status">{{trans('database.content.comment_status')}}</label>
      <select class="form-control" name="comment_status" id="comment_status">
        @foreach(trans('database.commentValue') as $key => $val)
          <option value="{{$key}}">{{$val}}</option>
        @endforeach
      </select>
      @if ($errors->has('comment_status'))
        <span class="help-block">
          <strong>{{ $errors->first('comment_status') }}</strong>
        </span>
      @endif
    </div>
    <div class="form-group{{$errors->has('state') ? 'has-error' : '' }} col-md-4">
      <label class="control-label" for="state">{{trans('database.content.state')}}</label>
      <select class="form-control" name="state" id="state">
        @foreach(trans('database.stateValue') as $key => $val)
          <option value="{{$key}}">{{$val}}</option>
        @endforeach
      </select>
      @if ($errors->has('state'))
        <span class="help-block">
            <strong>{{ $errors->first('state') }}</strong>
          </span>
      @endif
    </div>
    <div class="form-group{{$errors->has('cat_id') ? 'has-error' : '' }} col-md-4">
      <label class="control-label" for="state">{{trans('database.content.cat_id')}}</label>
      <select class="form-control" name="cat_id" id="cat_id">
        @foreach($categoryObj as $category)
          <option value="{{$category->id}}">{{$category->cat_name}}</option>
        @endforeach
      </select>
      @if ($errors->has('cat_id'))
        <span class="help-block">
          <strong>{{ $errors->first('cat_id') }}</strong>
        </span>
      @endif
    </div>

    <p class="clearfix"></p>
    <textarea id="body" name="body" class="form-control" rows="8">{{old('body')}}</textarea>
    <p class="clearfix"></p>
    <p class="text-center">
      <button type="submit" class="btn btn-primary">{{trans('system.save')}}</button>
      <button type="button" class="btn btn-default">{{trans('system.cancel')}}</button>
    </p>
  </form>

</div>
@push('ls-js')
<script src="{{url('tinymce/tinymce.min.js')}}"></script>
<script>
  tinymce.init({
    selector: '#body'
  });
</script>
@endpush