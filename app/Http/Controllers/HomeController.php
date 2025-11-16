<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Shop;
use App\Models\Objects;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Inventory;
use App\Models\LogsPurchase;
use App\Models\PollQuestions;
use App\Models\PollAnswers;

use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $user_registered = User::count();

      if (Auth::guest()) {
          return view('front.home', compact('user_registered'));
      } else {
        $user = User::where('id', Auth::user()->id)->first();
        return view('front.pages.maps.home', compact('user_registered','user'));
      }
    }

    // Se sei nuovo vieni indirizzato davanti al cappello parlante
    public function getSortingHat(){
      // Verifica che l'utente sia loggato
      if (!Auth::check()) {
        return redirect()->route('login')->with('alert-error', 'Devi effettuare il login per accedere al Cappello Parlante.');
      }

      // Se l'utente è già stato smistato, reindirizza alla home
      if (Auth::user()->team) {
        return redirect('/')->with('alert-info', 'Sei già stato smistato!');
      }

      $sorting = PollQuestions::inRandomOrder()->limit(10)->get();
      return view('front.pages.maps.sort', compact('sorting'));
    }

    public function postSortingHat(Request $request){
      // Verifica che l'utente sia loggato
      if (!Auth::check()) {
        return redirect()->route('login')->with('alert-error', 'Devi effettuare il login per essere smistato.');
      }

      // Verifica che l'utente non sia già stato smistato
      if (Auth::user()->team) {
        return redirect('/')->with('alert-error', 'Sei già stato smistato in una casa!');
      }

      $questions = $request->input('question', []);
      $answers = $request->input('answer', []);

      if (empty($questions) || empty($answers)) {
        return redirect()->back()->with('alert-error', 'Devi rispondere a tutte le domande del Cappello Parlante.');
      }

      $points = 0;
      for($p = 0; $p < count($questions); $p++){
        $answer = PollAnswers::where('id_question',$questions[$p])->where('id',$answers[$p])->first();
        if ($answer) {
          $points += $answer->value;
        }
      }
      $quests = count($questions);
      if($points <= $quests){
        $casa = "Grifondoro";
        $id_casa = 1;
      }if($points > $quests){
        $casa = "Serpeverde";
        $id_casa = 2;
      }if($points > 2*$quests){
        $casa = "Tassorosso";
        $id_casa = 3;
      }if($points > 3*$quests){
        $casa = "Corvonero";
        $id_casa = 4;
      }

      $request->session()->flash('alert-success',Auth::user()->username.', benvenuto nei '.$casa);
      // Aggiorno il team dell'utente
      $user = User::where('id',Auth::user()->id)->first();
      $user->isAdmitted = "1";
      $user->team = $id_casa;
      $user->save();
      return redirect('/');
      //return view('front.pages.maps.sorting',compact('casa'));
    }

    public function getShop($slug)
    {
      $shop = Shop::where('slug', $slug)->first();
      $object = Objects::where('id_shop', $shop->id)->get();
      return view('front.pages.shop.index1', compact('shop','object'));
    }

    public function getMaps($slug)
    {
      $chat = Chat::where('slug', $slug)->first();

      //select Chat
      $mess_chat = ChatMessage::where('id_maps', $chat->id)->get();

      if (Auth::guest()) {
        \Session::flash('flash_message','Devi registrarti per accedere alle pagine del GDR'); //<--FLASH MESSAGE
        return redirect('/');
      } else {
          if($chat->id_team == '0') {
            return view('front.pages.maps.index', compact('chat','mess_chat'));
          } elseif ($chat->id_team == Auth::user()->team) {
            return view('front.pages.maps.index', compact('chat','mess_chat'));
          } else {
            \Session::flash('flash_message','Non puoi entrare nella sala comune di un\'altra casata!'); //<--FLASH MESSAGE
            return redirect('/');
          }
      }

    }

    public function getCensus()
    {
      $user = User::with('house')->get();

      // return $user;
      return view('front.pages.profile.census', compact('user'));
    }

    public function getObjects($slug)
    {
      $object = Objects::where('slug', $slug)->first();

      return view('front.pages.objects.index', compact('object'));
    }

    public function postObject(Request $request, $slug)
    {
      $object = Objects::where('id', $request->input('id_obj'))->first();

      $rest = Auth::user()->money - $object->price;

      if($rest >= "0" || Auth::user()->money > $object->price) {
        $logpurchase = new  LogsPurchase();

        $logpurchase->id_user = Auth::id();
        $logpurchase->id_obj = $request->input('id_obj');
        $logpurchase->money = $object->price;
        $logpurchase->save();
        $inventory = new Inventory();
        $inventory->id_user = Auth::id();
        $inventory->id_obj = $request->input('id_obj');
        $inventory->save();
        #I update the user's money in the user table
        User::where('id', Auth::id())->update(['money' => $rest]);

        return redirect()->back()->with('message', 'IT WORKS!');
      }

      return view('front.pages.objects.index', compact('object'));
    }

    // public function postPurchase(Request $request)	{
    //     #I take the item to buy
    //       $object = Objects::where('id', $request->input('id_obj'))->first();
    //       #I check the money that is left to the user after the purchase
    //       #I look at if the user has the money for the purchase
    //       $rest = Auth::user()->money - $object->price;
    //       if($rest >= "0" || Auth::user()->money > $object->price) {
    //         $logpurchase = new  LogsPurchase();
    //         $logpurchase->id_user = Auth::id();
    //         $logpurchase->id_obj = $request->input('id_obj');
    //         $logpurchase->money = $object->price;
    //         $logpurchase->save();
    //         $inventory = new Inventory();
    //         $inventory->id_user = Auth::id();
    //         $inventory->id_obj = $request->input('id_obj');
    //         $inventory->save();
    //         #I update the user's money in the user table
    //         User::where('id', Auth::id())->update(['money' => $rest]);
    //         $response = array(
    //           'status' => 'success',
    //           'msg' => $request->message,
    //         );
    //         return response()->json($response);
    //       } else {
    //         $response = array(
    //           'status' => 'error',
    //           'msg' => $request->message,
    //         );
    //         return response()->json($response);
    //       }
  	// }


}
