<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Order;
use DB;

class OrderExport implements FromCollection, WithMapping, WithHeadings
{
    private $data;
    private $heading;

    public function __construct($data, array $heading)
    {
        $this->data = $data;
        $this->heading = $heading;
    }

    public function collection()
    {
        return $this->data;
    }
 

    public function map($records) : array {
        if (isset($records->orrequest_id)) {
            $amount = $records->op_product_price * $records->orrequest_qty +  $records->oramount_tax +  $records->oramount_shipping - ($records->oramount_reward_price +  $records->oramount_discount);
            if ($records->oramount_giftwrap_price != 0) {
                if ($records->oramount_giftwrap_price > 0) {
                    $amount = $amount + abs($records->oramount_giftwrap_price);
                } else {
                    $amount = $amount - $records->oramount_giftwrap_price;
                }
            }
            return [
                $records->user_fname,
                $records->user_lname,
                $records->user_phone,
                '#' . $records->order_id,
                $records->order_net_amount,
                '#' . $records->orrequest_id,
                $records->op_product_name,
                $records->orrequest_qty,
                $amount,
                getConvertedAdminDateTime($records->orrequest_date),
                $records->orrequest_reason,
                $records->orrequest_comment,
            ];
        } else {
            return [
                $records->user_fname,
                $records->user_lname,
                $records->user_phone,
                '#' . $records->order_id,
                $records->order_net_amount,
                $records->order_payment_status,
                ($records->order_notes) ?? '',
                (isset($records->address[0])) ? $records->address[0]->addresses : '',
                (isset($records->address[1])) ? $records->address[1]->addresses : '',
                getConvertedAdminDateTime($records->order_date_added),
            ];
        }
    }
    public function headings(): array
    {
        return $this->heading;
        
    }
}
