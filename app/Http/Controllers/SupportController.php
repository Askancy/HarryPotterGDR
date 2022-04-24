<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketMessage;

use Auth;

class SupportController extends Controller
{
  public function __construct(){
    //$this->middleware('auth');
  }

  public function index(){
    if(Auth::guest()){
      return view('front.pages.support.home');
    }else{
      $tickets = Ticket::where('id_user', Auth::user()->id)->orderBy('last_msg','desc')->get();
      return view('front.pages.support.home', compact('tickets'));
    }
  }

  public function getNewTicket(){
    return view('front.pages.support.new_ticket');
  }

  public function postNewTicket(Request $request){
    $this->validate($request,[
      'name'  =>  'required',
      'text'  =>  'required|max:255',
    ],[
      'name.required' =>  'Il titolo del ticket è obbligatorio',
      'text.required' =>  'Il testo del ticket è obbligatorio',
      'text.max'  =>  'Il testo è di massimo 255 caratteri',
    ]);
    $ticket = new Ticket();
    $ticket->name = $request->input('name');
    $ticket->id_user = Auth::user()->id;
    $ticket->status = 0;
    $ticket->save();

    $ticket_msg = new TicketMessage();
    $ticket_msg->id_user = Auth::user()->id;
    $ticket_msg->id_ticket = $ticket->id;
    $ticket_msg->text = $request->input('text');
    $ticket_msg->first = 1;
    $ticket_msg->read = 1;
    $ticket_msg->save();

    $ticket->slug = $ticket->id.'-'.Str::slug($ticket->name,'-');
    $ticket->last_msg = $ticket_msg->created_at;
    $ticket->save();

    return redirect('/support');
  }

  public function getViewTicket($slug){
    $ticket = Ticket::where('slug',$slug)->first();
    if($ticket->id_user == Auth::user()->id || Auth::user()->group > 0){
      $ticket_msg = TicketMessage::where('id_ticket',$ticket->id)->get();
      //Reader
      $last_message = TicketMessage::orderBy('created_at','desc')->first();
      if($ticket->id_user == Auth::user()->id && $last_message->read == 0){
        $last_message->read = 1;
        $last_message->save();
        $ticket->last_msg = $last_message->updated_at;
        $ticket->save();
      }
      //______
      return view('front.pages.support.ticket',compact('ticket','ticket_msg','last_message'));
    }else{
      return redirect('/support');
    }
  }

  public function postAnswerTicket(Request $request, $slug){
    $this->validate($request,[
      'text'  =>  'required|max:255',
    ],[
      'text.required' =>  'Il testo del ticket è obbligatorio',
      'text.max'  =>  'Il testo è di massimo 255 caratteri',
    ]);

    $ticket = Ticket::where('slug',$slug)->first();
    $ticket_msg = new TicketMessage();
    $ticket_msg->id_user = Auth::user()->id;
    $ticket_msg->id_ticket = $ticket->id;
    $ticket_msg->text = $request->input('text');
    $ticket_msg->first = 0;
    if($ticket->id_user == Auth::user()->id){
      $ticket_msg->read = 1;
    }else{
      $ticket_msg->read = 0;
    }
    $ticket_msg->save();

    $ticket->last_msg = $ticket_msg->created_at;
    $ticket->save();
    return redirect('/support/ticket/view/'.$ticket->slug.'#'.$ticket_msg->id);
  }

  // Management
  public function getTicketManagement(){
    $get_tickets = Ticket::orderBy('created_at','desc')
    ->orderBy('status','asc')
    ->paginate(5);
    return view('front.pages.support.management',compact('get_tickets'));
  }

  public function lockTicket($id){
    $ticket = Ticket::find($id);
    $ticket->status = 1; // Chiudo il ticket
    $ticket->save();
    return redirect('/support/ticket/management');
  }

}
