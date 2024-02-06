<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ESUBaseController;
use Illuminate\Http\Request;

class UserController extends ESUBaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|void
     * @throws \App\Exceptions\SystemErrorExcept
     */
    public function login(Request $request)
    {
        $input = $request->only('username', 'password');
        $jwt_token = null;
        if (!$jwt_token = auth()->attempt($input)) {
            $code = 20006;
            $msg = trans('login.invalid');
            return $this->error($msg, $code);
        } else {
            $user = auth()->user();
            $code = 1;
            $data = [
                'token' => $jwt_token,
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user_information' => $user
            ];
            $msg = trans('login.success');
        }
        return $this->response($code, $data, $msg);
    }

    public function getUser(Request $request)
    {
        $data = auth()->user();
        $data['roles'] = ['admin'];
        return $this->response($code = 1, $data, $msg = 'success');
    }

    public function logout(Request $request){
        $data = null;
        auth()->invalidate($request->token);
        $code = 1;
        $msg = trans('logout.success');
        return $this->response($code, $data, $msg);
    }
}
