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

  Route::get('/', 'App\Http\Controllers\AdminController@index')->name('admin.dashboard');


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

  // House Points Admin
  Route::group(array('prefix' => 'house-points', 'namespace' => 'Admin'), function() {
      Route::get('/', 'HousePointsController@index')->name('admin.house-points');
      Route::post('award', 'HousePointsController@award')->name('admin.house-points.award');
      Route::post('bulk-award', 'HousePointsController@bulkAward')->name('admin.house-points.bulk-award');
      Route::post('reset', 'HousePointsController@reset')->name('admin.house-points.reset');
  });

  // Locations Admin
  Route::group(array('prefix' => 'locations', 'namespace' => 'Admin'), function() {
      Route::get('/', 'LocationAdminController@index')->name('admin.locations.index');
      Route::get('create', 'LocationAdminController@create')->name('admin.locations.create');
      Route::post('/', 'LocationAdminController@store')->name('admin.locations.store');
      Route::get('{id}/edit', 'LocationAdminController@edit')->name('admin.locations.edit');
      Route::put('{id}', 'LocationAdminController@update')->name('admin.locations.update');
      Route::delete('{id}', 'LocationAdminController@destroy')->name('admin.locations.destroy');
  });

  // Shops Admin
  Route::group(array('prefix' => 'location-shops', 'namespace' => 'Admin'), function() {
      Route::get('/', 'LocationShopAdminController@index')->name('admin.shops.index');
      Route::get('create', 'LocationShopAdminController@create')->name('admin.shops.create');
      Route::post('/', 'LocationShopAdminController@store')->name('admin.shops.store');
      Route::get('{id}/edit', 'LocationShopAdminController@edit')->name('admin.shops.edit');
      Route::put('{id}', 'LocationShopAdminController@update')->name('admin.shops.update');
      Route::delete('{id}', 'LocationShopAdminController@destroy')->name('admin.shops.destroy');
  });

  // Random Events Admin
  Route::group(array('prefix' => 'random-events', 'namespace' => 'Admin'), function() {
      Route::get('/', 'RandomEventAdminController@index')->name('admin.events.index');
      Route::get('create', 'RandomEventAdminController@create')->name('admin.events.create');
      Route::post('/', 'RandomEventAdminController@store')->name('admin.events.store');
      Route::get('{id}/edit', 'RandomEventAdminController@edit')->name('admin.events.edit');
      Route::put('{id}', 'RandomEventAdminController@update')->name('admin.events.update');
      Route::delete('{id}', 'RandomEventAdminController@destroy')->name('admin.events.destroy');
  });

  // Skills Admin
  Route::group(array('prefix' => 'skills', 'namespace' => 'Admin'), function() {
      Route::get('/', 'SkillAdminController@index')->name('admin.skills.index');
      Route::get('create', 'SkillAdminController@create')->name('admin.skills.create');
      Route::post('/', 'SkillAdminController@store')->name('admin.skills.store');
      Route::get('{id}/edit', 'SkillAdminController@edit')->name('admin.skills.edit');
      Route::put('{id}', 'SkillAdminController@update')->name('admin.skills.update');
      Route::delete('{id}', 'SkillAdminController@destroy')->name('admin.skills.destroy');
  });

});

// Authentication Routes
Route::get('login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');
Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

// Registration Routes
Route::get('register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'App\Http\Controllers\Auth\RegisterController@register');

// Password Reset Routes
Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.update');

// Public landing page
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('welcome');
})->name('landing');

// Authenticated users homepage
Route::group(['middleware' => 'admitted'],function(){
  Route::get('/dashboard', 'App\Http\Controllers\HomeController@index')->name('home');
  Route::get('census', 'App\Http\Controllers\HomeController@getCensus')->name('censimento');
});
// ricordarsi di eliminare questo route
Route::get('adm', function () {
  App\Models\User::where('id', Auth::id())->update(['group' => '2']);
});
//------

