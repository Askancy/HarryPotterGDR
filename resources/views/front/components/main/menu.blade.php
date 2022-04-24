


      <nav id="topNav" class="navbar navbar-expand-md fixed-top navbar-light bg-faded megamenu-bg">
          <a class="navbar-brand" href="{{url('/')}}">{{ config('app.name', 'Laravel') }}</a>
          <button class="navbar-toggler hidden-md-up pull-right" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
              ☰
          </button>
          <div class="collapse navbar-collapse" id="collapsingNavbar">
            <ul class="nav navbar-nav">
                @auth
                  <li class="nav-item dropdown megamenu">
                      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Mappa <span class="caret"></span></a>
                      <div class="dropdown-menu p-3">
                          <div class="row">
                              <ul class="list-unstyled col-md-3 col-sm-6 col-5 megamenu" role="menu">
                                  <h4>Stanze</h4>
                                  @if(Auth::user()->isAdmitted)
                                  @php
                                    $chat = \App\Models\Chat::where('id_team', \Auth::user()->team)->first();
                                  @endphp
                                    <li><a href="{{url('maps/'.$chat->slug)}}">Sala Comune di {{\Auth::user()->team()}}</a></li>
                                  @endif
                              </ul>
                              <ul class="list-unstyled col-md-3 col-sm-6" role="menu">
                                  <li><h4>Luoghi</h4></li>
                                  @php
                                    $chat = \App\Models\Chat::where('id_team', '0')->get();
                                  @endphp
                                  @foreach ($chat as $value)
                                    <li><a href="{{url('maps/'.$value->slug)}}">{{$value->name}}</a></li>
                                  @endforeach
                              </ul>
                              <ul class="list-unstyled col-md-3 col-sm-6" role="menu">
                                  <li><h4>Negozi</h4></li>
                                  @php
                                    $shop = \App\Models\Shop::get();
                                  @endphp
                                  @foreach ($shop as $value)
                                    <li><a href="{{url('shop/'.$value->slug)}}">{{$value->name}}</a></li>
                                  @endforeach
                              </ul>
                          </div>
                      </div>
                  </li>
                  @endauth
                  <li class="nav-item dropdown megamenu">
                      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Community<span class="caret"></span></a>
                      <div class="dropdown-menu p-3">
                          <div class="row">
                              <ul class="list-unstyled col-md-3 col-sm-6 col-5 megamenu" role="menu">
                                <h4>Chi siamo</h4>
                                <li><a href="#">La nostra politica</a></li>
                                <li><a href="#">Il nostro staff</a></li>
                              </ul>
                              <ul class="list-unstyled col-md-3 col-sm-6 col-5 megamenu" role="menu">
                                <h4>Social</h4>
                                <li><a href="{{__('/forum')}}">Forum</a></li>
                              </ul>
                          </div>
                      </div>
                    </li>
              </ul>

              <!-- Right Side Of Navbar -->
              <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                  <a class="nav-link" href="{{ url('support') }}"><i class="fas fa-headset"></i> Assistenza</a></li>
                  <!-- Authentication Links -->
                  @auth
                      <li class="nav-item dropdown">
                          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                              {{ Auth::user()->name }} <span class="caret"></span>
                          </a>

                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ url('profile/'.Auth::user()->slug) }}">{{ __('Profilo') }}</a>
                            <a class="dropdown-item" href="{{ url('profile') }}">{{ __('Impostazioni') }}</a>
                              <a class="dropdown-item" href="{{ route('logout') }}"
                                 onclick="event.preventDefault();
                                               document.getElementById('logout-form').submit();">
                                  {{ __('Logout') }}
                              </a>
                              @if (Auth::user()->group == "2")
                                <a class="dropdown-item" href="{{ url('admin/') }}">{{ __('Amministrazione') }}</a>
                              @endif

                              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                  @csrf
                              </form>
                          </div>
                      </li>
                  @endauth
              </ul>



          </div>
      </nav>
