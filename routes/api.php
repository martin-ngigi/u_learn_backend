<?php

//use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 * No need of importing the Controllers since it's already imported globally in the /Providers/RouteService.php i.e.     protected $namespace = "App\Http\Controllers";

 */
Route::group(['namespace'=>'Api'], function(){
    //   api/login
    //Route::post('/login',[UserController::class, 'createUser'] );
    
    Route::post('/login','UserController@createUser' );
    
    Route::group(['middleware'=>['auth:sanctum']], function(){
        Route::any('/course-list', 'CourseController@courseList');
        Route::any('/course-detail', 'CourseController@courseDetail');
        Route::any('/checkout', 'CourseController@checkout');

    });

});

 // clear cache
// THIS WILL SOLVE ERROR: There is no existing directory at /storage/logs and its not buildable: Permission denied
Route::get('/reset', function (){
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');

    return ["status_code" => 200, "message" => "Reset successfully"];
});
