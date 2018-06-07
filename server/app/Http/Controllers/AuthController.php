<?php

namespace App\Http\Controllers;

use Exception;
use Iwanli\Wxxcx\Wxxcx;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    protected $wxxcx;

    protected $userRepository;

    public function __construct(Wxxcx $wxxcx, UserRepository $userRepository)
    {
        parent::__construct();

        $this->wxxcx = $wxxcx;
        $this->userRepository = $userRepository;
    }

    public function askSession(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|string'
        ]);

        try {
            $userInfo = $this->wxxcx->getLoginInfo($request->code);
            $user = $this->userRepository->getById($userInfo['openid']);
            $token = \JWTAuth::fromUser($user);

            return $this->response->success(compact('token'));
        } catch (JWTException $e) {
            return $this->response->withInternalServer('could not create token');
        } catch (Exception $e) {
            return $this->response->withUnauthorized($e->getMessage());
        }
    }
}
