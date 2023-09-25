<?php

namespace App\Console;

use App\Jobs\Admin\send_freesms_ads;
use App\Models\Notifications\Ads_sms_orders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)

    {
        $ads=Ads_sms_orders::where("software_finish","no")->first();
        if ($ads){
        if ($ads->count_receivers==$ads->software_count_receivers){
            $ads->software_finish="yes";
            $ads->save();
            Log::info("change software status");
        }
        else{
            Log::info('RUNING');
         $schedule->job(new send_freesms_ads($ads))->everyMinute();}
    }}

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
