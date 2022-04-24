<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use Config;
use Carbon\Carbon;
use Auth;
use Image;

use App\Models\Objects;
use App\Models\User;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('isAdmin');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $user	=	User::orderby('created_at')->paginate(15);
      return view('admin.pages.user.index', compact('user'));
    }


    public function getEdit($IDuser)
    {
      $user	=	User::where('id', $IDuser)->first();
      return view('admin.pages.user.edit', compact('user'));
    }


      public function postEdit(Request $request, $IDuser) {

        $user = User::find($IDuser);

          $user->username = $request->get('username');
          $user->name = $request->get('name');
          $user->surname = $request->get('surname');
          $user->group = $request->get('group');

          $user->sex = $request->get('sex');
          $user->mago = $request->get('mago');
          $user->exp = $request->get('exp');
          $user->biography = $request->get('biography');

          $user->team = $request->get('team');
          $user->level = $request->get('level');
          $user->telegram = $request->get('telegram');
          $user->money = $request->get('money');

          $user->email = $request->get('email');

          if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = $request->get('username').'-user-'.rand().'.'.$avatar->getClientOriginalExtension();
            Image::make($avatar)->fit(200, 200)->save( public_path('/upload/user/' . $filename ) );
            $user->image = $filename;
          }
        $user->save();
        return redirect('admin/user')->with('success_message', 'Utente modificato correttamente!');
      }

      public function getAdd()
      {
        return view('admin.pages.user.add');
      }

    public function postAdd(Request $request) {

      $user = new User();
      
      $user->username = $request->get('username');
      $user->name = $request->get('name');
      $user->surname = $request->get('surname');
      $user->group = $request->get('group');

      $user->sex = $request->get('sex');
      $user->mago = $request->get('mago');
      $user->exp = $request->get('exp');
      $user->biography = $request->get('biography');
      $user->birthday = $request->get('year').'-'.$request->get('month').'-'.$request->get('day');
      $user->team = $request->get('team');
      $user->level = $request->get('level');
      $user->telegram = $request->get('telegram');
      $user->money = $request->get('money');

      $user->email = $request->get('email');
      $user->password = Hash::make($request->get('password'));


      if ($request->hasFile('avatar')) {
        $avatar = $request->file('avatar');
        $filename = $request->get('username').'-user-'.rand().'.'.$avatar->getClientOriginalExtension();
        Image::make($avatar)->fit(200, 200)->save( public_path('/upload/user/' . $filename ) );
        $user->image = $filename;
      }
    $user->save();

      $IDuser = $user->id;
      $user->slug = $IDuser.'-'.Str::slug($user->username, '-');
      $user->save();


      return redirect('admin/user')->with('success_message', 'Utente aggiunto correttamente!');
    }

}
