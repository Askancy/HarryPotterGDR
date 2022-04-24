<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Validator;
use Carbon\Carbon;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Models\ForumCategory;
use App\Models\ForumSection;
use App\Models\ForumTopic;
use App\Models\ForumPost;

class ForumController extends Controller
{
    public function __construct(){
      //
    }

    public function index(){
      $categories = ForumCategory::orderBy('ordered','asc')->get();
      return view('front.pages.forum.home',compact('categories'));
    }

    public function getSection($slug){
      $section = ForumSection::where('slug', $slug)->first();
      $category = ForumCategory::where('id', $section->id_category)->first();
      return view('front.pages.forum.section',compact('section','category'));
    }

    public function getAddTopic($slug){
      $section = ForumSection::where('slug', $slug)->first();
      $category = ForumCategory::where('id', $section->id_category)->first();
      if(Auth::user()->group == 0 && $section->status){
        return redirect('/forum/'.$section->slug);
      }else{
        return view('front.pages.forum.new_topic',compact('section','category'));
      }
    }

    public function postAddTopic(Request $request, $slug){
      $section = ForumSection::where('slug', $slug)->first();
      $topic = new ForumTopic();
      $topic->name = $request->input('name');
      $topic->id_user = Auth::user()->id;
      $topic->id_section = $section->id;
      $topic->save();
      $topic->slug = $topic->id.'-'.Str::slug($topic->name, '-');
      $topic->save();
        /// \\\
      ///----\\\
    ///       \\\
      $post = new ForumPost();
      $post->id_user = Auth::user()->id;
      $post->id_topic = $topic->id;
      $post->id_section = $section->id;
      $post->text = $request->input('text');
      $post->first = "1";
      $post->save();
      session()->flash('alert-success', 'Nuovo topic creato con successo');
      return redirect('/forum/topic/'.$topic->slug);
    }

    public function getTopic($slug){
      if(Auth::user() && Auth::user()->group > 0) {
        $topic = ForumTopic::where('slug', $slug)->first();
      }else{
        $topic = ForumTopic::where('slug', $slug)->where('deleted', 0)->first();
      }
      $section = ForumSection::where('id',$topic->id_section)->first();
      $category = ForumCategory::where('id',$section->id_category)->first();
      $posts = ForumPost::where('id_topic',$topic->id)->paginate(6);
        // Quante persone hanno guardato questa pagina
        if (!\Session::has($slug)) {
    		    ForumTopic::where('slug', $slug)->increment('count_view');
    		    \Session::put($slug, 1);
    		}
      return view('front.pages.forum.topic',compact('topic','section','category','posts'));
    }

    public function postAnswerPost(Request $request, $slug){
      $this->validate($request, [
        'text'  =>  'required|max:255',
      ],[
        'text.required'  =>  "testo richiesto",
        'text.max'  =>  "lunghezza massima del testo consentito di 255 caratteri"
      ]);
      $topic = ForumTopic::where('slug', $slug)->first();
      $post = new ForumPost();
      $post->id_user = Auth::user()->id;
      $post->id_topic = $topic->id;
      $post->id_section = $topic->id_section;
      $post->text = $request->input('text');
      $post->first = "0";
        if(Auth::user()->group > 0 || !$topic->locked)
          $post->save();
      $topic->last_msg = $post->created_at;
      $topic->save();
      session()->flash('alert-success', 'Risposta inviata con successo');
      return redirect()->back();
    }

    public function getEditPost($id){
      $post = ForumPost::where('id',$id)->first();
      $topic = ForumTopic::where('id',$post->id_topic)->first();
      $section = ForumSection::where('id', $post->id_section)->first();
      $category = ForumCategory::where('id', $section->id_category)->first();
      //   /\
      //  //\\
      // ///\\\
      $now = Carbon::now()->format('Y-m-d H:i:s');
      $startTime = Carbon::parse($now);
      $finishTime = Carbon::parse($post->created_at->format('Y-m-d H:i:s'));
      $totalDuration = $startTime->diffInSeconds($finishTime);
      // \\\///
      //  \\//
      //   \/
      if(($post->id_user != Auth::user()->id || $totalDuration >= 300) && Auth::user()->group == 0){
        session()->flash('alert-danger', 'Impossibile modificare questo post');
        return redirect('/forum/topic/'.$topic->slug);
      }
      if($post->first){
        return view('front.pages.forum.edit_topic',compact('post','topic','section','category'));
      }else{
        return view('front.pages.forum.edit_post',compact('post','topic','section','category'));
      }
    }

    public function postEditPost(Request $request, $id){
      $this->validate($request, [
        'text'  =>  'required|max:255',
      ],[
        'text.required'  =>  "testo richiesto",
        'text.max'  =>  "lunghezza massima del testo consentito di 255 caratteri"
      ]);
      // Modifico il post
      $post = ForumPost::find($id); // Cerco le informazioni
      $post->text = $request->input('text');
      $post->save();
      if($post->first){ // Il post da modificare è il principale
        // Modifico il Topic
        $topic = ForumTopic::find($post->id_topic);
        $topic->name = $request->input('name');
        $topic->save();
      }

      $request->session()->flash('alert-success', 'Post modificato con successo');
      return redirect($request->input('uri'));
    }

    public function getReportPost(){
      if(request()->json()){
        $post_id = request()->input('id');
        $post_text = request()->input('text') ? request()->input('text') : '';
        if($post_id != Auth::user()->id){
          //....
          $report = new \App\Models\ForumReport();
          $report->user_id = Auth::user()->id;
          $report->post_id = $post_id;
          $report->status = "0";
          $report->text = $post_text;
          $report->save();
          //....
          $response = array(
            'status'  =>  'alert-success',
            'msg'     =>  'Hai inviato la segnalazione. Ti ringraziamo per il tuo contributo',
          );
        }else{
          $response = array(
            'status'  =>  'alert-danger',
            'msg'     =>  'Impossibile segnalare un proprio post',
          );
        }
      return response()->json($response);
      }
    }

    public function getDeletePost(){
      if(request()->json()){
      $post = ForumPost::find(request()->input('id'));
      $now = Carbon::now()->format('Y-m-d H:i:s');
      $startTime = Carbon::parse($now);
      $finishTime = Carbon::parse($post->created_at->format('Y-m-d H:i:s'));
      $totalDuration = $startTime->diffInSeconds($finishTime);
      // falso ||  (vero || falso = vero) = Vero && falso = falso
      if($post->first || ($post->id_user != Auth::user()->id || $totalDuration >= 300) && Auth::user()->group == 0){
        $response = array(
          'status'  =>  'alert-danger',
          'msg' =>  'Impossibile eliminare questo post',
        );
      }else{
        $response = [
          'status'  =>  'alert-success',
          'msg' =>  'Post eliminato con successo',
        ];
        $post->delete();
      }
        return response()->json($response);
      }
    }

    public function getActionMods($slug,$action){
      $topic = ForumTopic::where('slug',$slug)->first();
      if($action == "delete") $topic->update(['deleted' => !($topic->deleted)]);
      elseif($action == "close") $topic->update(['locked' => !($topic->locked)]);
      elseif($action == "notable") $topic->update(['notable' => !($topic->notable)]);
      return redirect()->back();
    }
}
