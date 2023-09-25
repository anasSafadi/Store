<?php

namespace App\Http\Resources\Traders;

use Illuminate\Http\Resources\Json\JsonResource;

class sms_ads_orders extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->status=="pending"){
            $this->status="قيد الانتظار";

        }elseif($this->status=="accept"){
            $this->states="تم قبول الاعلان -تم الارسال";

        }elseif($this->status=="reject"){
            $this->status="مرفوض";

        }else{

        }
        return [
            "message"=>$this->message_of_ads,
            "states_of_ads_order"=>$this->status,
            "count_receivers_Ads"=>$this->software_count_receivers
        ];
    }
}
