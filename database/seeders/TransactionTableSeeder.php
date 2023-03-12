<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transaction::truncate();
        $sql =  base_path('public/dummydata/yokart_transactions.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
