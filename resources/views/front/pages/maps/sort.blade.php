@extends('front.layouts.app')

@section('title', "Sala Grande")

@section('styles')
<style type="text/css">
.question-form {
  margin-left: 30px;
}
.question-group {
  display: block;
  width: 100%;
}
.answer-list {
  margin-left: 24px;
}
.answer-list input {
  transform: scale(1.6);
}
.answer-list label {
  padding-left: 5px;
}
</style>
@endsection
@section('content')
<div class="container rc sk-container">
  <div class="row justify-content-center" style="margin-bottom: 35px">

    <div class="col-12">
      <h2>Benvenuto nella Sala Grande</h2>
      <p>Rispondi a tutte e 9 le domande per procedere allo smistamento</p>

      {{ Form::open() }}
        <div class="question-group">
          @foreach($sorting as $d => $value)
          <h2>{{$value->name}}</h2>
            @php
              $answers = App\Models\PollAnswers::where('id_question',$value->id)->inRandomOrder()->get();
            @endphp
            @foreach($answers as $answer)
            <div id="question[{{$value->id}}]" class="answer-list">
              <input type="hidden" name="question[{{$d}}]" value="{{$value->id}}"/>
              <input type="checkbox" class="question-checkbox" name="answer[{{$d}}]" value="{{$answer->id}}"/>
              <label>{{$answer->name}}</label>
            </div>
            @endforeach
          @endforeach
          <div class="col-md-6 offset-md-4">
              <button type="submit" class="btn btn-dark">
                {{ __('Prosegui') }}
              </button>
          </div>
        </div>
      {{ Form::close() }}

      <script type="text/javascript">
      $(function(){
        $("input:checkbox").click(function() {
          var $box = $(this);
          if ($box.is(":checked")) {
            var group = "input:checkbox[name='" + $box.attr("name") + "']";
            $(group).prop("checked", false);
            $box.prop("checked", true);
          } else {
            $box.prop("checked", false);
          }
        });
      });
      </script>
    </div>
  </div>
</div>
@endsection
