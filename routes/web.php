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

// Auth::routes(); // Commented out - requires laravel/ui package
// If you need these routes, you can either:
// 1. Install laravel/ui: composer require laravel/ui
// 2. Or define authentication routes manually here

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

// House Points Public Routes
Route::get('house-points', 'PublicHousePointsController@index')->name('house-points.public');

// House Points API
Route::group(['prefix' => 'api/house-points'], function(){
  Route::get('ranking', 'HousePointsController@getRanking');
  Route::get('activity', 'HousePointsController@getRecentActivity');
  Route::get('stats/{houseId}', 'HousePointsController@getHouseStats');
});

//ajax
Route::group(['prefix' => 'ajax'], function(){
  //Route::get('sendMessage', 'CommentController@postComment');
  Route::get('deletePost', 'ForumController@getDeletePost');
});

// Locations Routes
Route::group(['prefix' => 'locations', 'middleware' => 'auth'], function(){
  Route::get('/', 'LocationController@index')->name('locations.index');
  Route::get('{slug}', 'LocationController@show')->name('locations.show');
  Route::post('{slug}/travel', 'LocationController@travel')->name('locations.travel');
});

// Shops Routes
Route::group(['prefix' => 'shops', 'middleware' => 'auth'], function(){
  Route::get('{slug}', 'LocationShopController@show')->name('shops.show');
  Route::post('{slug}/purchase', 'LocationShopController@purchase')->name('shops.purchase');
  Route::post('{slug}/purchase-shop', 'LocationShopController@purchaseShop')->name('shops.purchase-shop');
});

// Inn Routes
Route::group(['prefix' => 'inns', 'middleware' => 'auth'], function(){
  Route::get('{slug}', 'InnController@show')->name('inns.show');
  Route::post('{slug}/leave', 'InnController@leave')->name('inns.leave');
  Route::post('{slug}/trigger-event', 'InnController@triggerEvent')->name('inns.trigger-event');
});

// Notifications Routes
Route::group(['prefix' => 'notifications', 'middleware' => 'auth'], function(){
  Route::get('/', 'NotificationController@index')->name('notifications.index');
  Route::post('{id}/read', 'NotificationController@markAsRead')->name('notifications.read');
  Route::post('mark-all-read', 'NotificationController@markAllAsRead')->name('notifications.mark-all-read');
  Route::get('unread-count', 'NotificationController@unreadCount')->name('notifications.unread-count');
});

// Progression Routes
Route::group(['prefix' => 'progression', 'middleware' => 'auth'], function(){
  Route::get('/', 'ProgressionController@index')->name('progression.index');
  Route::post('allocate-skill', 'ProgressionController@allocateSkillPoint')->name('progression.allocate-skill');
});

// Random Events Routes
Route::group(['prefix' => 'events', 'middleware' => 'auth'], function(){
  Route::get('{id}', 'RandomEventController@show')->name('events.show');
  Route::post('{id}/choice', 'RandomEventController@makeChoice')->name('events.choice');
  Route::post('{id}/invite', 'RandomEventController@inviteUser')->name('events.invite');
  Route::post('{id}/join', 'RandomEventController@join')->name('events.join');
});

// Public Profile Routes
Route::get('/user/{slug}', 'ProfileController@show')->name('user.profile');

// Profile Settings Routes
Route::group(['prefix' => 'profile-settings', 'middleware' => 'auth'], function(){
  Route::get('/', 'ProfileController@settings')->name('profile.settings');
  Route::post('/', 'ProfileController@updateSettings')->name('profile.update-settings');
});

// Clothing Routes
Route::group(['prefix' => 'clothing', 'middleware' => 'auth'], function(){
  Route::get('/', 'ClothingController@index')->name('clothing.index');
  Route::get('/shop', 'ClothingController@shop')->name('clothing.shop');
  Route::post('/equip', 'ClothingController@equip')->name('clothing.equip');
  Route::post('/unequip', 'ClothingController@unequip')->name('clothing.unequip');
  Route::post('/purchase', 'ClothingController@purchase')->name('clothing.purchase');
});

// Friendship Routes
Route::group(['prefix' => 'friends', 'middleware' => 'auth'], function(){
  Route::get('/', 'FriendshipController@index')->name('friends.index');
  Route::post('/send-request', 'FriendshipController@sendRequest')->name('friends.send-request');
  Route::post('/{friendshipId}/accept', 'FriendshipController@acceptRequest')->name('friends.accept');
  Route::post('/{friendshipId}/decline', 'FriendshipController@declineRequest')->name('friends.decline');
  Route::delete('/{userId}/remove', 'FriendshipController@removeFriend')->name('friends.remove');
});

// Messages Routes
Route::group(['prefix' => 'messages', 'middleware' => 'auth'], function(){
  Route::get('/', 'MessageController@index')->name('messages.index');
  Route::get('/{conversationId}', 'MessageController@show')->name('messages.show');
  Route::post('/send', 'MessageController@send')->name('messages.send');
  Route::post('/mark-as-read', 'MessageController@markAsRead')->name('messages.mark-as-read');
});

// Lessons Routes
Route::group(['prefix' => 'lessons', 'middleware' => 'auth'], function(){
  Route::get('/', 'LessonController@index')->name('lessons.index');
  Route::get('/progress', 'LessonController@progress')->name('lessons.progress');
  Route::get('/{id}', 'LessonController@show')->name('lessons.show');
  Route::get('/{id}/quiz', 'LessonController@quiz')->name('lessons.quiz');
  Route::post('/{id}/attend', 'LessonController@attend')->name('lessons.attend');
});

// Perks Routes
Route::group(['prefix' => 'perks', 'middleware' => 'auth'], function(){
  Route::get('/', 'PerkController@index')->name('perks.index');
  Route::post('/{id}/unlock', 'PerkController@unlock')->name('perks.unlock');
  Route::post('/{id}/toggle', 'PerkController@toggle')->name('perks.toggle');
});
