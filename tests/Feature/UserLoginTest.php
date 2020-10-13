<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    public function testUserLogsInSuccessfully()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('secret')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'secret'
        ]);

        $response->assertRedirect('/home');
        $this->assertTrue(Auth::check());
        $this->assertTrue(Auth::user()->is($user));
    }

    public function testUserLogsInUnsuccessfully()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('secret')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'incorrect-password'
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertFalse(Auth::check());
    }
}
