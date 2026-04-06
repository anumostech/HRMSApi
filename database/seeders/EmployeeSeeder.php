<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'username' => 'jithin@thesay.ae',
            'email' => 'jithin@thesay.ae',
            'password' => Hash::make('jithin@thesay'),
            'type' => 'employee',
            'status' => 'active',
        ]);

        Employee::create([
            'user_id' => $user->id,
            'first_name' => 'Jithin',
            'last_name' => 'J',
            'employee_id' => '1',
            'company_email' => 'jithin@thesay.ae',
            'personal_email' => 'jithin@thesay.ae',
            'personal_number' => '9876543210',
            'joining_date' => now(),
            'total_leaves_allocated' => 24,
        ]);

        $user->assignRole('Employee');
    }
}