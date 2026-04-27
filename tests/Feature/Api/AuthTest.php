<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected Role $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->role = Role::create(['name' => 'فروش']);
    }

    private function makeUser(array $override = []): User
    {
        return User::create(array_merge([
            'full_name' => 'کاربر تست',
            'username'  => 'testuser',
            'password'  => bcrypt('password123'),
            'role_id'   => $this->role->id,
            'status'    => 'active',
        ], $override));
    }

    #[Test]
    public function user_can_login_with_correct_credentials()
    {
        $this->makeUser();

        $response = $this->postJson('/api/v1/login', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'full_name', 'username', 'role'],
            ]);
    }

    #[Test]
    public function user_cannot_login_with_wrong_password()
    {
        $this->makeUser();

        $response = $this->postJson('/api/v1/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'نام کاربری یا رمز عبور اشتباه است']);
    }

    #[Test]
    public function inactive_user_cannot_login()
    {
        $this->makeUser(['status' => 'inactive']);

        $response = $this->postJson('/api/v1/login', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'حساب کاربری غیرفعال است']);
    }

    #[Test]
    public function authenticated_user_can_get_profile()
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/me');

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'full_name', 'username', 'role', 'status']);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_protected_routes()
    {
        $response = $this->getJson('/api/v1/me');

        $response->assertStatus(401);
    }

    #[Test]
    public function authenticated_user_can_logout()
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'خروج موفق']);
    }
}
