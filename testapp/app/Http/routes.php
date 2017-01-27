
<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::post('filepost','mainController@filePost');
Route::group(['prefix'=>'api'],function()
{
	Route::group(['prefix'=>'category'],function()
	{
		Route::get('catlist', 'apiController@categorylist');
		Route::get('keywordajax', 'apiController@keywordajax');
	});

	Route::group(['prefix'=>'city'],function()
	{
		Route::get('citylist', 'apiController@citylist');
		Route::get('citylistajax', 'apiController@citylistajax');
		Route::get('browselist', 'apiController@browselist');
		Route::any('checklocation', 'apiController@checklocation');
		Route::post('changecokkies', 'apiController@changecokkies');
	});
});

Route::group(array('namespace'=>'admin'), function()
{   
			Route::get('/admin', 'AdminController@index');
			Route::post('/postIndex', 'AdminController@postIndex');
			Route::get('/admin/dashboard', 'AdminController@dashboard');
			Route::get('/admin/logout', 'AdminController@getLogout');
			Route::get('/admin/user/manage', 'AdminController@manageuser');
			Route::any('/admin/makeuser', 'AdminController@makeuser');
			Route::any('/editadminuser/{userID}', 'AdminController@editadminuser');
			Route::any('/admin/makeuser/edit/{userID}', 'AdminController@makeuser');
			Route::get('updateuserstatus/{id}/{status}', 'AdminController@adminuserstatus');
			Route::any('/admin/location/{type?}/{id?}', 'AdminController@locationtype');
			Route::any('/locationstatus/{type?}/{id?}', 'AdminController@changelocationstatus');

			Route::any('/admin/articles/{type?}/{id?}', 'AdminController@articlesmaster');
			Route::any('/categorystatus/{type?}/{id?}', 'AdminController@changecategorystatus');
			Route::any('/articlelist', 'AdminController@articlelist');
			Route::any('/deletearticle/{id}', 'AdminController@deletearticle');
			Route::any('/articlestatus/{id}', 'AdminController@articlestatus');
			Route::any('/discussionlist', 'AdminController@discussionlist');
			Route::any('/deletediscussion/{id}', 'AdminController@deletediscussion');
			Route::any('/discussionstatus/{id}', 'AdminController@discussionstatus');
			Route::any('/invitationlisting', 'AdminController@invitationlist');
			Route::any('/discussion-comment-list', 'AdminController@discussion_comment');
    
});


Route::any('/articles', 'ArticlesController@index');
Route::any('/articledetail/{articleurl}', 'ArticlesController@articledetail');
Route::any('/deletearticle/{id}', 'ArticlesController@deletearticle');
Route::any('/discussions', 'DiscussionController@index');
Route::any('/discussiondetail/{url}', 'DiscussionController@discussionshow');
Route::any('/digital-locker', 'DigitallockerController@index');

///////////////job listing
Route::any('/joblisting', 'JoblistingController@index');

/////////////////////////// profile//////////
Route::any('/profile/{pagename?}', 'UserController@index');
Route::any('/editprofile/{pagename?}', 'UserController@editprofile');

//////////////////////////////////////
Route::post('career/postjob', 'mainController@postjob');
Route::get('/contactus', 'mainController@contactus');
Route::get('faq', 'mainController@faq');
Route::get('/','mainController@index');
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::get('auth/admin', 'Auth\AuthController@getadminlogin');
Route::post('auth/login', 'Auth\AuthController@userlogin_register');
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('about/', 'mainController@about');

/////////////////////////////// ajax login////
Route::post('/ajaxlogin','ajaxRequestController@loginform');
Route::get('/ajaxauthnticate','Auth\AuthController@postLoginajax');

Route::post('/statelist','ajaxRequestController@statelist');
/*adding terms and policy page */
Route::get('/terms', 'mainController@terms');
Route::get('/privacy-policy', 'mainController@privacy');
Route::get('/contactus', 'mainController@contactus');
Route::post('/contactus', 'mainController@saveContactFormInfo');
 // Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}/{email?}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

/////////////////////////////////////////////////////////////////////////////

Route::post('/loadsharebox','DigitallockerController@sharedocument');
Route::post('/sharedocument','DigitallockerController@makesharedocument');
Route::post('/deletedocument','DigitallockerController@deletedocument');
///////////////////////////////////////////////////////////////////////
Route::post('/connectmailbox','ajaxRequestController@connectviamailbox');
Route::get('/savedetails','ajaxRequestController@savedetails');


Route::get('error404', 'eventController@errorpage');

Route::get('/errorpage', 'eventController@commonerror');




