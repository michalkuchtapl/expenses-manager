<?php

namespace App\Actions\Fortify;

use Kuchta\Laravel\MahAuth\Adapters\Adapter;
use Kuchta\Laravel\MahAuth\DataTransferObjects\User;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function __construct(protected Adapter $adapter)
    {
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $user = new User();
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = $input['password'];
        $user->passwordConfirmation = $input['password_confirmation'];

        return $this->adapter->createUser($user);
    }
}
