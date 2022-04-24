<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Config;
use Carbon\Carbon;
use Auth;
use Image;

use App\Models\Chat;


class ChatController extends Controller
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
      $chat	=	Chat::orderby('created_at')->paginate(15);
      return view('admin.pages.chat.index', compact('chat'));
    }


    public function getEdit($IDchat)
    {
      $chat	=	Chat::where('id', $IDchat)->first();
      return view('admin.pages.chat.edit', compact('chat'));
    }


      public function postEdit(Request $request, $IDchat) {

        $chat = Chat::find($IDchat);

        $chat->id_team = $request->get('id_team');
        $chat->id_quest = $request->get('id_quest');
        $chat->is_quest = $request->get('is_quest');

        $chat->name = $request->get('name');
        $chat->description = $request->get('description');
        $chat->id_team = $request->get('restrinction');

        if ($request->hasFile('image')) {
          $image = $request->file('image');
          $filename = Str::slug($chat->name, '-').'-chat-'.rand().'.'.$image->getClientOriginalExtension();
          Image::make($image)->fit(200, 200)->save( public_path('/upload/chat/' . $filename ) );
          $user->image = $filename;
        }

        $chat->save();
        $chatID = $chat->id;
        $chat->slug = $chatID.'-'.Str::slug($chat->name, '-');
        $chat->save();

        return redirect('admin/chat')->with('success_message', 'Chat modificata correttamente!');
      }

      public function getAdd()
      {
        return view('admin.pages.chat.add');
      }

    public function postAdd(Request $request) {

      $chat = new Chat();

      $chat->id_team = $request->get('id_team');
      $chat->id_quest = $request->get('id_quest');
      $chat->is_quest = $request->get('is_quest');

      $chat->name = $request->get('name');
      $chat->description = $request->get('description');
      $chat->id_team = $request->get('id_team');
      $chat->restrinction = $request->get('restrinction');

      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $filename = Str::slug($chat->name, '-').'-chat-'.rand().'.'.$image->getClientOriginalExtension();
        Image::make($image)->fit(200, 200)->save( public_path('/upload/chat/' . $filename ) );
        $user->image = $filename;
      }

    $chat->save();
    $chatID = $chat->id;
    $chat->slug = $chatID.'-'.Str::slug($chat->name, '-');
    $chat->save();


      return redirect('admin/chat')->with('success_message', 'Chat creata correttamente!');
    }

}
