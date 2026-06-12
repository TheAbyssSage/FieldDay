<?php

namespace Tests\Unit;

use App\Models\Classroom;
use App\Models\FieldTrip;
use App\Models\Payment;
use App\Models\PermissionForm;
use App\Models\PermissionFormSignature;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ModelRelationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::factory()->admin()->create();
        Role::factory()->teacher()->create();
        Role::factory()->guardian()->create();
    }

    #[Test]
    public function user_hasRole_returns_true_for_matching_role(): void
    {
        $admin = User::factory()->create(['role_id' => Role::where('name', 'admin')->first()->id]);
        $teacher = User::factory()->create(['role_id' => Role::where('name', 'teacher')->first()->id]);

        $this->assertTrue($admin->hasRole('admin'));
        $this->assertFalse($admin->hasRole('teacher'));
        $this->assertTrue($teacher->hasRole('teacher'));
        $this->assertFalse($teacher->hasRole('admin'));
    }

    #[Test]
    public function user_hasRole_returns_false_when_user_has_no_role(): void
    {
        $user = User::factory()->create(['role_id' => null]);

        $this->assertFalse($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('teacher'));
    }

    #[Test]
    public function classroom_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $classroom = Classroom::factory()->create(['user_id' => $user->id]);

        $this->assertSame($user->id, $classroom->user->id);
    }

    #[Test]
    public function field_trip_belongs_to_classroom(): void
    {
        $classroom = Classroom::factory()->create();
        $trip = FieldTrip::factory()->create(['classroom_id' => $classroom->id]);

        $this->assertSame($classroom->id, $trip->classroom->id);
    }

    #[Test]
    public function field_trip_can_have_optional_permission_form(): void
    {
        $trip = FieldTrip::factory()->create(['permission_form_id' => null]);
        $this->assertNull($trip->permissionForm);

        $form = PermissionForm::factory()->create();
        $trip->update(['permission_form_id' => $form->id]);
        $this->assertSame($form->id, $trip->fresh()->permissionForm->id);
    }

    #[Test]
    public function field_trip_can_have_nullable_cost_and_payment_deadline(): void
    {
        $trip = FieldTrip::factory()->create(['cost' => null, 'payment_deadline' => null]);

        $this->assertNull($trip->cost);
        $this->assertNull($trip->payment_deadline);
    }

    #[Test]
    public function payment_belongs_to_field_trip_student_and_user(): void
    {
        $user = User::factory()->create();
        $student = Student::factory()->create();
        $trip = FieldTrip::factory()->create();

        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'student_id' => $student->id,
            'field_trip_id' => $trip->id,
        ]);

        $this->assertSame($user->id, $payment->user->id);
        $this->assertSame($student->id, $payment->student->id);
        $this->assertSame($trip->id, $payment->fieldTrip->id);
    }

    #[Test]
    public function permission_form_signature_belongs_to_form_user_and_student(): void
    {
        $form = PermissionForm::factory()->create();
        $user = User::factory()->create();
        $student = Student::factory()->create();

        $signature = PermissionFormSignature::factory()->create([
            'permission_form_id' => $form->id,
            'user_id' => $user->id,
            'student_id' => $student->id,
        ]);

        $this->assertSame($form->id, $signature->permissionForm->id);
        $this->assertSame($user->id, $signature->user->id);
        $this->assertSame($student->id, $signature->student->id);
    }
}
