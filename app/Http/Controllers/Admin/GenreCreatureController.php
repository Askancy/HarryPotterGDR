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

class GenreCreatureController extends Controller
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
      $genre	=	Genre::orderby('created_at')->paginate(15);
      return view('admin.pages.genre.index', compact('genre'));
    }


    public function getEdit($IDGenre)
    {
      $genre	=	Genre::where('id', $IDGenre)->first();
      return view('admin.pages.genre.edit', compact('creature','genre'));
    }


      public function postEdit(Request $request, $IDGenre) {

        $genre = Genre::find($IDGenre);
        $genre->name = $request->get('name');
        $creature->save();
        $genreID = $genre->id;
        $genre->slug = $genreID.'-'.Str::slug($genre->name, '-');
        $genre->save();

        return redirect('admin/genre-creature')->with('success_message', 'Genere creatura modificata correttamente!');
      }

      public function getAdd()
      {
        $genre = Genre::get();
        return view('admin.pages.genre.add', compact('genre'));
      }

    public function postAdd(Request $request) {

      $genre = new Genre();

      $genre->name = $request->get('name');


      $genre->save();

      $genreID = $genre->id;
      $genre->slug = $genreID.'-'.Str::slug($genre->name, '-');
      $genre->save();


      return redirect('admin/genre-creature')->with('success_message', 'Genere creatura creata correttamente!');
    }

}
