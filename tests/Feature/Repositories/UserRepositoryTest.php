<?php

namespace Tests\Feature\Repositories;

use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRepository $repo;
    protected Role $role;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(UserRepository::class);
        $this->role = Role::create(['name' => 'فروش']);
    }

    private function makeUser(array $override = []): User
    {
        static $counter = 0;
        $counter++;

        return User::create(array_merge([
            'full_name' => 'کاربر تست',
            'username'  => 'user_' . $counter,
            'password'  => bcrypt('password'),
            'role_id'   => $this->role->id,
            'status'    => 'active',
        ], $override));
    }

    #[Test]
    public function it_finds_user_by_username()
    {
        $this->makeUser(['username' => 'ali123']);

        $found = $this->repo->findByUsername('ali123');

        $this->assertNotNull($found);
        $this->assertEquals('ali123', $found->username);
    }

    #[Test]
    public function it_returns_null_for_unknown_username()
    {
        $found = $this->repo->findByUsername('nobody');

        $this->assertNull($found);
    }

    #[Test]
    public function it_returns_only_active_users()
    {
        $this->makeUser(['status' => 'active']);
        $this->makeUser(['status' => 'active']);
        $this->makeUser(['status' => 'inactive']);

        $results = $this->repo->getActiveUsers();

        $this->assertCount(2, $results);
    }

    #[Test]
    public function it_searches_users_by_name()
    {
        $this->makeUser(['full_name' => 'علی احمدی']);
        $this->makeUser(['full_name' => 'علی رضایی']);
        $this->makeUser(['full_name' => 'رضا محمدی']);

        $results = $this->repo->searchByName('علی');

        $this->assertCount(2, $results);
    }

    #[Test]
    public function it_gets_user_with_role()
    {
        $user = $this->makeUser();

        $found = $this->repo->getUserWithRole($user->id);

        $this->assertNotNull($found->role);
        $this->assertEquals('فروش', $found->role->name);
    }
}
