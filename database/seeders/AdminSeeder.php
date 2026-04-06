<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'username' => 'hr@thesay.ae',
            'email' => 'hr@thesay.ae',
            'password' => Hash::make('HR@th3$4y2026'),
            'type' => 'admin',
            'status' => 'active', 
        ]);

        $employee = Employee::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'user_id' => $user->id,
            'employee_id' => '1000',
            'company_email' => 'hr@thesay.ae',
            'personal_email' => 'hr@thesay.ae',
            'total_leaves_allocated' => 0
        ]);


        $user->assignRole('Admin');
    }
}