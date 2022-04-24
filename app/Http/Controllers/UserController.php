<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Position;
use App\Models\Shop;
use App\Models\Chat;
use Auth;
use Image;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProfile($slug)
    {
      $user = User::where('slug', $slug)->first();
      $position = Position::where('id_user', $user->id)->orderby('created_at', 'desc')->first();
      if($position->id_maps == "0") {
        $id_shop = Shop::where('id', $position->id_shop)->first();
        $location = $id_shop;
      } else {
        $id_maps = Chat::where('id', $position->id_maps)->first();
        $location = $id_maps;
      }
      return view('front.pages.profile.home', compact('user','location'));
    }
    public function getSetting()
    {
      if (Auth::guest()) {
          return redirect('login');
      }
      $user	=	Auth::user();

      return view('front.pages.profile.setting', compact('user'));
    }

    public function UpdateSettingsProfilo(Request $request){

      $this->validate($request, [
        'avatar'  => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
      ], [

        'avatar.mimes' => "Formato non consentito",
        'avatar.max' => "Immagine troppo grande"
      ]);

      $user	=	Auth::user();

      if ($request->hasFile('avatar')) {
        $avatar = $request->file('avatar');
        $filename = $user->id.'-'.$user->slug.'-avatar.'.$avatar->getClientOriginalExtension();
        Image::make($avatar)->fit(200, 200)->save( public_path('/upload/user/' . $filename ) );
        $user->avatar = $filename;
      }

      $user->email	=		$request->get('email');
      $user->telegram	=		$request->get('telegram');
      $user->biography	=		$request->get('biography');

      $user->save();

      $request->session()->flash('alert-success', 'Impostazioni salvate correttamente!');
      return view('front.pages.profile.setting', array('user' => Auth::user()) );

    }



}
