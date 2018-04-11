<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    //$router->resource('pages', PageController::class);
    $router->resource('users', UserController::class);
    $router->resource('category', CategoryController::class);
    $router->resource('list_cost', ListCostController::class);
    $router->resource('ads', AdsController::class);
    $router->resource('ads-comments', AdsCommentsController::class);
    $router->resource('banner_words', BannerWordsController::class);
    $router->resource('home_information', HomeInfoController::class);
    $router->resource('reviews', ReviewsController::class);
    $router->resource('feedback', FeedbackController::class);
    $router->resource('conditions', ConditionsController::class);
    $router->resource('terms', TermsController::class);
    $router->resource('payments', PaymentsController::class);

    Route::group(['prefix' => 'lang'], function(Router $router){
        $router->resource('/languages', Language\LanguageController::class);
        $router->resource('/translations', Language\TranslationController::class);
    });
    
});
