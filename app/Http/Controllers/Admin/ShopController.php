<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Config;
use Carbon\Carbon;
use App\Models\Shop;
use Auth;
use Image;

class ShopController extends Controller
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
      $shop	=	Shop::orderby('created_at')->paginate(15);
      return view('admin.pages.shop.index', compact('shop'));
    }


    public function getEdit($IDShop)
    {
      $shop	=	Shop::where('id', $IDShop)->first();
      return view('admin.pages.shop.edit', compact('shop'));
    }


      public function postEdit(Request $request, $IDShop) {

        $shop = Shop::find($IDShop);

        $shop->name = $request->get('name');
        $shop->description = $request->get('description');
        $shop->color = $request->get('color');
        $shop->style = $request->get('style');

        if ($request->hasFile('image')) {
          $image = $request->file('image');
          $filename = Str::slug($request->get('name'), '-').'-image-shop-'.rand().'.'.$image->getClientOriginalExtension();
          Image::make($image)->fit(200, 200)->save( public_path('/upload/shop/' . $filename ) );
          $shop->image = $filename;
        }
        if ($request->hasFile('background')) {
          $background = $request->file('background');
          $filename = Str::slug($request->get('name'), '-').'-background-shop-'.rand().'.'.$background->getClientOriginalExtension();
          Image::make($background)->fit(1150, 300)->save( public_path('/upload/shop/' . $filename ) );
          $shop->background = $filename;
        }

      $shop->save();

      $shopID = $shop->id;
      $shop->slug = $shopID.'-'.Str::slug($shop->name, '-');
      $shop->save();

        return redirect('admin/shop')->with('success_message', 'Negozio modificata correttamente!');
      }

      public function getAdd()
      {
        return view('admin.pages.shop.add');
      }

    public function postAdd(Request $request) {

      $shop = new Shop();

      $shop->name = $request->get('name');
      $shop->description = $request->get('description');
      $shop->color = $request->get('color');
      $shop->style = $request->get('style');

      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $filename = Str::slug($request->get('name'), '-').'-image-shop-'.rand().'.'.$image->getClientOriginalExtension();
        Image::make($image)->fit(200, 200)->save( public_path('/upload/shop/' . $filename ) );
        $shop->image = $filename;
      }
      if ($request->hasFile('background')) {
        $background = $request->file('background');
        $filename = Str::slug($request->get('name'), '-').'-background-shop-'.rand().'.'.$background->getClientOriginalExtension();
        Image::make($background)->fit(1150, 300)->save( public_path('/upload/shop/' . $filename ) );
        $shop->background = $filename;
      }

    $shop->save();

    $shopID = $shop->id;
    $shop->slug = $shopID.'-'.Str::slug($shop->name, '-');
    $shop->save();


      return redirect('admin/shop')->with('success_message', 'Negozio creato correttamente!');
    }

}
