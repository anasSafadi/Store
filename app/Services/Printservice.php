<?php


namespace App\Services;


use Illuminate\Support\Facades\Log;

class Printservice
{
    public function getMessage($data)
    {
        Log::info($data);
    }

}