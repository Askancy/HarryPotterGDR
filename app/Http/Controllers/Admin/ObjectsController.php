<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Config;
use Carbon\Carbon;
use App\Models\Objects;
use Illuminate\Support\Str;

use Auth;
use Image;

class ObjectsController extends Controller
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
      $obj	=	Objects::orderby('created_at')->paginate(15);
      return view('admin.pages.objects.index', compact('obj'));
    }


    public function getEdit($IDobj)
    {
      $obj	=	Objects::where('id', $IDobj)->first();
      return view('admin.pages.objects.edit', compact('obj'));
    }


      public function postEdit(Request $request, $IDobj) {

        $obj = Objects::find($IDobj);

          $obj->name = $request->get('name');
          $obj->description = $request->get('description');
          $obj->type = $request->get('type');
          $obj->price = $request->get('price');
          $obj->id_shop = $request->get('id_shop');

          if ($request->hasFile('image')) {
            $avatar = $request->file('image');
            $filename = $request->get('name').'-obj-'.rand().'.'.$avatar->getClientOriginalExtension();
            Image::make($avatar)->fit(200, 200)->save( public_path('/upload/obj/' . $filename ) );
            $obj->image = $filename;
          }
        $obj->save();
        $objID = $obj->id;
        $obj->slug = $objID.'-'.Str::slug($obj->name, '-');
        $obj->save();

        return redirect('admin/objects')->with('success_message', 'Oggetto modificato correttamente!');
      }

      public function getAdd()
      {
        return view('admin.pages.objects.add');
      }

    public function postAdd(Request $request) {

      $obj = new Objects();

      $obj->name = $request->input('name');
      $obj->description = $request->input('description');
      $obj->type = $request->input('type');
      $obj->price = $request->input('price');
      $obj->id_shop = $request->input('id_shop');

      if ($request->hasFile('image')) {
        $avatar = $request->file('image');
        $filename = $request->get('name').'-obj-'.rand().'.'.$avatar->getClientOriginalExtension();
        Image::make($avatar)->fit(200, 200)->save( public_path('/upload/obj/' . $filename ) );
        $obj->image = $filename;
      }

      $obj->save();

      $objID = $obj->id;
      $obj->slug = $objID.'-'.Str::slug($obj->name, '-');
      $obj->save();


      return redirect('admin/objects')->with('success_message', 'Oggetto aggiunto correttamente!');
    }

}
