<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('registration_code', 40)->unique();
            $table->date('registration_date')->nullable();
            $table->string('status', 30)->default('draft');

            $table->string('full_name');
            $table->string('nickname')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('education_level')->nullable();
            $table->string('school_destination')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->string('head_circumference')->nullable();
            $table->string('religion')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('child_number')->nullable();
            $table->string('language_at_home')->nullable();
            $table->string('other_languages')->nullable();
            $table->string('living_with')->nullable();
            $table->string('distance_to_school')->nullable();
            $table->string('email')->nullable();

            $table->string('total_siblings')->nullable();
            $table->string('older_siblings')->nullable();
            $table->string('younger_siblings')->nullable();
            $table->string('total_step_siblings')->nullable();
            $table->string('older_step_siblings')->nullable();
            $table->string('younger_step_siblings')->nullable();
            $table->string('total_adopted_siblings')->nullable();
            $table->string('older_adopted_siblings')->nullable();
            $table->string('younger_adopted_siblings')->nullable();
            $table->boolean('ever_out_of_school')->default(false);
            $table->string('out_of_school_duration')->nullable();
            $table->boolean('had_special_therapy')->default(false);
            $table->string('therapy_type')->nullable();

            $table->string('father_name')->nullable();
            $table->string('father_birth_place')->nullable();
            $table->date('father_birth_date')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_income')->nullable();
            $table->string('father_notes')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_birth_place')->nullable();
            $table->date('mother_birth_date')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_income')->nullable();
            $table->string('mother_notes')->nullable();
            $table->text('parent_address')->nullable();

            $table->string('guardian_name')->nullable();
            $table->string('guardian_gender', 20)->nullable();
            $table->string('guardian_birth_place')->nullable();
            $table->date('guardian_birth_date')->nullable();
            $table->string('guardian_religion')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->text('guardian_address')->nullable();
            $table->string('guardian_income')->nullable();

            $table->string('student_photo_path')->nullable();
            $table->string('father_id_card_path')->nullable();
            $table->string('mother_id_card_path')->nullable();
            $table->string('family_card_path')->nullable();
            $table->string('birth_certificate_path')->nullable();
            $table->string('report_card_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_applications');
    }
};
