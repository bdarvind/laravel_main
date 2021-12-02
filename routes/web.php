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
/*
Route::get('/', function () {
    return view('home');
})->middleware('auth');
*/
Route::get('/', 'HomeController@home')->middleware('auth');;

Route::get('/register', 'Auth\RegisterController@register')->name('register');
Route::post('register', 'Auth\RegisterController@storeUser');

Route::get('login', 'Auth\LoginController@login')->name('login');
Route::post('login', 'Auth\LoginController@authenticate');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('home', 'HomeController@home')->name('home')->middleware('auth');

Route::get('forget-password', 'Auth\ForgotPasswordController@getEmail');
Route::post('forget-password', 'Auth\ForgotPasswordController@postEmail');

Route::get('reset-password/{token}', 'Auth\ResetPasswordController@getPassword');
Route::post('reset-password', 'Auth\ResetPasswordController@updatePassword');

Route::get('/topics', 'TopicController@loadAll')->name('topics')->middleware('auth');
Route::get('topic_add', 'TopicController@accessTopicAdd')->name('topic_add');
Route::post('topic_add', 'TopicController@addTopic')->middleware('auth');
Route::get('topic_edit/{topicId}','TopicController@accessEditTopic')->name('topic_edit')->middleware('auth');
Route::post('topic_edit/{topicId}', 'TopicController@updateTopic')->middleware('auth');
Route::get('topic_delete/{topicId}','TopicController@deleteTopic')->name('topic_delete')->middleware('auth');

Route::get('categories', 'CategoryController@loadAll')->name('categories')->middleware('auth');
Route::get('category_add', 'CategoryController@accessCategoryAdd')->name('category_add');
Route::post('category_add', 'CategoryController@addCategory')->middleware('auth');
Route::get('category_edit/{categoryId}','CategoryController@accessEditCategory')->name('category_edit')->middleware('auth');
Route::post('category_edit/{categoryId}', 'CategoryController@updateCategory')->middleware('auth');
Route::get('category_delete/{categoryId}','CategoryController@deleteCategory')->name('category_delete')->middleware('auth');

Route::get('questions', 'QuestionController@loadAll')->name('questions')->middleware('auth');
Route::get('question_add', 'QuestionController@accessQuestionAdd')->name('question_add');
Route::post('question_add', 'QuestionController@addQuestion')->middleware('auth');
Route::get('question_edit/{questionId}','QuestionController@accessEditQuestion')->name('question_edit')->middleware('auth');
Route::post('question_edit/{questionId}', 'QuestionController@updateQuestion')->middleware('auth');
Route::get('question_delete/{questionId}','QuestionController@deleteQuestion')->name('question_delete')->middleware('auth');

Route::post('showCategoriesInTopic', 'CategoryController@showCategoriesInTopic');

Route::get('exams', 'ExamController@loadAll')->name('exams')->middleware('auth');
Route::get('exam_add', 'ExamController@accessExamAdd')->name('exam_add');
Route::post('exam_add', 'ExamController@addExam')->middleware('auth');
Route::get('exam_edit/{examId}','ExamController@accessEditExam')->name('exam_edit')->middleware('auth');
Route::post('exam_edit/{examId}', 'ExamController@updateExam')->middleware('auth');
Route::get('exam_delete/{examId}','ExamController@deleteExam')->name('exam_delete')->middleware('auth');

Route::post('showQuestionsInCategory', 'QuestionController@showQuestionsInCategory');
Route::post('getQuestionList', 'ExamController@getQuestionList');
Route::post('showQuestionsInTopic', 'QuestionController@showQuestionsInTopic');
//Route::get('question_add_question', 'CategoryController@showCategories')->name('question_add_question');
Route::post('showUserInToolbar', 'HomeController@showUserInToolbar');

Route::get('preview-image-upload', 'PhotoController@index');
Route::post('preview-image-upload', 'PhotoController@store');




