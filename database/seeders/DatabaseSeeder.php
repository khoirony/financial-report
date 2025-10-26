<?php

namespace Database\Seeders;

use App\Models\Cashflow;
use App\Models\CashflowCategory;
use App\Models\CashflowType;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===== ROLE =====
        $adminRole = UserRole::create([
            'name' => 'Admin',
            'description' => 'Administrator with full access',
        ]);
        $userRole = UserRole::create([
            'name' => 'User',
            'description' => 'Regular user with limited access',
        ]);

        // ===== USER =====
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password1234'),
            'role_id' => $adminRole->id,
        ]);

        $rony = User::create([
            'name' => 'Rony',
            'email' => 'khoironyarief08@gmail.com',
            'password' => bcrypt('password1234'),
            'role_id' => $userRole->id,
        ]);

        // ===== TYPE =====
        $incomeType = CashflowType::create(['name' => 'Income']);
        $spendingType = CashflowType::create(['name' => 'Spending']);

        // ===== CATEGORY =====
        $salary = CashflowCategory::create(['name' => 'Salary', 'cashflow_type_id' => $incomeType->id]);
        $food = CashflowCategory::create(['name' => 'Food', 'cashflow_type_id' => $spendingType->id]);
        $groceries = CashflowCategory::create(['name' => 'Groceries', 'cashflow_type_id' => $spendingType->id]);
        $items = CashflowCategory::create(['name' => 'Items', 'cashflow_type_id' => $spendingType->id]);
        $entertainment = CashflowCategory::create(['name' => 'Entertainment', 'cashflow_type_id' => $spendingType->id]);

        // ===== CASHFLOW DUMMY (3 BULAN) =====
        $categories = [$food, $groceries, $items, $entertainment];
        $startDate = now()->subMonths(3)->startOfMonth(); // 3 bulan lalu
        $endDate = now()->endOfMonth();

        $cashflows = [];

        // Gaji bulanan di awal bulan
        for ($month = 0; $month < 3; $month++) {
            $date = $startDate->copy()->addMonths($month)->startOfMonth()->addDays(1);
            $cashflows[] = [
                'user_id' => $rony->id,
                'cashflow_category_id' => $salary->id,
                'transaction_date' => $date,
                'description' => 'Monthly Salary',
                'source_account' => 'Farcapital',
                'destination_account' => 'Bank BCA',
                'amount' => 35000000,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Pengeluaran acak setiap hari
        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            // Random 1-2 transaksi per hari
            for ($i = 0; $i < rand(1, 2); $i++) {
                $category = $categories[array_rand($categories)];
                $desc = match ($category->name) {
                    'Food' => 'Makan siang / GoFood',
                    'Groceries' => 'Belanja harian',
                    'Items' => 'Pembelian barang',
                    'Entertainment' => 'Nonton / Spotify / Netflix',
                    default => 'Pengeluaran harian',
                };

                $cashflows[] = [
                    'user_id' => $rony->id,
                    'cashflow_category_id' => $category->id,
                    'transaction_date' => $current->copy()->setTime(rand(8, 20), rand(0, 59)),
                    'description' => $desc,
                    'source_account' => 'Bank Jago',
                    'destination_account' => 'Merchant ' . Str::random(5),
                    'amount' => rand(20000, 300000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $current->addDay();
        }

        // Insert bulk untuk efisiensi
        Cashflow::insert($cashflows);
    }
}
