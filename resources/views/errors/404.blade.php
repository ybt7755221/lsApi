<!DOCTYPE html>
<html>
<head>
  <title>404 NOT FOUND</title>
  <link rel="stylesheet" href="{{url('css/errors.css')}}"/>
</head>
<body>
<div class="container">
  <div class="content">
    <div class="title">HTTP Error 404</div>
    <div class="tab-content">The page not be found.</div>
    <hr/>
    <div class="tab-pane">
      <a href="{{url('/home')}}">Return to the index page</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="" onclick="javascript:history.go(-1);">Be right back</a>
    </div>
  </div>
</div>
</body>
</html>