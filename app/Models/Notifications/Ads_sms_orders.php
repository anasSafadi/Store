<?php

namespace App\Models\Notifications;

use App\Models\Seller\Seller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads_sms_orders extends Model
{
    use HasFactory;
    protected $table="ads_sms_orders";

    protected $guarded=[];
    public function seller(){
        return $this->belongsTo(Seller::class,'seller_id');
    }

}
