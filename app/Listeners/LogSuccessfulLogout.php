<?php

namespace App\Listeners;

use App\Models\UserSecurityLogs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Torann\GeoIP\Facades\GeoIP;

class LogSuccessfulLogout
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $locationObj = geoip()->getLocation(GeoIP::getClientIP());
        $location = implode(',', [$locationObj->city, $locationObj->state_name, $locationObj->country]);
        $log = new UserSecurityLogs();
        $log->user_id = Auth::id();
        $log->action = 'Выход из системы';
        $log->ip = GeoIP::getClientIP();
        $log->location =  $location;
        $log->save();
    }
}
