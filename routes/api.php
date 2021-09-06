<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarketController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('/details', function () {

    $ip = '50.90.0.1';
    $ip = \Request::ip();
    dd($ip);
    $data = \Location::get($ip);
    dd($data);
   
});

Route::post('admin/login', 'AuthController@adminLogin');
Route::post('login', 'AuthController@login');
Route::get('/packages/all', 'PackageController@allPackages');
Route::post('/monnifyWebhookResponse', 'PackageController@monnifyWebhookResponse');
Route::post('password/email', 'ForgotPasswordController@forgot');
Route::post('password/reset', 'ForgotPasswordController@reset');
Route::post('/marketplace', 'MarketController@store');
Route::put('/marketplace/{id}', 'MarketController@update');
Route::delete('/marketplace', 'MarketController@destroyAll');


// Route::middleware(['auth:api','auth.validToken'])->group( function(){
// Route::middleware(['auth:admin','auth.validToken'])->group( function(){
Route::middleware(['auth.jwt'])->group( function(){
    Route::get('/totalStats', 'DashboardController@totalStats');
    Route::get('/getSets', 'DashboardController@getSets');
    Route::get('/countStats', 'DashboardController@userCountStats');
    Route::get('/getDatasets', 'DashboardController@userGetDatasets');
    
    // Route::post('details', 'API\UserController@details');

    Route::get('/admins/list', 'AdminController@list');
    Route::get('/admins/listWithType', 'AdminController@listWithType');
    Route::resource('/admins', 'AdminController');

    Route::post('/adverts/changeStatus', 'AdvertController@changeStatus');
    Route::delete('/adverts', 'AdvertController@destroyAll');
    Route::resource('/adverts', 'AdvertController');

    Route::post('/artisans/changeStatus', 'ArtisanController@changeStatus');
    Route::delete('/artisans', 'ArtisanController@destroyAll');
    Route::get('/artisans/groupLists', 'ArtisanController@groupLists');
    Route::resource('/artisans', 'ArtisanController');
    
    Route::post('/artisanCategories/changeStatus', 'ArtisanCategoryController@changeStatus');
    Route::delete('/artisanCategories', 'ArtisanCategoryController@destroyAll');
    Route::get('/artisanCategories/lists', 'ArtisanCategoryController@lists');
    Route::resource('/artisanCategories', 'ArtisanCategoryController');
        
    Route::post('/artisanGroups/changeStatus', 'ArtisanGroupController@changeStatus');
    Route::delete('/artisanGroups', 'ArtisanGroupController@destroyAll');
    Route::resource('/artisanGroups', 'ArtisanGroupController');

    Route::post('/artisan-ratings/changeStatus', 'ArtisanRatingController@changeStatus');
    Route::delete('/artisan-ratings', 'ArtisanRatingController@destroyAll');
    Route::resource('/artisan-ratings', 'ArtisanRatingController');

    Route::post('/artisan-signins/changeStatus', 'ArtisanSigninController@changeStatus');
    Route::delete('/artisan-signins', 'ArtisanSigninController@destroyAll');
    Route::resource('/artisan-signins', 'ArtisanSigninController');
    
    Route::post('/estates/changeStatus', 'EstateController@changeStatus');
    Route::delete('/estates', 'EstateController@destroyAll');
    Route::get('/estates/lists', 'EstateController@lists');
    Route::get('/estates/getEstateRelatedLists', 'EstateController@getEstateRelatedLists');
    Route::post('/estates/updateEstateGuards', 'EstateController@updateEstateGuards');
    Route::resource('/estates', 'EstateController');
    
    Route::post('/events/changeStatus', 'EventController@changeStatus');
    Route::delete('/events', 'EventController@destroyAll');
    Route::get('/events/lists', 'EventController@lists');
    Route::resource('/events', 'EventController');

    Route::post('/goods/buyItems/changeStatus', 'GoodController@buyItemChangeStatus');
    Route::get('/goods/purchased', 'GoodController@purchased');
    Route::post('/goods/buyGoods', 'GoodController@buyGoods');
    Route::get('/goods/getGoods', 'GoodController@getGoods');
    Route::delete('/goods/items', 'GoodController@destroyAllItem');
    Route::post('/goods/items/changeStatus', 'GoodController@itemChangeStatus');
    Route::post('/goods/items/edit/{slug}', 'GoodController@item_edit');
    Route::post('/goods/items/add', 'GoodController@item_add');
    Route::get('/goods/items/get-details/{slug}', 'GoodController@get_details');
    Route::get('/goods/items/{goodId}', 'GoodController@items');
    Route::post('/goods/changeStatus', 'GoodController@changeStatus');
    Route::delete('/goods', 'GoodController@destroyAll');
    Route::resource('/goods', 'GoodController');
    
    Route::post('/groups/changeStatus', 'GroupController@changeStatus');
    Route::get('/groups/groupRelatedLists', 'GroupController@groupRelatedLists');
    Route::get('/groups/getUsers', 'GroupController@getUsers');
    Route::delete('/groups', 'GroupController@destroyAll');
    Route::resource('/groups', 'GroupController');

    Route::get('/messages/smsLogs', 'MessageController@smsLogs');
    Route::get('/messages/emailLogs', 'MessageController@emailLogs');
    Route::resource('/messages', 'MessageController');
    
    Route::post('/notifications/contactEstate', 'NotificationController@contactEstate');
    Route::post('/notifications/save-token', 'NotificationController@saveToken');
    Route::post('/notifications/send','NotificationController@sendNotification');
    Route::post('/notifications/changeStatus', 'NotificationController@changeStatus');
    Route::get('/notifications/panicAlert', 'NotificationController@panicAlert');
    Route::delete('/notifications', 'NotificationController@destroyAll');
    Route::get('/notifications/getLatest', 'NotificationController@getLatest');
    Route::resource('/notifications', 'NotificationController');
    Route::post('/guard/notification', 'AuthController@SecurityNotifications');

    Route::post('/packages/changeStatus', 'PackageController@changeStatus');
    Route::delete('/packages', 'PackageController@destroyAll');
    Route::resource('/packages', 'PackageController');

    Route::post('/pages/changeStatus', 'PageController@changeStatus');
    Route::delete('/pages', 'PageController@destroyAll');
    Route::resource('/pages', 'PageController');
    
    Route::post('/payments/productPay', 'PaymentController@productPay');
    Route::post('/payments/productInstallmantPay', 'PaymentController@productInstallmantPay');
    Route::resource('/payments', 'PaymentController');

    Route::post('/power-products/changeStatus', 'PowerProductController@changeStatus');
    Route::delete('/power-products', 'PowerProductController@destroyAll');
    Route::post('/power-products/buy/{id}', 'PowerProductController@buy');
    Route::resource('/power-products', 'PowerProductController');


    Route::post('/products/changeStatus', 'ProductController@changeStatus');
    Route::delete('/products', 'ProductController@destroyAll');
    Route::get('/products/list', 'ProductController@list');
    Route::get('/products/purchased', 'ProductController@purchased');
    Route::get('/products/purchasedDetails/{slug}', 'ProductController@purchasedDetails');
    Route::resource('/products', 'ProductController');
    
    Route::post('/relationships/changeStatus', 'RelationshipController@changeStatus');
    Route::delete('/relationships', 'RelationshipController@destroyAll');
    Route::get('/relationships/lists', 'RelationshipController@lists');
    Route::resource('/relationships', 'RelationshipController');

    Route::post('/roles/changeStatus', 'RoleController@changeStatus');
    Route::delete('/roles', 'RoleController@destroyAll');
    Route::post('/roles/addRoleRoutes', 'RoleController@addRoleRoutes');
    Route::get('/roles/getRoleList', 'RoleController@getRoleList');
    Route::resource('/roles', 'RoleController');

    Route::get('/reports/user', 'ReportController@user');
    Route::get('/reports/product', 'ReportController@product');
    Route::get('/reports/powerProduct', 'ReportController@powerProduct');

    Route::get('/routes/authRoutes', 'RouteController@authRoutes');
    Route::get('/routes/getAssignRoutes', 'RouteController@getAssignRoutes');
    Route::resource('/routes', 'RouteController');
    
    Route::get('/send-mes/buy-items', 'SendMeController@getBuyItems');
    Route::post('/send-mes/saveBuyItems', 'SendMeController@saveBuyItems');
    Route::get('/send-mes/estates/getItems', 'SendMeController@getItems');
    Route::delete('/send-mes/estates', 'SendMeController@destroyAllEstate');
    Route::get('/send-mes/estates/get-details/{sendMeestateId}', 'SendMeController@get_estateDetails');
    Route::post('/send-mes/estates/edit/{id}', 'SendMeController@estate_edit');
    Route::post('/send-mes/estates/add', 'SendMeController@estate_add');
    Route::get('/send-mes/estates', 'SendMeController@estates');
    Route::get('/send-mes/getSendMeWithList/{slug}', 'SendMeController@getSendMeWithList');
    Route::delete('/send-mes/items', 'SendMeController@destroyAllItem');
    Route::post('/send-mes/items/changeStatus', 'SendMeController@itemChangeStatus');
    Route::post('/send-mes/items/edit/{slug}', 'SendMeController@item_edit');
    Route::post('/send-mes/items/add', 'SendMeController@item_add');
    Route::get('/send-mes/items/get-details/{sendMeId}', 'SendMeController@get_details');
    Route::get('/send-mes/items/{sendMeId}', 'SendMeController@items');
    Route::post('/send-mes/changeStatus', 'SendMeController@changeStatus');
    Route::delete('/send-mes', 'SendMeController@destroyAll');
    Route::resource('/send-mes', 'SendMeController');

    Route::post('/settings/updateStatusPaymentMethod', 'SettingController@updateStatusPaymentMethod');
    Route::get('/settings/paymentMethods', 'SettingController@paymentMethods');
    Route::post('/settings/savePaymentMethod','SettingController@savePaymentMethod');
    Route::delete('/settings/paymentMethods','SettingController@destroyAllPaymentMethod');
    Route::get('/settings/paymentMethodList','SettingController@paymentMethodList');
    Route::get('/settings/generalSetting','SettingController@generalSetting');
    Route::resource('/settings', 'SettingController');
    
    Route::post('/sms-settings/changeStatus', 'SmsSettingController@changeStatus');
    Route::delete('/sms-settings', 'SmsSettingController@destroyAll');
    Route::resource('/sms-settings', 'SmsSettingController');

    Route::post('/templates/changeStatus', 'TemplateController@changeStatus');
    Route::delete('/templates', 'TemplateController@destroyAll');
    Route::resource('/templates', 'TemplateController');
    
    Route::post('/users/changeStatus', 'UserController@changeStatus');
    Route::post('/users/addUser', 'UserController@addUser');
    Route::post('/users/editUser/{slug}', 'UserController@editUser');
    Route::post('/users/import', 'UserController@import');
    Route::get('/users/getResidentRelatedLists', 'UserController@getResidentRelatedLists');
    Route::get('/users/getSecurityGuardRelatedLists', 'UserController@getSecurityGuardRelatedLists');
    Route::post('/users/resetProfile', 'UserController@resetProfile');
    Route::delete('/users', 'UserController@destroyAll');
    Route::get('/users/nextofKins', 'UserController@nextofKins');
    Route::post('/users/addNextofKin', 'UserController@addNextofKin');
    Route::post('/users/editNextofKin/{slug}', 'UserController@editNextofKin');
    Route::delete('/users/nextofKinsDelete', 'UserController@nextofKinsDelete');
    Route::post('/users/assignPanicAlert', 'UserController@assignPanicAlert');
    Route::post('/users/changePassowrd/{slug}', 'UserController@changePassowrd');
    Route::resource('/users', 'UserController');
    Route::post('/users/getNumUsers', 'UserController@getNumUsers');
    Route::get('/user-checks/details', 'UserCheckController@details');
    Route::resource('/user-checks', 'UserCheckController');
    Route::post('/numalphas', 'UserController@getNumResidents');
    Route::get('/getAalphUsers', 'UserController@aplhasUsersList');


    Route::post('/visitors/changeStatus', 'VisitorController@changeStatus');
    Route::delete('/visitors', 'VisitorController@destroyAll');
    Route::get('/visitors/lists', 'VisitorController@lists');
    Route::get('/visitors/settings/{id}', 'VisitorController@settings');
    Route::post('/visitors/updateSetting', 'VisitorController@updateSetting');
    Route::resource('/visitors', 'VisitorController');
    
    Route::get('/visitor-visits/checkVisit', 'VisitorVisitController@checkVisit');
    Route::post('/visitor-visits/checkIn', 'VisitorVisitController@checkIn');
    Route::post('/visitor-visits/checkOut', 'VisitorVisitController@checkOut');
    Route::post('/visitor-visits/changeStatus', 'VisitorVisitController@changeStatus');
    Route::resource('/visitor-visits', 'VisitorVisitController');

    Route::resource('/marketplace','MarketController');


});