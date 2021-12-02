<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Api\AuthController@login');
    Route::post('register', 'Api\AuthController@register');
    Route::post('searchUserFromEmail', 'Api\AuthController@searchUserFromEmail');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::delete('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
        Route::get('getUserById/{userId}', 'Api\AuthController@getUserById');
        Route::post('updateProfile/{userId}', 'Api\AuthController@updateProfile');
        Route::post('followUser/{accountId}/{userId}', 'Api\AuthController@followUser');
        Route::post('unfollowUser/{accountId}/{userId}', 'Api\AuthController@unfollowUser');
        Route::get('getFollowingUser/{userId}', 'Api\AuthController@getFollowingUser');
        Route::get('getFollowingUser/{userId}', 'Api\AuthController@getFollowingUser');
        Route::get('getFollowers/{userId}', 'Api\AuthController@getFollowers');
        Route::get('getLatestFollowingExams/{userId}', 'Api\AuthController@getLatestFollowingExams');
        Route::get('getLatestFollowingTopics/{userId}', 'Api\AuthController@getLatestFollowingTopics');
    });
});

Route::group([
    'prefix' =>'topic'
],function(){
    Route::post('search', 'Api\TopicApiController@search');
    Route::post('searchTopicFromUser', 'Api\TopicApiController@searchTopicFromUser');
    Route::get('getTopicById/{topicId}', 'Api\TopicApiController@getTopicById');

});

Route::group([
    'prefix' =>'exam'
],function(){
    //Route::post('search', 'Api\TopicApiController@search');
    Route::post('searchExamFromUser/{userId}', 'Api\ExamApiController@searchExamFromUser');
    Route::get('getExamFromTopic/{topicId}', 'Api\ExamApiController@getExamFromTopic');
    Route::get('getExamByCategory/{userId}/{categoryId}', 'Api\ExamApiController@getExamByCategory');
    Route::post('addMark/{userId}/{examId}', 'ExamMarkController@addMark');
    Route::get('getTestedExamByUserId/{userId}', 'Api\ExamApiController@getTestedExamByUserId');



});

Route::group([
    'prefix' =>'category'
],function(){
    //Route::post('search', 'Api\TopicApiController@search');
    Route::get('getCategoryFromTopic/{topiId}', 'Api\CategoryApiController@getCategoryFromTopic');
    Route::get('getCategoryById/{categoryId}', 'Api\CategoryApiController@getCategoryById');

});
