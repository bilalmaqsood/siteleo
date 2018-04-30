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
 
Route::group(['middleware' => 'web'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', function () { return redirect()->route('home'); });
    Route::get('/profile/id{user_id}', 'ProfileController@index')->name('profile');
    Route::get('/search/ajax', 'SearchController@ajax')->name('search-ajax');
    Route::get('/search/q/', 'SearchController@indexQ')->name('search-q');
    Route::get('/search/sResult/', 'SearchController@sResult')->name('search-s');
    Route::get('/search/cResult/', 'SearchController@cResult')->name('search-c');
    Route::get('/search/{uri_category}/{uri_subcategory?}', 'SearchController@index')->name('search');
    Route::get('/faq', 'ConditionsController@index')->name('conditions');
    Route::get('/terms', 'TermsController@index')->name('terms');
    Route::get('/categories', 'CategoriesController@index')->name('categories');


    Route::get('/live-upd', function(){
        $counts = \App\Models\CoutEvents::counts();
        return [
            'new_events' => !$counts ? 0 : $counts->chat_num+$counts->ad_num,
            'chat_num' => !$counts ? 0 : $counts->chat_num,
            'ad_num' => !$counts ? 0 : $counts->ad_num,
        ];
    })->name('live-update');
    Route::get('/live-chat', 'User\ChatController@liveChat')->name('live-chat');

    Route::group(['middleware' => 'auth'], function () {
        Route::post('/advertise/{id}/commentaries', 'AdertiseController@commentary')->name('advertise-commentary');
        Route::get('/advertise/like', 'AdertiseController@llike')->name('advertise-like');
        Route::post('/advertise/likes', 'AdertiseController@storeLike')->name('advertise-like.store');
        Route::post('/advertise/{id}/chat', 'AdertiseController@chat')->name('advertise-chat');

        //Route::get('/live-updater', 'HomeController@liveUpdate')->name('live-update');
    });

    Route::get('/advertise/{uri}', 'AdertiseController@index')->name('advertise');

    Route::get('/contact', 'ContactsController@index')->name('contact');
    Route::post('/contact', 'ContactsController@create')->name('contact-send');
});

Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
    Route::get('/', function () { return redirect()->route('user-setting'); })->name('user');
    
    Route::group(['prefix' => 'setting', 'middleware' => 'auth'], function () {
        Route::get('/', 'User\SettingsController@index')->name('user-setting');
        Route::post('/', 'User\SettingsController@update')->name('user-setting-update');
    });
    
    Route::group(['prefix' => 'profile', 'middleware' => 'auth'], function(){
        Route::get('/', 'User\ProfileController@index')->name('user-profile');
        Route::get('/worker', 'User\ProfileController@worker')->name('user-worker');
        
        Route::post('/', 'User\ProfileController@update')->name('user-profile-update');
        Route::post('/location', 'User\ProfileController@location_update')->name('user-location-update');
        Route::post('/graphics', 'User\ProfileController@graphics_update')->name('user-graphics-update');
    });
    
    Route::get('/messages', 'User\MessagesController@index')->name('user-messages');
    Route::get('/chat/{id}', 'User\ChatController@index')->name('user-chat');
    Route::post('/chat/{id}', 'User\ChatController@say')->name('user-chat-say');
    Route::get('/chat/say/{id}', 'User\ChatController@sayAjax')->name('user-chat-say-ajax');

    Route::get('/favorite', 'User\FavoriteController@index')->name('user-favorite');
    
    Route::group(['middleware' => 'role'], function(){
        Route::get('/advertisement', 'User\AdvertisementController@index')->name('user-advertisement');
        Route::post('/advertisement', 'User\AdvertisementController@destroy')->name('user-advertisement-destroy');
        Route::get('/new-advertisement', 'User\NewAdvertisementController@index')->name('user-new-advertisement');
        Route::post('/new-advertisement', 'User\NewAdvertisementController@create')->name('user-new-advertisement-create');
        Route::get('{id}/edit-advertisement/', 'User\NewAdvertisementController@edit')->name('user-new-advertisement-edit');
        Route::post('{id}/update-advertisement/', 'User\NewAdvertisementController@update')->name('user-new-advertisement-update');
        Route::get('{id}/up-advertisement/', 'User\AdvertisementController@up')->name('user-new-advertisement-up');
        Route::get('/feedback', 'User\FeedbackController@index')->name('user-feedback');
        Route::post('/feedback/{id}/answer', 'User\FeedbackController@answer')->name('user-feedback-answer');
        Route::get('/balance', 'User\BalanceController@index')->name('user-balance');
        Route::post('/balance', 'User\BalanceController@create')->name('user-balance-pay');
        Route::get('/pay/{hash}', 'User\BalanceController@pay')->name('user-balance-pay-redirect');
    });
});


Route::get('404',function(){ return view('errors.404'); })->name('404');
Route::get('405',function(){ return view('errors.405'); })->name('405');

Auth::routes();
