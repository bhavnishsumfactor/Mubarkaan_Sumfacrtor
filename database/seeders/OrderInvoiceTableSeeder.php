<?php

namespace Database\Seeders;

use App\Models\OrderInvoice;
use Illuminate\Database\Seeder;

class OrderInvoiceTableSeeder extends Seeder
{

    public function run()
    {
        OrderInvoice::truncate();
        $sql =  base_path('public/dummydata/yokart_order_invoices.sql');
        \DB::unprepared(file_get_contents($sql));
    }
}
