<?php

namespace App\Jobs\Admin;

use App\Models\Notifications\History_sms;
use App\Models\Notifications\Phone_numbers;
use App\Models\Notifications\Phones_ads;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class send_freesms_ads implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public  $the_ads;
    public function __construct($ads)
    {
        $this->the_ads=$ads;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $count_recivers=$this->the_ads->count_receivers; //عدد المستلمين


        $msg=$this->the_ads->message_of_ads; //الرسالة

        for($i=0;$i< $count_recivers;$i++){
            Log::info("insder loop");
            $phone=Phone_numbers::where("status","=","up")
                ->whereBetween('remain_messages', [0, 100])->first();

            if (!$phone){
                Log::info("no phones ");
            }
            $to=Phones_ads::orderBy('count_rec_msg', 'asc')->first();
            if (!$to){
                Log::info("no ads phones ");
            }
            Log::info("count_receivers :".$this->the_ads->count_receivers);
            Log::info("software_count_receivers :".$this->the_ads->software_count_receivers);

            if ($this->the_ads->count_receivers > $this->the_ads->software_count_receivers && $phone && $to){


            $this->send_telegram_message($phone,$to,$msg);}
            elseif($this->the_ads->count_receivers == $this->the_ads->software_count_receivers){
                Log::info("finish job");
                $this->the_ads->software_finish=="yes";
                Log::info("software finish");
                $this->the_ads->save();
            }
            else{
                Log::info("5505");
            }
        }
        Log::info("after loop ");


    }

    public function send_telegram_message($phone,$to,$message){


        $response = Http::withHeaders([



        ])->get('http://127.0.0.1/user-index/order/store', [
            "message"=> $message,

        ]);

        if(200==200){
//            success
            $phone->remain_messages=$phone->remain_messages-1;
            $to->count_rec_msg=$to->count_rec_msg+1;
            $this->the_ads->software_count_receivers=$this->the_ads->software_count_receivers+1;

            $phone->save();
            $to->save();

            //errrrrrrrrrrrrrrrrrrro
            $this->the_ads->save();
            if ($this->the_ads->software_count_receivers==$this->the_ads->count_receivers){
                $this->the_ads->software_finish="yes";
                $this->the_ads->save();

            }

            $history_sms=History_sms::create([
                "body_of_api"=>'body_of_api',
                "message"=>$message,
                "destinations"=>$to,
                "status_of_sms"=>"success",

                "phones_number_id"=>$phone->id, //  used _number_for send
            ]);



        }else{
//            falid

            try{
                $history_sms=History_sms::create([
                    "body_of_api"=>$response->body(),
                    "message"=>$message,
                    "destinations"=>$to,
                    "states_of_sms"=>"failed",

                    "phones_number_id"=>$phone->id, //  used _number_for send
                ]);

            }catch (\Exception $e){
                $history_sms=History_sms::create([
                    "body_of_api"=>$response->body(),
                    "message"=>$message,
                    "destinations"=>$to,
                    "states_of_sms"=>"failed",

                    "phones_number_id"=>$phone->id, //  used _number_for send
                ]);
            }





        }
    }

    public function connection_to_jawwal($phone,$to,$message){


        $response = Http::withHeaders([
            "Authorization"=>$phone->token,

            "Channel"=>"Website",
            "Content-Type"=>"application/json",
            "Lang"=>"AR",
            "Origin"=>"https://myaccount.jawwal.ps",
            "Referer"=>"https://myaccount.jawwal.ps/",
            "Sec-Fetch-Mode"=>"cors",
            "User-Agent"=>"Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36",



        ])->post('https://hisabiapi.jawwal.ps/api/SmsFree/SendFreeSMS', [
            "Destmsisdn"=> $to,
            "MsgText"=> $message,
            "deviceId"=> $phone->device_id,
            "lang"=> "EN"
        ]);

        if($response->body()){
//            success
            $phone->remain_messages=$phone->remain_messages-1;
            $to->count_rec_msg=$to->count_rec_msg+1;
            $this->the_ads->software_count_receivers=$this->the_ads->software_count_receivers+1;

            $phone->save();
            $to->save();

            //errrrrrrrrrrrrrrrrrrro
            $this->the_ads->save();

            $history_sms=History_sms::create([
                "body_of_api"=>$response->body(),
                "message"=>$message,
                "destinations"=>$to,
                "states_of_sms"=>"failed",

                "phones_number_id"=>$phone->id, //  used _number_for send
            ]);



        }else{
//            falid

            try{
                $history_sms=History_sms::create([
                    "body_of_api"=>$response->body(),
                    "message"=>$message,
                    "destinations"=>$to,
                    "states_of_sms"=>"failed",

                    "phones_number_id"=>$phone->id, //  used _number_for send
                ]);

            }catch (\Exception $e){
                $history_sms=History_sms::create([
                    "body_of_api"=>$response->body(),
                    "message"=>$message,
                    "destinations"=>$to,
                    "states_of_sms"=>"failed",

                    "phones_number_id"=>$phone->id, //  used _number_for send
                ]);
            }





        }
    }

}
