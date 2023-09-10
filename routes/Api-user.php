<?php

use App\Http\Controllers\Api\orders\Seller_ordersController;


use App\Http\Controllers\Api\orders\Pool_ordersController;

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\Save_byController;

/*****************orders***********/
Route::middleware(['jwt','Only_users','active_user'])->group(function () {
    Route::get('/get/user/information',[UserController::class,'get_my_information']);
    Route::post('/edit/user/information',[UserController::class,'edit_user_information']);

    Route::post('/make/order/for/seller',[Seller_ordersController::class,'make_seller_order']);
    Route::post('/make/offer-order/for/seller',[Seller_ordersController::class,'make_offer_seller_order']);
    Route::post('/delete/seller/order/{id_order}',[Seller_ordersController::class,'delete_seller_order']);




    Route::post('/make/order/for/pool',[Pool_ordersController::class,'make_pool_order']);


    Route::get('/delete/pool_order/{id_order}',[Pool_ordersController::class,'delete_pool_order']);


    Route::post('/make/offer-order/for/pool',[Pool_ordersController::class,'make_pool_order_offer']);


    Route::get('/user/get/my/seller/orders',[UserController::class,'get_my_seller_orders']);

    Route::get('/user/get/my/pool/orders',[UserController::class,'get_my_pool_orders']);


    /**rating**/

    Route::post('/pool_place/make/rating',[RatingController::class,'make_rating_pool_place']);

    Route::post('/seller_place/make/rating',[RatingController::class,'make_rating_seller_place']);
    /**save post**/


    Route::post('/user/save/pool_place',[Save_byController::class,'save_pool_place']);
    Route::post('/user/save/seller_place',[Save_byController::class,'save_seller_place']);

    Route::post('/user/delete/save/pool_place',[Save_byController::class,'delete_save_pool_place']);
    Route::post('/user/delete/save/seller_place',[Save_byController::class,'delete_save_seller_place']);




    Route::get('/user/get/save/pool_place',[Save_byController::class,'get_save_pool_place']);

    Route::get('/user/get/save/seller_place',[Save_byController::class,'get_save_seller_place']);



    /**end save post**/

});