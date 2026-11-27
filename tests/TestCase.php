<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function makeAdmin(): User
    {
        $user = User::factory()->create();
        // "role" is not mass assignable, so set it explicitly.
        $user->role = User::ROLE_ADMIN;
        $user->save();

        return $user;
    }

    protected function makeCustomer(): User
    {
        return User::factory()->create();
    }
}
