<?php

namespace BMS\API\V1\Controllers;

//use BMS\API\V1\Entities\User;
use BMS\Entities\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App;
use JWTAuth;

class APIController extends Controller
{

    use Helpers,
        ValidatesRequests;

    public function __construct()
    {
        App::register(\BMS\API\V1\Providers\APIServiceProvider::class);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        try {
            if (($user = JWTAuth::parseToken()->authenticate()) == FALSE) {
                throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('user_not_found');
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('token_expired');
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException('token_invalid');
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('token_absent');
        }

        return $user;
    }

    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\ValidationHttpException($validator->failed());
        }
    }

    public function validateArray(array $array, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($array, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\ValidationHttpException($validator->failed());
        }
    }
}
