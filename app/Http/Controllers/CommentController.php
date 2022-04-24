<?php
namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use Auth;

use App\Models\User;
use App\Models\Chat;
use App\Models\ChatMessage;

class CommentController extends Controller {

	public function postComment(Request $request, $slug) {

		if(!Auth::guest())	{
			if(empty($request->get('messaggio'))){
				$request->session()->flash('alert-danger', 'Non puoi inviare un messaggio vuoto!');
				return \Redirect::back();
			} else {
				$msg	=	new	ChatMessage();
				$msg->id_maps = $request->get('id_maps');
        $msg->id_user	=	Auth::id();
				$msg->type = $request->get('type');
				$msg->text	=	$request->get('messaggio');
				$msg->id_dest = $request->get('id_dest');


				$msg->save();

        $chat = Chat::where('id', $msg->id_maps)->first();

				return redirect('maps/'.$chat->slug)->with('success_message', 'Commento inviato!');
			}

		}
	}

}
