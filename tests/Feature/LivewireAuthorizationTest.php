<?php

namespace Tests\Feature;

use App\Livewire\Admin\Trips\Create as AdminCreate;
use App\Livewire\Admin\Trips\Edit as AdminEdit;
use App\Livewire\Admin\Trips\Index as AdminIndex;
use App\Livewire\Teacher\Trips\Edit as TeacherEdit;
use App\Livewire\Teacher\Trips\Show as TeacherShow;
use App\Models\Classroom;
use App\Models\FieldTrip;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LivewireAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::factory()->admin()->create();
        Role::factory()->teacher()->create();
        Role::factory()->guardian()->create();
    }

    // ── Admin authorization ──────────────────────────────────────────

    #[Test]
    public function admin_index_component_blocks_non_admin_users(): void
    {
        $teacher = User::factory()->create(['role_id' => Role::where('name', 'teacher')->first()->id]);

        Livewire::actingAs($teacher)
            ->test(AdminIndex::class)
            ->assertForbidden();
    }

    #[Test]
    public function admin_index_component_allows_admin_users(): void
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'admin')->first()->id]);

        Livewire::actingAs($admin)
            ->test(AdminIndex::class)
            ->assertOk();
    }

    #[Test]
    public function admin_create_component_blocks_non_admin_users(): void
    {
        $teacher = User::factory()->create(['role_id' => Role::where('name', 'teacher')->first()->id]);

        Livewire::actingAs($teacher)
            ->test(AdminCreate::class)
            ->assertForbidden();
    }

    #[Test]
    public function admin_edit_component_blocks_non_admin_users(): void
    {
        $teacher = User::factory()->create(['role_id' => Role::where('name', 'teacher')->first()->id]);
        $trip = FieldTrip::factory()->create();

        Livewire::actingAs($teacher)
            ->test(AdminEdit::class, ['trip' => $trip])
            ->assertForbidden();
    }

    // ── Teacher authorization ────────────────────────────────────────

    #[Test]
    public function teacher_show_component_blocks_unauthorized_teacher(): void
    {
        $teacherA = User::factory()->create(['role_id' => Role::where('name', 'teacher')->first()->id]);
        $teacherB = User::factory()->create(['role_id' => Role::where('name', 'teacher')->first()->id]);
        $classroom = Classroom::factory()->create(['user_id' => $teacherA->id]);
        $trip = FieldTrip::factory()->create(['classroom_id' => $classroom->id]);

        Livewire::actingAs($teacherB)
            ->test(TeacherShow::class, ['trip' => $trip])
            ->assertForbidden();
    }

    #[Test]
    public function teacher_show_component_allows_owning_teacher(): void
    {
        $teacher = User::factory()->create(['role_id' => Role::where('name', 'teacher')->first()->id]);
        $classroom = Classroom::factory()->create(['user_id' => $teacher->id]);
        $trip = FieldTrip::factory()->create(['classroom_id' => $classroom->id]);

        Livewire::actingAs($teacher)
            ->test(TeacherShow::class, ['trip' => $trip])
            ->assertOk();
    }

    #[Test]
    public function teacher_edit_component_blocks_unauthorized_teacher(): void
    {
        $teacherA = User::factory()->create(['role_id' => Role::where('name', 'teacher')->first()->id]);
        $teacherB = User::factory()->create(['role_id' => Role::where('name', 'teacher')->first()->id]);
        $classroom = Classroom::factory()->create(['user_id' => $teacherA->id]);
        $trip = FieldTrip::factory()->create(['classroom_id' => $classroom->id]);

        Livewire::actingAs($teacherB)
            ->test(TeacherEdit::class, ['trip' => $trip])
            ->assertForbidden();
    }

    // ── Trip status transitions ──────────────────────────────────────

    #[Test]
    public function teacher_can_mark_trip_as_completed(): void
    {
        $teacher = User::factory()->create(['role_id' => Role::where('name', 'teacher')->first()->id]);
        $classroom = Classroom::factory()->create(['user_id' => $teacher->id]);
        $trip = FieldTrip::factory()->create(['classroom_id' => $classroom->id, 'status' => 'open']);

        Livewire::actingAs($teacher)
            ->test(TeacherShow::class, ['trip' => $trip])
            ->call('complete')
            ->assertOk();

        $this->assertSame('completed', $trip->fresh()->status);
    }

    #[Test]
    public function teacher_can_cancel_a_trip(): void
    {
        $teacher = User::factory()->create(['role_id' => Role::where('name', 'teacher')->first()->id]);
        $classroom = Classroom::factory()->create(['user_id' => $teacher->id]);
        $trip = FieldTrip::factory()->create(['classroom_id' => $classroom->id, 'status' => 'open']);

        Livewire::actingAs($teacher)
            ->test(TeacherShow::class, ['trip' => $trip])
            ->call('cancel')
            ->assertOk();

        $this->assertSame('cancelled', $trip->fresh()->status);
    }

    // ── Payment marking ──────────────────────────────────────────────

    #[Test]
    public function teacher_can_mark_student_as_paid(): void
    {
        $teacher = User::factory()->create(['role_id' => Role::where('name', 'teacher')->first()->id]);
        $classroom = Classroom::factory()->create(['user_id' => $teacher->id]);
        $trip = FieldTrip::factory()->withCost(25.00)->create(['classroom_id' => $classroom->id]);
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);

        Livewire::actingAs($teacher)
            ->test(TeacherShow::class, ['trip' => $trip])
            ->call('markPaid', $student->id)
            ->assertOk();

        $payment = Payment::where('field_trip_id', $trip->id)
            ->where('student_id', $student->id)
            ->first();

        $this->assertNotNull($payment);
        $this->assertSame('paid', $payment->status);
        $this->assertSame(25.00, (float) $payment->amount);
    }

    #[Test]
    public function teacher_can_mark_student_as_unpaid(): void
    {
        $teacher = User::factory()->create(['role_id' => Role::where('name', 'teacher')->first()->id]);
        $classroom = Classroom::factory()->create(['user_id' => $teacher->id]);
        $trip = FieldTrip::factory()->create(['classroom_id' => $classroom->id]);
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);
        Payment::factory()->paid()->create([
            'field_trip_id' => $trip->id,
            'student_id' => $student->id,
        ]);

        Livewire::actingAs($teacher)
            ->test(TeacherShow::class, ['trip' => $trip])
            ->call('markUnpaid', $student->id)
            ->assertOk();

        $payment = Payment::where('field_trip_id', $trip->id)
            ->where('student_id', $student->id)
            ->first();

        $this->assertSame('pending', $payment->status);
        $this->assertNull($payment->paid_at);
    }

    // ── Admin trip creation with nullable fields ─────────────────────

    #[Test]
    public function admin_can_create_trip_without_cost_and_payment_deadline(): void
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'admin')->first()->id]);
        $classroom = Classroom::factory()->create();

        Livewire::actingAs($admin)
            ->test(AdminCreate::class)
            ->set('title', 'Museum Visit')
            ->set('description', 'A trip to the science museum.')
            ->set('location', 'Science Museum')
            ->set('classroom_id', $classroom->id)
            ->set('begin_date', now()->addWeek()->format('Y-m-d'))
            ->set('end_date', now()->addWeek()->format('Y-m-d'))
            ->set('departure_time', now()->addWeek()->format('Y-m-d\TH:i'))
            ->set('return_time', now()->addWeek()->addHours(4)->format('Y-m-d\TH:i'))
            ->call('save')
            ->assertRedirect(route('admin.trips'));

        $trip = FieldTrip::latest()->first();
        $this->assertSame('Museum Visit', $trip->title);
        $this->assertNull($trip->cost);
        $this->assertNull($trip->payment_deadline);
    }

    // ── Admin refund flow ────────────────────────────────────────────

    #[Test]
    public function admin_refund_cancels_trip_and_marks_paid_payments_as_refunded(): void
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'admin')->first()->id]);
        $trip = FieldTrip::factory()->create(['status' => 'open']);
        $paidPayment = Payment::factory()->paid()->create(['field_trip_id' => $trip->id]);
        $pendingPayment = Payment::factory()->create(['field_trip_id' => $trip->id, 'status' => 'pending']);

        Livewire::actingAs($admin)
            ->test(AdminIndex::class)
            ->call('refund', $trip->id)
            ->assertOk();

        $this->assertSame('cancelled', $trip->fresh()->status);
        $this->assertSame('refunded', $paidPayment->fresh()->status);
        $this->assertSame('pending', $pendingPayment->fresh()->status); // unchanged
    }
}
