<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use App\Models\User;
use App\Models\Customer;
use App\Models\Company;
use App\Models\CustomerAddress;
use App\Models\CustomerContact;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderApproval;
use App\Models\WorkflowStep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WorkflowApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Role $role;
    protected Order $order;
    protected WorkflowStep $step;

    protected function setUp(): void
    {
        parent::setUp();

        $this->role = Role::create(['name' => 'مدیر فروش']);

        $this->user = User::create([
            'full_name' => 'مدیر تست',
            'username'  => 'workflow_api_user',
            'password'  => bcrypt('password'),
            'role_id'   => $this->role->id,
            'status'    => 'active',
        ]);

        $customer = Customer::create(['name' => 'مشتری تست', 'status' => 'active']);
        $company  = Company::create(['name' => 'شرکت تست']);
        $address  = CustomerAddress::create([
            'customer_id'  => $customer->id,
            'title'        => 'دفتر',
            'full_address' => 'آدرس تست',
            'is_default'   => true,
        ]);
        $contact = CustomerContact::create([
            'address_id' => $address->id,
            'full_name'  => 'گیرنده تست',
            'mobile'     => '09130000000',
        ]);

        $this->order = Order::create([
            'customer_id' => $customer->id,
            'company_id'  => $company->id,
            'address_id'  => $address->id,
            'contact_id'  => $contact->id,
            'is_official' => true,
            'status'      => 'pending',
            'created_by'  => $this->user->id,
        ]);

        $this->step = WorkflowStep::create([
            'name'       => 'تایید مدیر فروش',
            'step_order' => 1,
            'role_id'    => $this->role->id,
        ]);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_workflow()
    {
        $this->getJson('/api/v1/workflow/steps')->assertStatus(401);
    }

    #[Test]
    public function authenticated_user_can_get_workflow_steps()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/workflow/steps');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure(['*' => ['id', 'name', 'step_order']]);
    }

    #[Test]
    public function authenticated_user_can_get_order_approvals()
    {
        OrderApproval::create([
            'order_id' => $this->order->id,
            'step_id'  => $this->step->id,
            'status'   => 'pending',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/workflow/orders/' . $this->order->id . '/approvals');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonStructure(['*' => ['id', 'status', 'step']]);
    }

    #[Test]
    public function manager_can_approve_an_approval()
    {
        $approval = OrderApproval::create([
            'order_id' => $this->order->id,
            'step_id'  => $this->step->id,
            'status'   => 'pending',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/workflow/approvals/' . $approval->id . '/approve', [
                'description' => 'تایید شد',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'approved');

        $this->assertDatabaseHas('order_approvals', [
            'id'     => $approval->id,
            'status' => 'approved',
        ]);
    }

    #[Test]
    public function manager_can_reject_an_approval()
    {
        $approval = OrderApproval::create([
            'order_id' => $this->order->id,
            'step_id'  => $this->step->id,
            'status'   => 'pending',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/workflow/approvals/' . $approval->id . '/reject', [
                'description' => 'رد شد',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'rejected');

        $this->assertDatabaseHas('orders', [
            'id'     => $this->order->id,
            'status' => 'rejected',
        ]);
    }

    #[Test]
    public function order_becomes_approved_when_all_steps_approved()
    {
        $approval = OrderApproval::create([
            'order_id' => $this->order->id,
            'step_id'  => $this->step->id,
            'status'   => 'pending',
        ]);

        $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/workflow/approvals/' . $approval->id . '/approve');

        $this->assertDatabaseHas('orders', [
            'id'     => $this->order->id,
            'status' => 'approved',
        ]);
    }
}
