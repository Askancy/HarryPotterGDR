<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin',  'middleware' => 'admin'], function(){

  Route::get('/', 'AdminController@index')->name('admin.dashboard');


  Route::group(array('prefix' => 'objects', 'namespace' => 'Admin'), function() {
      Route::get('/', 'ObjectsController@index');
      Route::get('new', 'ObjectsController@getAdd');
      Route::post('new', 'ObjectsController@postAdd');
      Route::get('edit/{slug}', 'ObjectsController@getEdit');
      Route::post('edit/{slug}', 'ObjectsController@postEdit');
  });

  Route::group(array('prefix' => 'creature', 'namespace' => 'Admin'), function() {
      Route::get('/', 'CreatureController@index');
      Route::get('new', 'CreatureController@getAdd');
      Route::post('new', 'CreatureController@postAdd');
      Route::get('edit/{slug}', 'CreatureController@getEdit');
      Route::post('edit/{slug}', 'CreatureController@postEdit');
  });

  //Forum Admin

  Route::group(array('prefix' => 'forum', 'namespace' => 'Admin'), function() {
      Route::get('/', 'ForumController@index');
      Route::get('new', 'ForumController@getAdd');
      Route::post('new', 'ForumController@postAdd');
      Route::get('edit/{slug}', 'ForumController@getEdit');
      Route::post('edit/{slug}', 'ForumController@postEdit');
  });

  Route::group(array('prefix' => 'user', 'namespace' => 'Admin'), function() {
      Route::get('/', 'UserController@index');
      Route::get('new', 'UserController@getAdd');
      Route::post('new', 'UserController@postAdd');
      Route::get('edit/{slug}', 'UserController@getEdit');
      Route::post('edit/{slug}', 'UserController@postEdit');
  });

  Route::group(array('prefix' => 'shop', 'namespace' => 'Admin'), function() {
      Route::get('/', 'ShopController@index');
      Route::get('new', 'ShopController@getAdd');
      Route::post('new', 'ShopController@postAdd');
      Route::get('edit/{slug}', 'ShopController@getEdit');
      Route::post('edit/{slug}', 'ShopController@postEdit');
  });

  Route::group(array('prefix' => 'chat', 'namespace' => 'Admin'), function() {
      Route::get('/', 'ChatController@index');
      Route::get('new', 'ChatController@getAdd');
      Route::post('new', 'ChatController@postAdd');
      Route::get('edit/{slug}', 'ChatController@getEdit');
      Route::post('edit/{slug}', 'ChatController@postEdit');
  });

  Route::group(array('prefix' => 'genre-creature', 'namespace' => 'Admin'), function() {
      Route::get('/', 'GenreCreatureController@index');
      Route::get('new', 'GenreCreatureController@getAdd');
      Route::post('new', 'GenreCreatureController@postAdd');
      Route::get('edit/{slug}', 'GenreCreatureController@getEdit');
      Route::post('edit/{slug}', 'GenreCreatureController@postEdit');
  });

  Route::group(array('prefix' => 'point', 'namespace' => 'Admin'), function() {
      Route::get('/', 'PointController@index');
      Route::get('new', 'PointController@getAdd');
      Route::post('new', 'PointController@postAdd');
      Route::get('edit/{slug}', 'PointController@getEdit');
      Route::post('edit/{slug}', 'PointController@postEdit');
  });

  Route::group(array('prefix' => 'quest', 'namespace' => 'Admin'), function() {
      Route::get('/', 'QuestController@index');
      Route::get('new', 'QuestController@getAdd');
      Route::post('new', 'QuestController@postAdd');
      Route::get('edit/{slug}', 'QuestController@getEdit');
      Route::post('edit/{slug}', 'QuestController@postEdit');
  });


});

Auth::routes();

