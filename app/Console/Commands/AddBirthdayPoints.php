<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Configuration;
use App\Models\UserRewardPoint;
use App\Models\UserRewardPointBreakup;
use App\Models\User;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use Carbon\Carbon;
use DB;

class AddBirthdayPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthdayrewardpoints:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add birthday reward points to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // echo 'cron started<br>';
        // echo 'DateTime: ' . Carbon::now()->format('Y-m-d') . '<br>';
        
        $users = User::select('user_id', 'user_email', DB::raw('CONCAT(user_fname, " ", user_lname) as user_name'))
        ->where(function ($sql) {
            $sql->whereNotNull('user_dob')
            ->where(DB::raw("DATE_FORMAT(`user_dob`, '%d-%m')"), Carbon::now()->format('d-m'));
        })
        ->where(function ($sql) {
            $sql->whereNull('user_birthday_points_expiry')
            ->orWhere('user_birthday_points_expiry', '<', Carbon::now()->format('Y-m-d H:i'));
        })
        ->where('user_publish', config('constants.YES'))
        ->get();
        // dd($users);
        $config = Configuration::getKeyValues([
            'REWARD_POINTS_ENABLE',
            'REWARD_POINTS_BIRTHDAY_POINTS_ENABLE',
            'REWARD_POINTS_BIRTHDAY_POINTS_VALIDITY',
            'REWARD_POINTS_BIRTHDAY_POINTS'
        ]);
        if (!empty($users) && count($users) > 0 && $config['REWARD_POINTS_ENABLE'] == 1 && $config['REWARD_POINTS_BIRTHDAY_POINTS_ENABLE'] == 1) {
            foreach ($users as $user) {
                $insertID = UserRewardPoint::create([
                    'urp_user_id' => $user->user_id,
                    'urp_referral_user_id' => 0,
                    'urp_comments' => 'Earned birthday points',
                    'urp_type' => UserRewardPoint::BIRTHDAY_POINT,
                    'urp_points' => $config['REWARD_POINTS_BIRTHDAY_POINTS'],
                    'urp_order_id' => 0,
                    'urp_date_added' => Carbon::now(),
                    'urp_date_expiry' => Carbon::now()->addDays($config['REWARD_POINTS_BIRTHDAY_POINTS_VALIDITY']),
                ])->urp_id;
                UserRewardPointBreakup::create([
                    'urpbreakup_urp_id' => $insertID,
                    'urpbreakup_referral_user_id' => 0,
                    'urpbreakup_used_order_id' => 0,
                    'urpbreakup_points' => $config['REWARD_POINTS_BIRTHDAY_POINTS'],
                    'urpbreakup_used' => 0,
                    'urp_date_added' => Carbon::now(),
                    'urpbreakup_expiry' => Carbon::now()->addDays($config['REWARD_POINTS_BIRTHDAY_POINTS_VALIDITY']),
                ]);
                User::where('user_id', $user->user_id)->update(['user_birthday_points_expiry' => Carbon::now()->addYear()]);

                /*send email code*/
                if (!empty($user->user_email)) {
                    $data = EmailHelper::getEmailData('rewards_earned_on_birthday');
                    $priority = $data['body']->etpl_priority;
                    $data['subject'] = $data['body']->etpl_subject;
                    $data['body'] = str_replace('{name}', $user->user_name, $data['body']->etpl_body);
                    $data['body'] = str_replace('{rewardPoints}', $config['REWARD_POINTS_BIRTHDAY_POINTS'], $data['body']);
                    $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
                    $data['to'] = $user->user_email;
                    sendPushNotification($user->user_id, 'rewards_earned_on_birthday', ['reward_points' =>  $config['REWARD_POINTS_BIRTHDAY_POINTS_VALIDITY']]);
                    dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
                }
            }
        }
        // echo 'cron finished<br>';
    }
}
