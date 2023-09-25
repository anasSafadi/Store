<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\informationController;
use App\Http\Controllers\Api\orders\Seller_ordersController;
use App\Http\Controllers\Api\poolControlller;
use App\Http\Controllers\Api\sellerController;
use App\Http\Controllers\Api\orders\Pool_ordersController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\Save_byController;
use App\Http\Controllers\Api\Traders\TradersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "Api" middleware group. Enjoy building your API!
|
*/
Route::middleware(['Only_get_request'])->group(function (){
    Route::any('/categories',[informationController::class,'get_categories']);
    Route::any('/get/all_place_of_category/{id_category}',[informationController::class,'get_place_of_category']);
    Route::any('/show/seller_place/{id_seller_place}',[sellerController::class,'show_seller_place_information']);
    Route::any('/show/pool_place/{id_pool_place}',[poolControlller::class,'show_pool_place_information']);
    Route::any('/get/pool/place/of/offers',[OfferController::class,'get_pool_place_of_offers']);
    Route::any('/show/pool/place/of/offer/{id_of_pool}',[OfferController::class,'show_pool_place_of_offer']);
    Route::any('/get/FQA',[informationController::class,'get_fqa']);


    Route::get('/get/sellers/offers',[OfferController::class,'get_seller_place_of_offers']);

    Route::any('/get/seller/place/offer/{id_seller_place}',[OfferController::class,'show_seller_place_of_offer']);


});
/** pooooooooooooooooost */

Route::middleware(['Only_post_request'])->group(function (){
    Route::any('/seller_place/show/specific/product',[sellerController::class,'specific_product']);
    Route::any('filtering/data',[informationController::class,'filtering_data']);
    Route::any("/whatsapp/msg",[informationController::class,"whats_msg"]);

});





Route::post('/who_iam',[informationController::class,'who_iam']);

/**Route::get('/randomly/seller_product',[informationController::class,'get_random_products']);**/


//Route::get('/get/sellers/offers',[informationController::class,'get_place_of_category']);










/**seller**/



Route::middleware(['Api','Only_post_request'])->group(function () {
    Route::any('/user/active/while-register',[AuthController::class,'active_user_while_register']);


    Route::any('/person/register',[AuthController::class,'register']);


    Route::any('/user/me',[AuthController::class,'me']);
    Route::any('/user/logout',[AuthController::class,'logout']);

});


Route::any('/person/login',[AuthController::class,'login']);


