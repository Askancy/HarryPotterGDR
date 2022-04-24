@foreach ($object as $value)
  <div class="col-6 row padding-5" id="container-#{{$value->id}}">
    <div class="col-6">
      <img src="http://via.placeholder.com/150x150">
    </div>
    <div class="col-6">
      <a href="{{url('object/'.$value->slug)}}"><h4>{{$value->name}}</h4></a>
      <span>{{str_limit($value->description, 100)}}</span>
      <span>Prezzo {{$value->price}}</span>
      <div class="col-12" style="margin-top: 10px">
        <button id="{{$value->id}}" type="button" class="btn btn-primary">Acquista</button>
      </div>
    </div>
  </div>


<script type="text/javascript">
  $(function() {
    var id_obj = {{$value->id}};
    $("#{{$value->id}}").click(function(e) {
          $.ajax({
              type: "GET",
              url: "{{url('ajax/purchase')}}",
              cache:false,
              data: {
                id_obj: id_obj,
              },
              if (id_obj <=	 "{{$value->id}}") {
                success: function(response) {
                  console.log("success");
                }
              } else {
                error: function(response) {
                  console.log("error");
                }
              };
          });
      });
  });
</script>
@endforeach
