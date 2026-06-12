<?php

namespace Database\Factories;

use App\Models\PermissionForm;
use App\Models\PermissionFormSignature;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PermissionFormSignature>
 */
class PermissionFormSignatureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'permission_form_id' => PermissionForm::factory(),
            'user_id' => User::factory(),
            'student_id' => Student::factory(),
            'signed_at' => now(),
        ];
    }
}
