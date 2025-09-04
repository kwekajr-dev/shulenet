<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeacherRole;

class TeacherRolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'Normal Teacher',
                'description' => 'Standard teaching responsibilities',
                'permissions' => ['teaching', 'grading', 'attendance']
            ],
            [
                'name' => 'Class Teacher',
                'description' => 'Responsible for a specific class, including attendance and student management',
                'permissions' => ['teaching', 'grading', 'attendance', 'class_management', 'student_records']
            ],
            [
                'name' => 'Accountant Teacher',
                'description' => 'Handles financial matters including fee collection and payment approvals',
                'permissions' => ['payment_approval', 'fee_management', 'financial_reports']
            ],
            [
                'name' => 'Subject Coordinator',
                'description' => 'Leads a specific subject department and coordinates curriculum',
                'permissions' => ['teaching', 'grading', 'curriculum_development', 'subject_management']
            ]
        ];

        foreach ($roles as $role) {
            TeacherRole::create($role);
        }
    }
}