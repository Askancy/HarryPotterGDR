<form method="POST" action="#" aria-label="{{ __('New Topic') }}" id="loginStyle" class="justify-content-center">
  @csrf

  <div class="board-form">
    <h3>Rispondi velocemente</h3>

    <div class="board-form-group">
      <h4 class="form-control"></h4>
    </div>

    <div class="board-form-group">
      <textarea rows="10" id="answer" class="form-control{{ $errors->has('text') ? ' is-invalid' : '' }}" name="text" placeholder="Inizia a scrivere..."></textarea>
    </div>

    @if ($errors->has('text'))
    <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('text') }}</strong>
            </span>
    @endif

    <div class="board-form-group">
      <button class="btn btn-dark" type="submit">
        {{__('Rispondi')}}
      </button>
    </div>

  </div>
</form>

<script type="text/javascript">
$(function(){$.fn.update=function(s,b){var $this = this;$this.updateText(0,s);for(var x in b.f){for(var y in b.f[x]){b.f.keyup.listen.keyup(function(){if(b.f.keyup.listen.val().length <= s){
$this.css("color", b.f.styles.color_pos);}else{$this.css("color", b.f.styles.color_neg);}$this.updateText(b.f.keyup.listen.val().length, s);});}}},$.fn.updateText = function(counter, max){return this.html("Caratteri: "+counter+"/"+max);},
$(".board-form-group > h4").update(255,{f:{keyup:{listen:$("#answer"),},styles:{color_pos:"#00C200",color_neg:"#C20000"}}});});
</script>
