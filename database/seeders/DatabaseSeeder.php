<?php

namespace Database\Seeders;

use App\Models\Cashflow;
use App\Models\Category;
use App\Models\Role;
use App\Models\Type;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Role::create([
            'name' => 'Admin',
            'description' => 'Administrator with full access',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Role::create([
            'name' => 'User',
            'description' => 'Regular user with limited access',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password1234'),
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::create([
            'name' => 'Rony',
            'email' => 'khoironyarief08@gmail.com',
            'password' => bcrypt('password1234'),
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Type::create([
            'name' => 'Income',
        ]);
        Type::create([
            'name' => 'Spending',
        ]);
        Type::create([
            'name' => 'Investment',
        ]);

        Category::create([
            'name' => 'Salary',
            'type_id' => 1,
        ]);
        Category::create([
            'name' => 'Investment',
            'type_id' => 3,
        ]);
        Category::create([
            'name' => 'Food',
            'type_id' => 2,
        ]);
        Category::create([
            'name' => 'Groceries',
            'type_id' => 2,
        ]);
        Category::create([
            'name' => 'Items',
            'type_id' => 2,
        ]);
        Category::create([
            'name' => 'Entertainment',
            'type_id' => 2,
        ]);

        Cashflow::create([
            'user_id' => 2,
            'category_id' => 1,
            'transaction_date' => '2025-04-25 08:00:00',
            'description' => 'Pendapatan',
            'source_account' => 'Farcapital',
            'destination_account' => 'Bank BCA',
            'amount' => 35000000,
        ]);
        Cashflow::create([
            'user_id' => 2,
            'category_id' => 3,
            'transaction_date' => '2025-04-25 13:00:00',
            'description' => 'Gofood',
            'source_account' => 'Bank Jago',
            'destination_account' => 'Gojek',
            'amount' => 27800,
        ]);
        Cashflow::create([
            'user_id' => 2,
            'category_id' => 3,
            'transaction_date' => '2025-04-26 15:00:00',
            'description' => 'Belanja Bulanan',
            'source_account' => 'Bank Jago',
            'destination_account' => 'Borma',
            'amount' => 27800,
        ]);
    }
}