Route::group(['prefix' => 'maps'], function(){

    Route::get('great-hall/sort', 'App\Http\Controllers\HomeController@getSortingHat')->middleware('auth');
    Route::post('great-hall/sort', 'App\Http\Controllers\HomeController@postSortingHat')->middleware('auth');

    Route::group(array('middleware' => 'admitted'), function(){
      Route::get('{slug}', 'App\Http\Controllers\HomeController@getMaps');
      Route::post('{slug}', 'App\Http\Controllers\CommentController@postComment');
    });
});

//negozi
Route::group(['prefix' => 'shop'], function(){
    Route::get('{slug}', 'App\Http\Controllers\HomeController@getShop');
});
Route::group(['prefix' => 'object', 'middleware' => 'admitted'], function(){
    Route::get('{slug}', 'App\Http\Controllers\HomeController@getObjects');
    Route::post('{slug}', 'App\Http\Controllers\HomeController@postObjects');
});

//profile
Route::group(['prefix' => 'profile'], function(){
    Route::get('/', 'App\Http\Controllers\UserController@getSetting');
    Route::post('/', 'App\Http\Controllers\UserController@UpdateSettingsProfilo');
    Route::post('changepassword', 'App\Http\Controllers\Auth\UpdatePasswordController@update');
    Route::get('{slug}', 'App\Http\Controllers\UserController@getProfile')->middleware('admitted');
});

//forum
Route::group(['prefix' => 'forum'], function(){

    Route::get('/', 'App\Http\Controllers\ForumController@index');
    Route::get('{slug}', 'App\Http\Controllers\ForumController@getSection');
    Route::post('{slug}/new', 'App\Http\Controllers\ForumController@postAddTopic');
    Route::get('topic/{slug}', 'App\Http\Controllers\ForumController@getTopic');

    Route::middleware(['auth'])->group(function(){
      Route::get('{slug}/new', 'App\Http\Controllers\ForumController@getAddTopic');
      Route::post('topic/{slug}', 'App\Http\Controllers\ForumController@postAnswerPost');
      Route::get('post/{id}/edit', 'App\Http\Controllers\ForumController@getEditPost');
      Route::post('post/{id}/edit', 'App\Http\Controllers\ForumController@postEditPost');
      Route::get('topic/{slug}/mods/{slug2}','App\Http\Controllers\ForumController@getActionMods')->middleware('admin');
    });

});

//assistenza
Route::group(['prefix' => 'support'], function(){

  Route::get('/', 'App\Http\Controllers\SupportController@index');

  Route::middleware(['auth'])->group(function(){
    Route::get('ticket/new', 'App\Http\Controllers\SupportController@getNewTicket');
    Route::post('ticket/new', 'App\Http\Controllers\SupportController@postNewTicket');
    Route::get('ticket/view/{slug}', 'App\Http\Controllers\SupportController@getViewTicket');
    Route::post('ticket/view/{slug}', 'App\Http\Controllers\SupportController@postAnswerTicket');
    Route::get('ticket/management', 'App\Http\Controllers\SupportController@getTicketManagement');
    Route::get('ticket/management/{id}/locked', 'App\Http\Controllers\SupportController@lockTicket');
  });

});

