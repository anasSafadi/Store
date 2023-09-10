<?php

use \App\Http\Controllers\Api\Traders\TradersController;
use \App\Http\Controllers\Api\Traders\AdsController;
use Illuminate\Support\Facades\Route;


Route::middleware(['jwt','Only_sellers'])->group(function () {


    Route::middleware(['Api','Only_post_request'])->group(function () {
    Route::any('/trader/register/project',[TradersController::class,'register_project_for_traders']);
    Route::any('/trader/get/project/information',[TradersController::class,'get_project_information']);
    Route::any('/trader/create/category',[TradersController::class,'create_category']);
    Route::any('/trader/create/product',[TradersController::class,'create_product']);
    Route::any('/trader/create/offer/code',[TradersController::class,'create_offer_code']);


    Route::any('/trader/register/sms/ads/order',[AdsController::class,'sms_ads']);

    });









    Route::middleware(['Api','Only_get_request'])->group(function () {
        Route::any('/trader/get/all/category',[TradersController::class,'get_category_seller']);

    Route::any('/trader/get/sms/ads/orders',[AdsController::class,'sms_ads_orders']);
    Route::any('/trader/get/category',[TradersController::class,'get_category']);
    Route::any('/trader/get/product',[TradersController::class,'get_product']);
    Route::any("/trader/show/offers-codes",[TradersController::class,"show_offer_code"])->name('seller_show_my_codes');
    Route::any('/trader/delete/offer/code/{id_offer}',[TradersController::class,'delete_offer_code']);
    Route::any('/trader/edit/ads/{id}',[TradersController::class,'delete_offer_code']);

    });



});
