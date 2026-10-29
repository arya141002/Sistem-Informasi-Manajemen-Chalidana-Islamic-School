<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addColumn('emergency_contact_1_name', fn (Blueprint $table) => $table->text('emergency_contact_1_name')->nullable());
        $this->addColumn('emergency_contact_1_phone', fn (Blueprint $table) => $table->text('emergency_contact_1_phone')->nullable());
        $this->addColumn('emergency_contact_1_doctor', fn (Blueprint $table) => $table->text('emergency_contact_1_doctor')->nullable());
        $this->addColumn('emergency_contact_1_hospital', fn (Blueprint $table) => $table->text('emergency_contact_1_hospital')->nullable());
        $this->addColumn('emergency_contact_2_name', fn (Blueprint $table) => $table->text('emergency_contact_2_name')->nullable());
        $this->addColumn('emergency_contact_2_phone', fn (Blueprint $table) => $table->text('emergency_contact_2_phone')->nullable());
        $this->addColumn('emergency_contact_2_doctor', fn (Blueprint $table) => $table->text('emergency_contact_2_doctor')->nullable());
        $this->addColumn('emergency_contact_2_hospital', fn (Blueprint $table) => $table->text('emergency_contact_2_hospital')->nullable());
        $this->addColumn('basic_immunizations', fn (Blueprint $table) => $table->json('basic_immunizations')->nullable());
        $this->addColumn('allergy_food', fn (Blueprint $table) => $table->text('allergy_food')->nullable());
        $this->addColumn('allergy_medicine', fn (Blueprint $table) => $table->text('allergy_medicine')->nullable());
        $this->addColumn('allergy_other', fn (Blueprint $table) => $table->text('allergy_other')->nullable());
        $this->addColumn('allergy_description', fn (Blueprint $table) => $table->text('allergy_description')->nullable());
        $this->addColumn('pregnancy_medicine_consumption', fn (Blueprint $table) => $table->text('pregnancy_medicine_consumption')->nullable());
        $this->addColumn('pregnancy_diseases', fn (Blueprint $table) => $table->text('pregnancy_diseases')->nullable());
        $this->addColumn('gestational_age', fn (Blueprint $table) => $table->text('gestational_age')->nullable());
        $this->addColumn('delivery_process', fn (Blueprint $table) => $table->text('delivery_process')->nullable());
        $this->addColumn('baby_birth_weight', fn (Blueprint $table) => $table->text('baby_birth_weight')->nullable());
        $this->addColumn('baby_birth_length', fn (Blueprint $table) => $table->text('baby_birth_length')->nullable());
        $this->addColumn('routine_medication', fn (Blueprint $table) => $table->text('routine_medication')->nullable());
        $this->addColumn('surgery_history', fn (Blueprint $table) => $table->text('surgery_history')->nullable());
        $this->addColumn('hospitalization_history', fn (Blueprint $table) => $table->text('hospitalization_history')->nullable());
        $this->addColumn('previous_diseases', fn (Blueprint $table) => $table->text('previous_diseases')->nullable());
        $this->addColumn('family_medical_history', fn (Blueprint $table) => $table->text('family_medical_history')->nullable());
        $this->addColumn('health_statement_approved', fn (Blueprint $table) => $table->boolean('health_statement_approved')->default(false));
        $this->addColumn('health_approved_city', fn (Blueprint $table) => $table->text('health_approved_city')->nullable());
        $this->addColumn('health_approved_date', fn (Blueprint $table) => $table->date('health_approved_date')->nullable());
        $this->addColumn('health_approved_by', fn (Blueprint $table) => $table->text('health_approved_by')->nullable());
    }

    public function down(): void
    {
        Schema::table('admission_applications', function (Blueprint $table) {
            $table->dropColumn([
                'emergency_contact_1_name',
                'emergency_contact_1_phone',
                'emergency_contact_1_doctor',
                'emergency_contact_1_hospital',
                'emergency_contact_2_name',
                'emergency_contact_2_phone',
                'emergency_contact_2_doctor',
                'emergency_contact_2_hospital',
                'basic_immunizations',
                'allergy_food',
                'allergy_medicine',
                'allergy_other',
                'allergy_description',
                'pregnancy_medicine_consumption',
                'pregnancy_diseases',
                'gestational_age',
                'delivery_process',
                'baby_birth_weight',
                'baby_birth_length',
                'routine_medication',
                'surgery_history',
                'hospitalization_history',
                'previous_diseases',
                'family_medical_history',
                'health_statement_approved',
                'health_approved_city',
                'health_approved_date',
                'health_approved_by',
            ]);
        });
    }

    private function addColumn(string $column, callable $callback): void
    {
        if (Schema::hasColumn('admission_applications', $column)) {
            return;
        }

        Schema::table('admission_applications', $callback);
    }
};
