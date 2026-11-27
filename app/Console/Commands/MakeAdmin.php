<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class MakeAdmin extends Command
{
    protected $signature = 'agrimarket:make-admin';

    protected $description = 'Create an administrator account, or promote an existing user';

    public function handle(): int
    {
        $email = Str::lower(trim((string) $this->ask('Admin email')));
        $user = User::where('email', $email)->first();

        if ($user !== null) {
            if (! $this->confirm("{$email} already exists. Make this user an administrator?")) {
                return self::FAILURE;
            }
        } else {
            $data = [
                'email' => $email,
                'name' => (string) $this->ask('Admin name'),
                'password' => (string) $this->secret('Password'),
                'password_confirmation' => (string) $this->secret('Confirm password'),
            ];

            $validator = Validator::make($data, [
                'email' => ['required', 'email', 'max:255'],
                'name' => ['required', 'max:255'],
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->error($error);
                }

                return self::FAILURE;
            }

            $user = new User(['name' => $data['name'], 'email' => $email, 'password' => $data['password']]);
        }

        // "role" is not mass assignable, so it is set explicitly.
        $user->forceFill(['role' => User::ROLE_ADMIN])->save();

        $this->info("{$email} is now an administrator.");

        return self::SUCCESS;
    }
}
