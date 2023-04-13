<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\User;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirect(string $driver)
    {
        abort_unless(in_array($driver, config('services.socialite.drivers')), 404);

        return Socialite::driver($driver)->redirect();
    }

    public function callback(string $driver)
    {
        try {
            $driverUser = Socialite::driver($driver)->user();
        } catch (\Exception $exception) {
            report($exception);

            flash()->error("Error while logging in with $driver");
            auth()->logout();
            return redirect()->route('login');
        }

        if (!$driverUser) {
            flash()->error("Error while logging in with $driver");
            auth()->logout();
            return redirect()->route('login');
        }

        $user = User::query()
            ->orWhere('email', $driverUser->getEmail())
            ->firstOrNew();

        if (!$user->id) {
            $user->email = $driverUser->getEmail();
            $user->name = $driverUser->getName();
            $user->password = \Str::password(16);
        }

        $user->{"{$driver}_id"} = $driverUser->getId();
        $user->{"{$driver}_token"} = $driverUser->token;
        $user->{"{$driver}_refresh_token"} = $driverUser->refresh_token;

        $user->save();
        \Auth::login($user);

        return redirect()->route('dashboard');
    }
}