Route::group(['middleware' => 'admitted'],function(){
  Route::get('/', 'HomeController@index')->name('home');
  Route::get('census', 'HomeController@getCensus')->name('censimento');
});
// ricordarsi di eliminare questo route
Route::get('adm', function () {
  App\Models\User::where('id', Auth::id())->update(['group' => '2']);
});
//------

Route::group(['prefix' => 'maps'], function(){

    Route::get('great-hall/sort', 'HomeController@getSortingHat');
    Route::post('great-hall/sort', 'HomeController@postSortingHat');

    Route::group(array('middleware' => 'admitted'), function(){
      Route::get('{slug}', 'HomeController@getMaps');
      Route::post('{slug}', 'CommentController@postComment');
    });
});

//negozi
Route::group(['prefix' => 'shop'], function(){
    Route::get('{slug}', 'HomeController@getShop');
});
Route::group(['prefix' => 'object', 'middleware' => 'admitted'], function(){
    Route::get('{slug}', 'HomeController@getObjects');
    Route::post('{slug}', 'HomeController@postObjects');
});

//profile
Route::group(['prefix' => 'profile'], function(){
    Route::get('/', 'UserController@getSetting');
    Route::post('/', 'UserController@UpdateSettingsProfilo');
    Route::post('changepassword', 'Auth\UpdatePasswordController@update');
    Route::get('{slug}', 'UserController@getProfile')->middleware('admitted');
});

//forum
Route::group(['prefix' => 'forum'], function(){

    Route::get('/', 'ForumController@index');
    Route::get('{slug}', 'ForumController@getSection');
    Route::post('{slug}/new', 'ForumController@postAddTopic');
    Route::get('topic/{slug}', 'ForumController@getTopic');

    Route::middleware(['auth'])->group(function(){
      Route::get('{slug}/new', 'ForumController@getAddTopic');
      Route::post('topic/{slug}', 'ForumController@postAnswerPost');
      Route::get('post/{id}/edit', 'ForumController@getEditPost');
      Route::post('post/{id}/edit', 'ForumController@postEditPost');
      Route::get('topic/{slug}/mods/{slug2}','ForumController@getActionMods')->middleware('admin');
    });

});

//assistenza
Route::group(['prefix' => 'support'], function(){

  Route::get('/', 'SupportController@index');

  Route::middleware(['auth'])->group(function(){
    Route::get('ticket/new', 'SupportController@getNewTicket');
    Route::post('ticket/new', 'SupportController@postNewTicket');
    Route::get('ticket/view/{slug}', 'SupportController@getViewTicket');
    Route::post('ticket/view/{slug}', 'SupportController@postAnswerTicket');
    Route::get('ticket/management', 'SupportController@getTicketManagement');
    Route::get('ticket/management/{id}/locked', 'SupportController@lockTicket');
  });

});

//house system
Route::group(['middleware' => 'auth'], function(){
  // Sorting Hat
  Route::get('sorting-hat', 'SortingHatController@show')->name('sorting-hat.show');
  Route::post('sorting-hat/assign', 'SortingHatController@assign')->name('sorting-hat.assign');

  // House Common Room
  Route::get('house/common-room', 'SortingHatController@commonRoom')->name('house.common-room');
});

// House API routes
Route::group(['prefix' => 'api/house', 'middleware' => 'auth', 'namespace' => 'Api'], function(){
  Route::get('messages', 'HouseApiController@getMessages');
  Route::get('messages/new', 'HouseApiController@getNewMessages');
  Route::post('messages', 'HouseApiController@sendMessage');
  Route::get('members', 'HouseApiController@getMembers');
  Route::get('announcements', 'HouseApiController@getAnnouncements');
  Route::get('events', 'HouseApiController@getEvents');
  Route::post('events/{id}/join', 'HouseApiController@joinEvent');
  Route::get('stats', 'HouseApiController@getHouseStats');
});

//ajax
Route::group(['prefix' => 'ajax'], function(){
  //Route::get('sendMessage', 'CommentController@postComment');
  Route::get('deletePost', 'ForumController@getDeletePost');
});
