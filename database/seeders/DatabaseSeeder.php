<?php

namespace Database\Seeders;

use App\Models\Cashflow;
use App\Models\Category;
use App\Models\Type;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Category::create([
            'name' => 'Salary',
        ]);
        Category::create([
            'name' => 'Investment',
        ]);

        Category::create([
            'name' => 'Food',
        ]);
        Category::create([
            'name' => 'Groceries',
        ]);
        Category::create([
            'name' => 'Items',
        ]);
        Category::create([
            'name' => 'Entertainment',
        ]);
        Category::create([
            'name' => 'Food',
        ]);

        Type::create([
            'name' => 'Income',
        ]);
        Type::create([
            'name' => 'Spending',
        ]);

        Cashflow::create([
            'user_id' => 1,
            'category_id' => 1,
            'type_id' => 1,
            'transaction_date' => '2025-04-25 08:00:00',
            'description' => 'Pendapatan',
            'source_account' => 'Farcapital',
            'destination_account' => 'Bank BCA',
            'amount' => 35000000,
        ]);
        Cashflow::create([
            'user_id' => 1,
            'category_id' => 3,
            'type_id' => 2,
            'transaction_date' => '2025-04-25 13:00:00',
            'description' => 'Gofood',
            'source_account' => 'Bank Jago',
            'destination_account' => 'Gojek',
            'amount' => 27800,
        ]);
        Cashflow::create([
            'user_id' => 1,
            'category_id' => 3,
            'type_id' => 2,
            'transaction_date' => '2025-04-26 15:00:00',
            'description' => 'Belanja Bulanan',
            'source_account' => 'Bank Jago',
            'destination_account' => 'Borma',
            'amount' => 27800,
        ]);
    }
}
