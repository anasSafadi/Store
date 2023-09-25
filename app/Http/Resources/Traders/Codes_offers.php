<?php

namespace App\Http\Resources\Traders;

use Illuminate\Http\Resources\Json\JsonResource;

class Codes_offers extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $who_Add='Seller';
        if (is_null($this->seller_place_id)){
            $who_Add="Admin";
        }
        return [
            "id"=>$this->id,
            "code"=>$this->code,
            "add_by"=>$who_Add
        ];
    }
}
