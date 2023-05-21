<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserService
{
    /**
     * Create a new user
     *
     * @param null $name
     * @param null $email
     * @param null $password
     * @return User
     */
    public static function create($name = NULL, $email = NULL, $password = NULL)
    {
        // init Faker
        $faker = Faker::create();

        // create new user
        $user = new User();
        $user->name = $name ?: $faker->name;
        $user->email = $email ?: $faker->safeEmail;
        $user->role = User::ROLE_BOT;
        $user->status = User::STATUS_ACTIVE;
        $user->password = Hash::make(str_random(8));
        $user->remember_token = str_random(10);
        $user->last_login_ip = $faker->ipv4;
        $user->last_login_time = Carbon::now();
        $user->save();

        // throw Registered event
        event(new Registered($user));

        return $user;
    }
}