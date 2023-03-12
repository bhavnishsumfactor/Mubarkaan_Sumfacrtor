<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\User;
use App\Models\NewsletterSubscription;
use DB;

class UsersSubscriptionExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $unionQuery = NewsletterSubscription::select(DB::raw("'' as user_fname"), DB::raw("'' as user_lname"), 'subscription_email as email', DB::raw("'YES' as newsletter_subscribed"));
        return User::select('user_fname', 'user_lname', 'user_email as email', DB::raw("'NO' as newsletter_subscribed"))
            ->where('user_publish', config('constants.YES'))
            ->whereNotNull('user_email')
            ->union($unionQuery)
            ->distinct('email')
            ->get();
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Email',
            'Newsletter Subscribed'
        ];
    }
}
