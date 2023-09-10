<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Resources\Offers\seller_place_offer;
use App\Http\Resources\Offers\specific_product_offer;
use App\Http\Resources\sller_place\information_seller_place;
use App\Http\Resources\sller_place\information_seller_products;
use App\Models\Seller\Place as seller_place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class sellerController extends Controller
{
    public function show_seller_place_information($id_seller_place){

        $seller_place=seller_place::find($id_seller_place);
        return response()->json(["status"=>true,"data"=>new information_seller_place($seller_place)]);
    }

    public function specific_product(Request $request){

        $v=Validator::make($request->all(),[
            "seller_place_id"=>"required|integer",
            ]);
        if ($v->fails()){
            return response()->json(["status"=>false,"msg"=>[$v->errors()->all()[0]]]);
        }


        else{
            $seller_place=seller_place::find($request->seller_place_id);
            if (!$seller_place){ return  response()->json(["status"=>false,"msg"=>["Place - not fount"]]);}

            if (isset($request->sub_category_id)){
                return response()->json(["status"=>true,"product"=>information_seller_products::collection($seller_place->products_seller->where("status","=","1")->where("sub_category_seller","=",$request->sub_category_id))]);

            }
            elseif (isset($request->seller_place_offers)&&$request->seller_place_offers=="yes"){


                return response()->json(["status"=>true,"data"=>new specific_product_offer($seller_place)]);
            }
            else{
                $data=$seller_place->products_seller->where("status","=","1");
                if (!$data){ return  response()->json(["status"=>true,"msg"=>["ERROR 404 -9865"]]);}
                return response()->json(["status"=>true,"data"=>information_seller_products::collection($data)]);
            }
        }
    }

}
