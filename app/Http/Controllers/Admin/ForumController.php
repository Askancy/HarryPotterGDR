<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use App\Models\ForumCategory;
use App\Models\ForumSection;

class ForumController extends Controller
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
   public function index(){
     $forum = ForumSection::orderBy('created_at')->paginate(15);
     return view('admin.pages.forum.index',compact('forum'));
   }

   public function getAdd(){
     $categories = ForumCategory::get();
     return view('admin.pages.forum.add',compact('categories'));
   }

   public function postAdd(Request $request){
     //Creo una nuova categoria sse
     if(!empty($request->input('categ-name'))){
       $categ = new ForumCategory();
       $categ->name = $request->input('categ-name');
       $categ->save();
     }
     $section = new ForumSection();
     $section->id_category = !empty($request->input('categ-name'))?$categ->id:$request->input('categ-id');
     $section->name = $request->input('name');
     $section->description = $request->input('description');
     $section->icon = $request->input('icon');
     $section->ordered = $request->input('orderby');
     $section->status = $request->input('status');
     $section->save();
     $section->slug = $section->id.'-'.str_slug($request->input('name'),'-');
     $section->save();
     return redirect('/admin/forum');
   }

   public function getEdit($id){
     $section = ForumSection::find($id);
     $categ = ForumCategory::where('id', $section->id_category)->first();
     return view('admin.pages.forum.edit',compact('section','categ'));
   }

   public function postEdit($id, Request $request){
     $section = ForumSection::find($id);
     $section->name = $request->input('name');
     $section->description = $request->input('description');
     $section->icon = $request->input('icon');
     $section->ordered = $request->input('orderby');
     $section->status = $request->input('status');
     if(!empty($request->input('name'))){
       //Cambio lo slug della Sezione
       $section->slug = $section->id.'-'.str_slug($request->input('name'),'-');
     }
     $section->save();
     return redirect('/admin/forum');
   }
}
