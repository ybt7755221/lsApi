<!--the alert area-->
<div id="alert-static" class="alert hidden" role="alert">
  <button type="button" class="close" id="alert-static-close" aria-label="Close"><span aria-hidden="true">&times;</span>
  </button>
  <p></p>
</div>
@if(Session::has('success'))
  <div class="alert alert-success will-hide" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    {{Session::get('success')}}
  </div>
@endif
@if(Session::has('error'))
  <div class="alert alert-danger will-hide" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    {{Session::get('error')}}
  </div>
@endif
@if(Session::has('waring'))
  <div class="alert alert-warning will-hide" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    {{Session::get('waring')}}
  </div>
@endif
<!--the alert area-->