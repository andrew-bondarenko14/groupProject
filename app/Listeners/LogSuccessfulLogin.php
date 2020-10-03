<?php

namespace App\Listeners;


use App\Mail\NewIpLoginEmail;
use App\Models\IpUser;
use App\Models\User;
use App\Models\UserSecurityLogs;
use App\Models\UserSettings;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Torann\GeoIP\Facades\GeoIP;
use Illuminate\Support\Facades\Cookie;

class LogSuccessfulLogin
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
        if(UserSecurityLogs::where('id',Auth::id())->first() && UserSecurityLogs::where('id',Auth::id())->first()->action != 'Выход из системы') {
            $log = new UserSecurityLogs();
            $log->user_id = Auth::id();
            $log->action =  'Выход из системы';
            $log->ip = GeoIP::getClientIP();
            $log->location =  $location;
            $log->created_at = Carbon::parse(request()->cookie('LastRequestTime'))->subMinute();
            $log->save();
            Cookie::queue(Cookie::make('NewIpLogin', 'value', -1));
        }

        Cookie::queue(Cookie::make('NewIpLogin', 'value', -1));
        if (UserSettings::where('user_id', '=', Auth::id())->where('security_notifications', '=', 1)->count() == 1) {
            if (IpUser::where('user_id', '=', Auth::id())->count() < 1) {
                $ipUser = new IpUser();
                $ipUser->user_id = Auth::id();
                $ipUser->ip = GeoIP::getClientIP();
                $ipUser->save();
            } else {
                if (IpUser::where('ip', '=', GeoIP::getClientIP())->where('user_id', '=', Auth::id())->count() < 1) {
                    Cookie::queue(Cookie::make('NewIpLogin', 'value', 30));

                    $user = Auth::user();
                    $warningData = new \stdClass();
                    $warningData->receiver = $user->surname . ' ' . $user->name . ' ' . $user->middle_name;
                    $warningData->location = $location;
                    Mail::to($user->email)->send(new NewIpLoginEmail($warningData));

                    $ipUser = new IpUser();
                    $ipUser->user_id = $user->id;
                    $ipUser->ip = GeoIP::getClientIP();
                    $ipUser->save();
                }
            }
        }

        $log = new UserSecurityLogs();
        $log->user_id = Auth::id();
        $log->action = 'Вход в систему';
        $log->ip = GeoIP::getClientIP();
        $log->location = $location;
        $log->save();
    }
}
