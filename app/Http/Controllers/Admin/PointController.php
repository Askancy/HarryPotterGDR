<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Config;
use Carbon\Carbon;
use App\Models\Team;
use App\Models\Logs_point;
use App\Models\User;
use Auth;
use Image;

class PointController extends Controller
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
      $logs_point	=	Logs_point::orderby('created_at')->paginate(15);
      return view('admin.pages.point.index', compact('logs_point'));
    }

    public function getAdd()
    {
      $user = User::get();
      $team = Team::get();
      return view('admin.pages.point.add', compact('user','team'));
    }

    public function postAdd(Request $request) {

      $logs_point = new Logs_point();

      $logs_point->id_team = $request->get('id_team');
      $logs_point->point = $request->get('point');
      $logs_point->positive = $request->get('positive');
      $logs_point->id_user = $request->get('id_user');
      $logs_point->motivation = $request->get('motivation');

      $logs_point->save();

      $punti = Team::where('id',$request->get('id_team'))->first();
      if ($request->get('positive') == '0') {
        $punti_agg = $punti->point + $request->get('point');
      } else {
        $punti_agg = $punti->point - $request->get('point');
      }

      Team::where('id', $request->get('id_team'))->update(['point' => $punti_agg]);


      return redirect('admin/point')->with('success_message', 'Punti assegnati correttamente!');
    }



    public function getEdit($IDPoint)
    {
      $logs_point	=	Logs_point::where('id', $IDPoint)->first();
      $user = User::get();
      $team = Team::get();
      return view('admin.pages.point.edit', compact('logs_point','user','team'));
    }


      public function postEdit(Request $request, $IDPoint) {

        $logs_point = Logs_point::find($IDPoint);

        $logs_point->id_team = $request->get('id_team');
        $logs_point->point = $request->get('point');
        $logs_point->positive = $request->get('positive');
        $logs_point->id_user = $request->get('id_user');
        $logs_point->motivation = $request->get('motivation');

        $logs_point->save();

        $punti = Team::where('id',$request->get('id_team'))->first();
        if ($request->get('positive') == '0') {
          $punti_agg = $punti->point + $request->get('point');
        } else {
          $punti_agg = $punti->point - $request->get('point');
        }

        Team::where('id', $request->get('id_team'))->update(['point' => $punti_agg]);

        return redirect('admin/point')->with('success_message', 'Punti modificati correttamente!');
      }

}
