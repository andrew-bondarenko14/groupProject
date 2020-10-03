<?php

namespace App\Providers;

use App\Mail\SecurityChangePassEmail;
use App\Mail\SecurityTwoFAEmail;
use App\Models\IpUser;
use App\Models\User;
use App\Models\UserSecurityLogs;
use App\Models\UserSettings;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Event;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Torann\GeoIP\Facades\GeoIP;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],

        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\LogSuccessfulLogout',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();


        User::updating(function ($user)
        {
                if($user->isDirty()) {
                    $locationObj = geoip()->getLocation(GeoIP::getClientIp());
                    $location = implode(',', [$locationObj->city, $locationObj->state_name, $locationObj->country]);
                    $dirty = $user->getDirty();
                    foreach ($dirty as $field => $value) {
                        $action = 'Ошибка при получении действия';
                        if ($field == 'password') {
                            $settings = $user->settings;
                            $settings->password_changed_at = Carbon::now();
                            $action = 'Изменен пароль';
                            $log = new UserSecurityLogs();
                            $log->user_id = $user->id;
                            $log->action = $action;
                            $log->ip = GeoIP::getClientIP();
                            $log->location = $location;
                            $log->save();

                            if (Cookie::has('NewIpLogin')) {
                                $warningData = new \stdClass();
                                $warningData->receiver = $user->surname . ' ' . $user->name . ' ' . $user->middle_name;
                                $warningData->location = $location;
                                Mail::to($user->email)->send(new SecurityChangePassEmail($warningData));
                                IpUser::where('ip', '=', GeoIP::getClientIP())->where('user_id', '=', $user->id)->delete();
                            }
                        }
                    }
                }
        });

        UserSettings::updating(function ($settings)
        {
            if($settings->isDirty()) {
                $locationObj = geoip()->getLocation(GeoIP::getClientIp());
                $location = implode(',', [$locationObj->city, $locationObj->state_name, $locationObj->country]);
                $dirty = $settings->getDirty();
                foreach ($dirty as $field => $value) {
                    $action = 'Ошибка при получении действия';
                    if ($field == 'backup_password') {
                        if ($value)
                            $action = 'Установлен резеврный пароль';
                        else
                            $action = 'Отключен резеврный пароль';
                    }
                    if ($field == 'backup_email') {
                        $action = 'Установлен резеврный email';
                    }
                    if ($field == 'codeword') {
                        if ($value)
                            $action = 'Установлено кодовое слово';
                        else
                            $action = 'Убрано кодовое слово';
                    }
                    if ($field == 'security_notifications') {
                        if ($value)
                            $action = 'Включены уведомления о подозрительных входах';
                        else
                            $action = 'Выключены уведомления о подозрительных входах';
                    }
                    if ($field == 'two_step_authentication') {
                        if ($value)
                            $action = 'Включена двухэтапная аутентификация';
                            if (Cookie::has('NewIpLogin')) {
                                $warningData = new \stdClass();
                                $warningData->receiver = $user->surname . ' ' . $user->name . ' ' . $user->middle_name;
                                $warningData->location = $location;
                                Mail::to($user->email)->send(new SecurityTwoFAEmail($warningData));
                                IpUser::where('ip', '=', GeoIP::getClientIP())->where('user_id', '=', $user->id)->delete();
                            }
                        else
                            $action = 'Выключена двухэтапная аутентификация';
                    }
                    if ($field != 'password_changed_at') {
                        $log = new UserSecurityLogs();
                        $log->user_id = Auth::id();
                        $log->action = $action;
                        $log->ip = GeoIP::getClientIP();
                        $log->location = $location;
                        $log->save();
                    }
                }
            }
        });


        }


}
