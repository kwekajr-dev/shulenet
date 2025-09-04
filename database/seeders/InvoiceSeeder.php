<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Student;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run()
    {
        // Get some students
        $students = Student::take(5)->get();
        
        if ($students->count() === 0) {
            $this->command->info('No students found. Please run Student seeder first.');
            return;
        }
        
        $invoiceData = [
            [
                'title' => 'Tuition Fee - Semester 1',
                'amount' => 500.00,
                'due_date' => Carbon::now()->addDays(30),
                'status' => 'pending',
            ],
            [
                'title' => 'Library Fee',
                'amount' => 50.00,
                'due_date' => Carbon::now()->addDays(15),
                'status' => 'pending',
            ],
            [
                'title' => 'Sports Fee',
                'amount' => 75.00,
                'due_date' => Carbon::now()->addDays(10),
                'status' => 'paid',
            ],
            [
                'title' => 'Exam Fee',
                'amount' => 100.00,
                'due_date' => Carbon::now()->subDays(5), // Overdue
                'status' => 'pending',
            ],
        ];
        
        foreach ($students as $student) {
            foreach ($invoiceData as $data) {
                Invoice::create(array_merge($data, [
                    'student_id' => $student->id,
                ]));
            }
        }
        
        $this->command->info('Created ' . (count($invoiceData) * $students->count()) . ' sample invoices.');
    }
}