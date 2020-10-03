<?php


namespace App\Commands\AuthCommands;


use App\Repositories\DB\User\DBUserRepository;
use App\Service\reCaptchaService;
use Illuminate\Support\Facades\Auth;

class LoginUserCommand
{
    private $userRepository;
    private $captchaService;

    public function __construct(DBUserRepository $userRepository, reCaptchaService $captchaService)
    {
        $this->userRepository = $userRepository;
        $this->captchaService = $captchaService;
    }

    public function execute($request) {
        $email = $request['email'];
        $credentials = request(['email', 'password']);

        $response = $this->captchaService->handleRecaptchaCheck($request['recaptchaToken'], $request->ip());
        if ($response)
            return $response;
        if(Auth::validate($credentials)) {
            $response = $this->handle2FA($request,$email);
            if ($response)
                return $response;
            return $this->authorizeUser($request,$credentials);
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    private function authorizeUser($request,$credentials)
    {
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return (['redirect' => redirect()->intended('/')->getTargetUrl()]);
    }

    private function handle2FA($request,$email)
    {
        $user = $this->userRepository->getUserByEmail($email);
        $emailAuthEnabled = $user->settings->two_step_authentication;
        $backupPasswordAuthEnabled = $user->settings->backup_password ? true : false;
        if ($emailAuthEnabled || $backupPasswordAuthEnabled) {
            if (!$request['twoFACode'])
                return ['twoFAMethods' => ['emailAuth' => $emailAuthEnabled, 'passwordAuth' => $backupPasswordAuthEnabled]];
            if ((!$user->twoFA || $user->twoFA->token != $request['twoFACode']) && $user->settings->backup_password != $request['twoFACode'])
                return response()->json([
                    'message' => 'Invalid 2FA code'
                ], 401);
        }

        return null;
    }
}
