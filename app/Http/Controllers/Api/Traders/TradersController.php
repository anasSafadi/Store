<?php

namespace App\Http\Controllers\Api\Traders;

use App\Http\Controllers\Controller;

use App\Http\Resources\Traders\Codes_offers;
use App\Models\Admin\codes\Seller_code;
use App\Models\Files;
use App\Models\Seller\Place as seller_place;
use App\Models\Seller\Seller;
use App\Models\Seller\seller_product;
use App\Models\Seller\sub_category_seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TradersController extends Controller
{
    public function get_category_seller(){
        $id_seller=auth("seller_api")->user()->id;

        $data= $eixst_title=sub_category_seller::where('seller_id',$id_seller)
            ->get();

        return response()->json(["status"=>true,"data"=>$data]);
    }
   public function register_project_for_traders(Request $request){


       $v=Validator::make($request->all(),[
           "title"=>"required|string",
           "description"=>"required|string",
           "time_work_from"=>"required|integer|between:1,12",
           "time_work_to"=>"required|integer|between:1,12",
           "logo_project"=>"required|image",
           //"days_of_works"=>"required|array|distinct|between:1,7",
           "region_id"=>"required|integer|between:1,3",
           "area_id"=>"required|integer",


       ]);
       if ($v->fails()){
           return response()->json(["status"=>false,"msg"=>"lack information","error"=>$v->errors()->all()]);

       }

       $seller=Seller::find(auth("seller_api")->user()->id);
       if ($seller->place){
           return response()->json(["status"=>false,"message"=>" تم اضافة مشروعك مسبقا غير مسموح لك اضافة اكثر من مشروع "]);
       }


       DB::beginTransaction();

       try{
       $place=seller_place::create(
           [
               "title_of_place"=>$request->title,
               "description_of_place"=>$request->description,
               "seller_id"=>$seller->id,
               "state"=>"0",
               "time_work"=>"$request->time_work_from"."صباحا"."-"."$request->time_work_to"."مساء",
               "place_phone"=>$seller->phone,
               "region_id"=>$request->region_id,
               "area_id"=>$request->area_id,
               "category_id"=>$seller->category->id,

           ]);
           $request->days_of_works=[1,2];
       foreach ($request->days_of_works as $x=>$day){
           DB::table("the_seller_days")->insert([
               "seller_place_id"=>$place->id,
               "day_id"=>$day
           ]);
       }



       if (isset($request['logo_project'])) {
           $file=$request->file("logo_project");
           $file_ex=$file->extension();
           $fileOriginalName=$file->getClientOriginalName();
           $un_file_name=uniqid().".".$file_ex;
           $file->storeAs("/all_files","$un_file_name");

           Files::create([
               "url"=>$un_file_name,
               "client_name"=>$fileOriginalName,
               "fileable_id"=>(int)$place->id,
               "fileable_type"=>"App\Models\seller\place"
           ]);


       }
           DB::commit();
           return response()->json(["status"=>true,"msg"=>"تم تسجيل مشروعك انتظر حتى يتم قبولة من الادمن سوف يصلك اشعار عند القبول"]);
       }catch (\Exception $e) {

           DB::rollback();

         return response()->json(["status"=>false,"msg"=>"ERROR in CODE"]);


       }

        return response()->json(["status"=>true,"msg"=>"تم تسجيل مشروعك انتظر حتى يتم قبولة من الادمن سوف يصلك اشعار عند القبول"]);
   }
    public function create_offer_code(Request $request){
        $place=seller_place::where("seller_id","=",auth("seller_api")->user()->id)->first();
        if (!$place){
            return response()->json(["status"=>false,"msg"=>["يجب عليك اضافة مشروعك التجاري اولا"]]);
        }


        $v=Validator::make($request->all(),[
            'code'=>"required",
            'offer'=>'integer|required|between:1,99',
            "max_use"=>"required|integer"

        ]);
        if ($v->fails()){
          return response()->json(["status"=>false,"msg"=>$v->errors()->all()]);
        }else{
            $v2=Validator::make($request->all(),[
                'code'=>"required|unique:seller_codes,code",
            ]);
            if ($v2->fails()){
                return response()->json(["status"=>false,"msg"=>["تم استخدام هذا الكود مسبقا الرجاء اختيار كود اخر"]]);

            }else{
                $code=Seller_code::create([
                'code'=>"$request->code",
                'offer'=>$request->offer,
                "max_use"=>$request->max_use,
                'category_id'=>$place->category_id,
                'seller_place_id'=>$place->id
            ]);
                return response()->json(["status"=>true,"msg"=>["تم اضافة كود الخصم"]]);
                }


        }


    }
    public function delete_offer_code($id_code){


       if (isset($id_code) && $id_code>0){
           $place=seller_place::where("seller_id","=",auth("seller_api")->user()->id)->first();
           if (!$place){
               return response()->json(["status"=>false,"msg"=>"انت لايوجد لك مشروع تجاري لا يمكنك طلب هذه الخدمة"]);
           }
           else{
               $code=Seller_code::find($id_code);
               if ($code){
                   if ($place->id==$code->seller_place_id){
                       $code->delete();
                           return response()->json(["status"=>true,"msg"=>"تم الحذف بنجاح"]);

                   }
                   else{ return response()->json(["status"=>false,"msg"=>"لا يمكنك حذف هذا الكود لانه غير ملكك"]);}

               }
               else{ return response()->json(["status"=>false,"msg"=>"The id of code not found"]);}

           }

       }else{
           return response()->json(["status"=>false,"msg"=>"الرجاء اضافة id"]);
       }
    }
    public function show_offer_code(){
        $place=seller_place::where("seller_id","=",auth("seller_api")->user()->id)->first();
        if (!$place){
            return response()->json(["status"=>false,"msg"=>["يجب عليك اضافة مشروعك التجاري اولا"]]);
        }

        $codes=Seller_code::where("category_id","=",$place->category_id)->where("seller_place_id","=",$place->id)->orwhere("seller_place_id","=",null)->get();
        if (!$codes){
            $codes=[];
        }

            return response()->json(["status"=>true,"data"=>Codes_offers::collection($codes)]);


    }






    public function create_category (Request $request){
        $id_seller=auth("seller_api")->user()->id;



        $seller=Seller::findorfail($id_seller);

        if($seller->place==null){
            return response()->json(['status'=>true,"msg"=>['لايمكنك طلب هذه الخدمة عليك تسجيل مشروعك اولا']]);

        }



        $v=Validator::make($request->all(),[
            "title"=>"required|string",

        ]);
        if ($v->fails()){
           return response()->json(['status'=>false,'msg'=>$v->errors()->all()]);
        }
        else{

            $eixst_title=sub_category_seller::where('seller_place_id',$seller->place->id)
                ->where('title',$request->title)
                ->first();
            if($eixst_title){
                return response()->json(['status'=>false,'msg'=>['تم اضافة هذا الصنف مسبقا']]);

            };

            sub_category_seller::create([
                "title"=>$request->title,
                "seller_id"=>$id_seller,
                "seller_place_id"=>$seller->place->id,
            ]);

        }
        return response()->json(['status'=>true,'msg'=>['تم تسجيل الصنف بنجاح']]);
    }
    public function create_product (Request $request){


        $v=Validator::make($request->all(),[

            "title"=>"required|string",
            "description"=>"required|string",
            "price"=>"required|integer",
            "price_ex"=>"required|string",
            "count_ex"=>"required|integer",
            "category_id"=>'required|integer',
           "file"=>"required|image"

        ]);
        if ($v->fails()){
            return response()->json(['status'=>false,'msg'=>$v->errors()->all()]);
        }
        $id_seller=auth('seller_api')->user()->id;
        $exist_category=sub_category_seller::where('seller_id',$id_seller)
            ->where("id",$request->category_id)->first();
        if(!$exist_category){
            return response()->json(['status'=>false,'msg'=>[' لا ينتمي الى هذا التاجر'." ".'category id']]);
        }


        $seller=Seller::find($id_seller);
        if(!$seller->place){
            return response()->json(['status'=>false,'msg'=>'You dont have place']);

        }



            $product=seller_product::create(
                [
                    "title"=>$request->title,
                    "description"=>$request->description,
                    "sub_category_seller"=>$request->category_id,
                    "price"=>$request->price,
                    "name_ex"=>$request->price_ex,
                    "count_ex"=>$request->count_ex,
                    'ex_price'=>"لكل"." ".$request->count_ex ." ". $request->price_ex,
                    "seller_id"=>$id_seller,
                    "seller_place_id"=>$seller->place->id

                ]);
            if (isset($request['file'])) {
                $file=$request->file("file");
                $file_ex=$file->extension();
                $fileOriginalName=$file->getClientOriginalName();
                $un_file_name=uniqid().".".$file_ex;
                $file->storeAs("/all_files","$un_file_name");
                Files::create([
                    "url"=>$un_file_name,
                    "client_name"=>$fileOriginalName,
                    "fileable_id"=>(int)$product->id,
                    "fileable_type"=>"App\Models\Seller\seller_product"
                ]);
            }

        return response()->json(['status'=>true,'msg'=>['تم الاضافة بنجاح']]);



    }
//    public function get_category(){
//        $place=seller_place::where("seller_id","=",auth("seller_api")->user()->id)->first();
//        if (!$place){
//            return response()->json(["status"=>false,"msg"=>"يجب عليك اضافة مشروعك التجاري اولا"]);
//        }else{
//
//            return $place->sub_category_seller;
//        }
//    }
//
//    public function get_product(){}
    public function get_project_information(){
        $place=seller_place::where("seller_id","=",auth("seller_api")->user()->id)->first();
        if (!$place){
            return response()->json(["status"=>false,"msg"=>["يجب عليك اضافة مشروعك التجاري اولا"]]);
        }else{

            unset($place['created_at']);
            unset($place['updated_at']);
             return response()->json(['status'=>true,'data'=>$place]);
        }

}}
