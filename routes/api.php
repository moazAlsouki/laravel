<?php

use App\Http\Controllers\commentController;
use App\Http\Controllers\likeController;
use App\Http\Controllers\productController;
use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Auth
route::post("login",[userController::class,'login']);  
route::post("signup",[userController::class,'signUp']);
route::group(['middleware'=>['auth:sanctum']],function () {
    //Auth-logout
    route::post("logout",[userController::class,'logout']);  
    route::get("myProduct",[userController::class,'myProduct']);  

    route::post("addProduct",[productController::class,'addProduct']);
    route::post("addComment",[commentController::class,'addComment']);
    route::get("getProduct/{productID}",[productController::class,'getProduct']);
    route::get("getAllProduct",[productController::class,'getAllProduct']);
    route::get("getEdit/{productID}",[productController::class,'getProducttoEdit']);
    route::get("getcomments/{productId}",[commentController::class,'getComments']);
    route::post("removeProduct",[productController::class,'removeProduct']);
    route::post("search",[productController::class,'searchProductByName']);
    route::post("searchMyProduct",[productController::class,'searchMyProduct']);
    route::post("searchProductByType",[productController::class,'searchProductByType']);
    route::post("sortProduct",[productController::class,'sortProduct']);
    
    //route::get("getProductlist",[productController::class,'getProductlist']);
    route::get("getfoodProduct",[productController::class,'getfoodProduct']);
    route::get("getclothProduct",[productController::class,'getclothProduct']);
    route::get("getMyProduct",[userController::class,'myProduct']);
    route::get("getOtherProduct",[productController::class,'getOtherproduct']);

    route::post("like",[likeController::class,'addLike']);
    
});
