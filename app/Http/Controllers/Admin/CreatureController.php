<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Config;
use Carbon\Carbon;
use App\Models\Creature;
use App\Models\Genre;
use Auth;
use Image;

class CreatureController extends Controller
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
      $creature	=	Creature::orderby('created_at')->paginate(15);
      return view('admin.pages.creature.index', compact('creature'));
    }


    public function getEdit($IDCreature)
    {
      $creature	=	Creature::where('id', $IDCreature)->first();
      $genre = Genre::get();
      return view('admin.pages.creature.edit', compact('creature','genre'));
    }


      public function postEdit(Request $request, $IDCreature) {

        $creature = Creature::find($IDCreature);

          $creature->name = $request->get('name');
          $creature->description = $request->get('description');
          $creature->genre = $request->get('genre');
          $creature->level = $request->get('level');
          $creature->hp = $request->get('hp');
          $creature->dmg = $request->get('dmg');

          if ($request->hasFile('image')) {
            $avatar = $request->file('image');
            $filename = $request->get('name').'-creature-'.rand().'.'.$avatar->getClientOriginalExtension();
            Image::make($avatar)->fit(200, 200)->save( public_path('/upload/creature/' . $filename ) );
            $creature->image = $filename;
          }


        $creature->save();
        return redirect('admin/creature')->with('success_message', 'Creatura modificata correttamente!');
      }

      public function getAdd()
      {
        $genre = Genre::get();
        return view('admin.pages.creature.add', compact('genre'));
      }

    public function postAdd(Request $request) {

      $creature = new Creature();

      $creature->name = $request->get('name');
      $creature->description = $request->get('description');
      $creature->genre = $request->get('genre');
      $creature->level = $request->get('level');
      $creature->hp = $request->get('hp');
      $creature->dmg = $request->get('dmg');

      if ($request->hasFile('image')) {
        $avatar = $request->file('image');
        $filename = $request->get('name').'-creature-'.rand().'.'.$avatar->getClientOriginalExtension();
        Image::make($avatar)->fit(200, 200)->save( public_path('/upload/creature/' . $filename ) );
        $creature->image = $filename;
      }

      $creature->save();

      $creatureID = $creature->id;
      $creature->slug = $creatureID.'-'.Str::slug($creature->name, '-');
      $creature->save();


      return redirect('admin/creature')->with('success_message', 'Creatura creata correttamente!');
    }

}
