<select class="form-control" onchange="location=this.value;">
  <option selected>Menu Moderazione</option>
  <option value="{{__('/forum/topic/'.$topic->slug.'/mods/delete')}}">@if($topic->deleted) Ripristina Topic @else Sposta nel cestino @endif</option>
  <option value="{{__('/forum/topic/'.$topic->slug.'/mods/close')}}">@if($topic->locked) Apri Topic @else Chiudi Topic @endif</option>
  <option value="{{__('/forum/topic/'.$topic->slug.'/mods/notable')}}">@if($topic->notable) Togli dalla bacheca @else Metti in bacheca @endif</option>
</select>
