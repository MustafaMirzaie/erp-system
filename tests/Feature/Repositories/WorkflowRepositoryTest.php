<?php

namespace Tests\Feature\Repositories;

use App\Models\Role;
use App\Models\WorkflowStep;
use App\Models\User;
use App\Models\Customer;
use App\Models\Company;
use App\Models\CustomerAddress;
use App\Models\CustomerContact;
use App\Models\Order;
use App\Models\OrderApproval;
use App\Repositories\WorkflowRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WorkflowRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected WorkflowRepository $repo;
    protected Role $role;
    protected User $user;
    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(WorkflowRepository::class);

        $this->role = Role::create(['name' => 'مدیر فروش']);

        $this->user = User::create([
            'full_name' => 'کاربر تست',
            'username'  => 'workflow_user',
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
    }

    #[Test]
    public function it_returns_workflow_steps_in_order()
    {
        WorkflowStep::create(['name' => 'مرحله ۳', 'step_order' => 3, 'role_id' => $this->role->id]);
        WorkflowStep::create(['name' => 'مرحله ۱', 'step_order' => 1, 'role_id' => $this->role->id]);
        WorkflowStep::create(['name' => 'مرحله ۲', 'step_order' => 2, 'role_id' => $this->role->id]);

        $steps = $this->repo->getWorkflowSteps();

        $this->assertCount(3, $steps);
        $this->assertEquals(1, $steps[0]->step_order);
        $this->assertEquals(2, $steps[1]->step_order);
        $this->assertEquals(3, $steps[2]->step_order);
    }

    #[Test]
    public function it_returns_order_approvals()
    {
        $step = WorkflowStep::create(['name' => 'تایید مدیر', 'step_order' => 1, 'role_id' => $this->role->id]);

        OrderApproval::create([
            'order_id' => $this->order->id,
            'step_id'  => $step->id,
            'status'   => 'pending',
        ]);

        $approvals = $this->repo->getOrderApprovals($this->order->id);

        $this->assertCount(1, $approvals);
        $this->assertEquals('pending', $approvals->first()->status);
    }

    #[Test]
    public function it_updates_approval_status_to_approved()
    {
        $step = WorkflowStep::create(['name' => 'تایید مدیر', 'step_order' => 1, 'role_id' => $this->role->id]);

        $approval = OrderApproval::create([
            'order_id' => $this->order->id,
            'step_id'  => $step->id,
            'status'   => 'pending',
        ]);

        $updated = $this->repo->updateApprovalStatus(
            $approval->id,
            'approved',
            $this->user->id,
            'تایید شد'
        );

        $this->assertEquals('approved', $updated->status);
        $this->assertEquals($this->user->id, $updated->action_by);
        $this->assertEquals('تایید شد', $updated->description);
        $this->assertNotNull($updated->action_at);
    }

    #[Test]
    public function it_updates_approval_status_to_rejected()
    {
        $step = WorkflowStep::create(['name' => 'تایید مدیر', 'step_order' => 1, 'role_id' => $this->role->id]);

        $approval = OrderApproval::create([
            'order_id' => $this->order->id,
            'step_id'  => $step->id,
            'status'   => 'pending',
        ]);

        $updated = $this->repo->updateApprovalStatus(
            $approval->id,
            'rejected',
            $this->user->id,
            'رد شد'
        );

        $this->assertEquals('rejected', $updated->status);
        $this->assertDatabaseHas('order_approvals', [
            'id'     => $approval->id,
            'status' => 'rejected',
        ]);
    }

    #[Test]
    public function it_loads_step_relation_on_approvals()
    {
        $step = WorkflowStep::create(['name' => 'تایید مالی', 'step_order' => 1, 'role_id' => $this->role->id]);

        OrderApproval::create([
            'order_id' => $this->order->id,
            'step_id'  => $step->id,
            'status'   => 'pending',
        ]);

        $approvals = $this->repo->getOrderApprovals($this->order->id);

        $this->assertNotNull($approvals->first()->step);
        $this->assertEquals('تایید مالی', $approvals->first()->step->name);
    }
}
