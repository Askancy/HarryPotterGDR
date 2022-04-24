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

use App\Models\Quest;
use App\Models\Pivot_Quest;
use App\Models\User;
use App\Models\Creature;
use App\Models\Chat;
use App\Models\ChatMessage;


class QuestController extends Controller
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
      $quest	=	Quest::orderby('created_at')->paginate(15);
      return view('admin.pages.quest.index', compact('quest'));
    }

    public function getAdd()
    {
      $user = User::get();
      $creature = Creature::get();

      return view('admin.pages.quest.add', compact('user','creature'));
    }

  public function postAdd(Request $request) {

    $quest = new Quest();

    $quest->id_quest = $request->get('id_quest');
    $quest->id_quest = $request->get('id_maps');
    $quest->id_quest = $request->get('id_maps_prev');
    $quest->id_quest = $request->get('id_maps_prec');
    $quest->id_quest = $request->get('privacy');
    $quest->id_quest = $request->get('status');

    // $quest->save();

    if (empty($request->get('id_maps'))) {

      $chat = new Chat();
      $chat->is_quest = '1';
      $chat->id_quest = $request->get('id_quest');
      $chat->name = $request->get('name');
      $chat->description = $request->get('description');
      $chat->save();
      $chat->slug =  $chat->id.'-'.Str::slug($chat->name, '-');
      $chat->save();
    }

    $id_users = (array)$request->get('id_user');

    foreach ($id_users as $value) {
      $pivot_quest = new Pivot_Quest();
      $pivot_quest->id_quest = $request->get('id_quest');
      $pivot_quest->id_user = $value;
      $pivot_quest->exp = '0';
      $pivot_quest->salary = '0';
      $pivot_quest->save();
    }




    return redirect('admin/chat')->with('success_message', 'Chat creata correttamente!');
  }


    //
    // public function getEdit($IDchat)
    // {
    //   $chat	=	Chat::where('id', $IDchat)->first();
    //   return view('admin.pages.chat.edit', compact('chat'));
    // }
    //
    //
    //   public function postEdit(Request $request, $IDchat) {
    //
    //     $chat = Chat::find($IDchat);
    //
    //     $chat->id_team = $request->get('id_team');
    //     $chat->id_quest = $request->get('id_quest');
    //     $chat->is_quest = $request->get('is_quest');
    //
    //     $chat->name = $request->get('name');
    //     $chat->description = $request->get('description');
    //     $chat->id_team = $request->get('restrinction');
    //
    //     if ($request->hasFile('image')) {
    //       $image = $request->file('image');
    //       $filename = Str::slug($chat->name, '-').'-chat-'.rand().'.'.$image->getClientOriginalExtension();
    //       Image::make($image)->fit(200, 200)->save( public_path('/upload/chat/' . $filename ) );
    //       $user->image = $filename;
    //     }
    //
    //     $chat->save();
    //     $chatID = $chat->id;
    //     $chat->slug = $chatID.'-'.Str::slug($chat->name, '-');
    //     $chat->save();
    //
    //     return redirect('admin/chat')->with('success_message', 'Chat modificata correttamente!');
    //   }



}
