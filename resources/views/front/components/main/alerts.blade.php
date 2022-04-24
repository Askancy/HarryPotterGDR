@if(Session::has('alert-danger'))
<div class="post-fixed fadeOut">
  <h3 class="alert alert-danger">{{Session::get('alert-danger')}}</h3>
</div>
@endif
@if(Session::has('alert-success'))
<div class="post-fixed fadeOut">
  <h3 class="alert alert-success">{{Session::get('alert-success')}}</h3>
</div>
@endif