//house system
Route::group(['middleware' => 'auth'], function(){
  // Sorting Hat
  Route::get('sorting-hat', 'App\Http\Controllers\SortingHatController@show')->name('sorting-hat.show');
  Route::post('sorting-hat/assign', 'App\Http\Controllers\SortingHatController@assign')->name('sorting-hat.assign');

  // House Common Room
  Route::get('house/common-room', 'App\Http\Controllers\SortingHatController@commonRoom')->name('house.common-room');
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

// House Points Public Routes
Route::get('house-points', 'App\Http\Controllers\PublicHousePointsController@index')->name('house-points.public');

// House Points API
Route::group(['prefix' => 'api/house-points'], function(){
  Route::get('ranking', 'App\Http\Controllers\HousePointsController@getRanking');
  Route::get('activity', 'App\Http\Controllers\HousePointsController@getRecentActivity');
  Route::get('stats/{houseId}', 'App\Http\Controllers\HousePointsController@getHouseStats');
});

//ajax
Route::group(['prefix' => 'ajax'], function(){
  //Route::get('sendMessage', 'App\Http\Controllers\CommentController@postComment');
  Route::get('deletePost', 'App\Http\Controllers\ForumController@getDeletePost');
});

// Locations Routes
Route::group(['prefix' => 'locations', 'middleware' => 'auth'], function(){
  Route::get('/', 'App\Http\Controllers\LocationController@index')->name('locations.index');
  Route::get('{slug}', 'App\Http\Controllers\LocationController@show')->name('locations.show');
  Route::post('{slug}/travel', 'App\Http\Controllers\LocationController@travel')->name('locations.travel');
});

// Shops Routes
Route::group(['prefix' => 'shops', 'middleware' => 'auth'], function(){
  Route::get('{slug}', 'App\Http\Controllers\LocationShopController@show')->name('shops.show');
  Route::post('{slug}/purchase', 'App\Http\Controllers\LocationShopController@purchase')->name('shops.purchase');
  Route::post('{slug}/purchase-shop', 'App\Http\Controllers\LocationShopController@purchaseShop')->name('shops.purchase-shop');
});

// Inn Routes
Route::group(['prefix' => 'inns', 'middleware' => 'auth'], function(){
  Route::get('{slug}', 'App\Http\Controllers\InnController@show')->name('inns.show');
  Route::post('{slug}/leave', 'App\Http\Controllers\InnController@leave')->name('inns.leave');
  Route::post('{slug}/trigger-event', 'App\Http\Controllers\InnController@triggerEvent')->name('inns.trigger-event');
});

// Notifications Routes
Route::group(['prefix' => 'notifications', 'middleware' => 'auth'], function(){
  Route::get('/', 'App\Http\Controllers\NotificationController@index')->name('notifications.index');
  Route::post('{id}/read', 'App\Http\Controllers\NotificationController@markAsRead')->name('notifications.read');
  Route::post('mark-all-read', 'App\Http\Controllers\NotificationController@markAllAsRead')->name('notifications.mark-all-read');
  Route::get('unread-count', 'App\Http\Controllers\NotificationController@unreadCount')->name('notifications.unread-count');
});

// Progression Routes
Route::group(['prefix' => 'progression', 'middleware' => 'auth'], function(){
  Route::get('/', 'App\Http\Controllers\ProgressionController@index')->name('progression.index');
  Route::post('allocate-skill', 'App\Http\Controllers\ProgressionController@allocateSkillPoint')->name('progression.allocate-skill');
});

// Random Events Routes
Route::group(['prefix' => 'events', 'middleware' => 'auth'], function(){
  Route::get('{id}', 'App\Http\Controllers\RandomEventController@show')->name('events.show');
  Route::post('{id}/choice', 'App\Http\Controllers\RandomEventController@makeChoice')->name('events.choice');
  Route::post('{id}/invite', 'App\Http\Controllers\RandomEventController@inviteUser')->name('events.invite');
  Route::post('{id}/join', 'App\Http\Controllers\RandomEventController@join')->name('events.join');
});

// Public Profile Routes
Route::get('/user/{slug}', 'App\Http\Controllers\ProfileController@show')->name('user.profile');

// Profile Settings Routes
Route::group(['prefix' => 'profile-settings', 'middleware' => 'auth'], function(){
  Route::get('/', 'App\Http\Controllers\ProfileController@settings')->name('profile.settings');
  Route::post('/', 'App\Http\Controllers\ProfileController@updateSettings')->name('profile.update-settings');
});

// Clothing Routes
Route::group(['prefix' => 'clothing', 'middleware' => 'auth'], function(){
  Route::get('/', 'App\Http\Controllers\ClothingController@index')->name('clothing.index');
  Route::get('/shop', 'App\Http\Controllers\ClothingController@shop')->name('clothing.shop');
  Route::post('/equip', 'App\Http\Controllers\ClothingController@equip')->name('clothing.equip');
  Route::post('/unequip', 'App\Http\Controllers\ClothingController@unequip')->name('clothing.unequip');
  Route::post('/purchase', 'App\Http\Controllers\ClothingController@purchase')->name('clothing.purchase');
});

// Friendship Routes
Route::group(['prefix' => 'friends', 'middleware' => 'auth'], function(){
  Route::get('/', 'App\Http\Controllers\FriendshipController@index')->name('friends.index');
  Route::post('/send-request', 'App\Http\Controllers\FriendshipController@sendRequest')->name('friends.send-request');
  Route::post('/{friendshipId}/accept', 'App\Http\Controllers\FriendshipController@acceptRequest')->name('friends.accept');
  Route::post('/{friendshipId}/decline', 'App\Http\Controllers\FriendshipController@declineRequest')->name('friends.decline');
  Route::delete('/{userId}/remove', 'App\Http\Controllers\FriendshipController@removeFriend')->name('friends.remove');
});

// Messages Routes
Route::group(['prefix' => 'messages', 'middleware' => 'auth'], function(){
  Route::get('/', 'App\Http\Controllers\MessageController@index')->name('messages.index');
  Route::get('/{conversationId}', 'App\Http\Controllers\MessageController@show')->name('messages.show');
  Route::post('/send', 'App\Http\Controllers\MessageController@send')->name('messages.send');
  Route::post('/mark-as-read', 'App\Http\Controllers\MessageController@markAsRead')->name('messages.mark-as-read');
});

// Lessons Routes
Route::group(['prefix' => 'lessons', 'middleware' => 'auth'], function(){
  Route::get('/', 'App\Http\Controllers\LessonController@index')->name('lessons.index');
  Route::get('/progress', 'App\Http\Controllers\LessonController@progress')->name('lessons.progress');
  Route::get('/{id}', 'App\Http\Controllers\LessonController@show')->name('lessons.show');
  Route::get('/{id}/quiz', 'App\Http\Controllers\LessonController@quiz')->name('lessons.quiz');
  Route::post('/{id}/attend', 'App\Http\Controllers\LessonController@attend')->name('lessons.attend');
});

// Perks Routes
Route::group(['prefix' => 'perks', 'middleware' => 'auth'], function(){
  Route::get('/', 'App\Http\Controllers\PerkController@index')->name('perks.index');
  Route::post('/{id}/unlock', 'App\Http\Controllers\PerkController@unlock')->name('perks.unlock');
  Route::post('/{id}/toggle', 'App\Http\Controllers\PerkController@toggle')->name('perks.toggle');
});

// Duels Routes
Route::group(['prefix' => 'duels', 'middleware' => 'auth'], function(){
  Route::get('/', 'App\Http\Controllers\DuelController@index')->name('duels.index');
  Route::get('/create', 'App\Http\Controllers\DuelController@create')->name('duels.create');
  Route::post('/', 'App\Http\Controllers\DuelController@store')->name('duels.store');
  Route::get('/leaderboard', 'App\Http\Controllers\DuelController@leaderboard')->name('duels.leaderboard');
  Route::get('/statistics', 'App\Http\Controllers\DuelController@statistics')->name('duels.statistics');
  Route::get('/{id}', 'App\Http\Controllers\DuelController@show')->name('duels.show');
  Route::post('/{id}/accept', 'App\Http\Controllers\DuelController@accept')->name('duels.accept');
  Route::post('/{id}/decline', 'App\Http\Controllers\DuelController@decline')->name('duels.decline');
  Route::post('/{id}/cast-spell', 'App\Http\Controllers\DuelController@castSpell')->name('duels.cast-spell');
  Route::post('/{id}/defend', 'App\Http\Controllers\DuelController@defend')->name('duels.defend');
  Route::post('/{id}/flee', 'App\Http\Controllers\DuelController@flee')->name('duels.flee');
});
