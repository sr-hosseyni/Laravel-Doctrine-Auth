<?php

namespace Tournament\API\V1\Providers;

use Tournament\API\V1\Entities\User;
use Tournament\API\V1\Repositories\UserRepository;
use Tournament\Entities\Traits\Authenticatable as AuthenticatableTrait;
use Tournament\Providers\Traits\AuthServiceProvider as AuthServiceProviderTrait;
use Dingo\Api\Auth\Provider\Authorization;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Tymon\JWTAuth\Providers\Auth\AuthInterface;
use Tymon\JWTAuth\Providers\User\UserInterface;

class AuthServiceProvider extends Authorization implements AuthInterface, UserProvider, UserInterface
{

    use AuthServiceProviderTrait;

    /**
     * Authenticate a user via the id.
     *
     * @param  mixed $id
     *
     * @return User|bool
     */
    public function byId($id)
    {
        /** @var User $user */
        $user = \App::make(UserRepository::class)->find($id);

        $this->user = $user;

        return ($user == NULL) ? (FALSE) : ($user);
    }

    /**
     * Authenticate a user via the given key and value.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return User|bool
     */
    public function getBy($key, $value)
    {
        /** @var User $user */
        $user = \App::make(UserRepository::class)->findOneBy(
            array(
                $key => $value,
            )
        );

        $this->user = $user;

        return ($user == NULL) ? (FALSE) : ($user);
    }

    /**
     * Check a user's credentials.
     *
     * @param  array $credentials
     *
     * @return User|bool
     */
    public function byCredentials(array $credentials = [])
    {
        /** @var User $user */
        $user = \App::make(UserRepository::class)->findOneBy(
            array(
                'email' => $credentials['email'],
            )
        );

        $this->user = $user;

        return ($user == NULL) ? (FALSE) : ($user);
    }

    /**
     * @param Authenticatable|User $user
     * @param array                $credentials
     *
     * @return boolean
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (hasTrait($user, AuthenticatableTrait::class) == TRUE) {
            return $user->matchesPassword($credentials['password']);
        } else {
            return $user->getAuthPassword() == $credentials['password'];
        }
    }
}
