<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionApplication extends Model
{
    protected $fillable = [
        'user_id',
        'registration_code',
        'registration_date',
        'status',
        'full_name',
        'nickname',
        'gender',
        'birth_place',
        'birth_date',
        'education_level',
        'campus_id',
        'school_destination',
        'academic_year',
        'weight',
        'height',
        'head_circumference',
        'religion',
        'address',
        'phone',
        'previous_school',
        'child_number',
        'language_at_home',
        'other_languages',
        'living_with',
        'distance_to_school',
        'email',
        'total_siblings',
        'older_siblings',
        'younger_siblings',
        'total_step_siblings',
        'older_step_siblings',
        'younger_step_siblings',
        'total_adopted_siblings',
        'older_adopted_siblings',
        'younger_adopted_siblings',
        'ever_out_of_school',
        'out_of_school_duration',
        'had_special_therapy',
        'therapy_type',
        'father_name',
        'father_birth_place',
        'father_birth_date',
        'father_occupation',
        'father_income',
        'father_notes',
        'mother_name',
        'mother_birth_place',
        'mother_birth_date',
        'mother_occupation',
        'mother_income',
        'mother_notes',
        'parent_address',
        'guardian_name',
        'guardian_gender',
        'guardian_birth_place',
        'guardian_birth_date',
        'guardian_religion',
        'guardian_occupation',
        'guardian_address',
        'guardian_income',
        'student_photo_path',
        'father_id_card_path',
        'mother_id_card_path',
        'family_card_path',
        'birth_certificate_path',
        'akta_path',
        'report_card_path',
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
    ];

    protected function casts(): array
    {
        return [
            'registration_date' => 'date',
            'birth_date' => 'date',
            'father_birth_date' => 'date',
            'mother_birth_date' => 'date',
            'guardian_birth_date' => 'date',
            'ever_out_of_school' => 'boolean',
            'had_special_therapy' => 'boolean',
            'basic_immunizations' => 'array',
            'health_statement_approved' => 'boolean',
            'health_approved_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
