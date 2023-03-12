<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserRewardPoint;
use App\Models\UserRewardPointBreakup;
use Carbon\Carbon;
use App\Helpers\EmailHelper;
use App\Jobs\SendNotification;
use DB;

class ExpireRewardPoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rewardpoints:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire reward points';

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
        $expiredRewardPoints = UserRewardPoint::select(DB::raw('SUM(urpbreakup_points) as expired_points'), 'urp_user_id', 'urpbreakup_id')
        ->join('user_reward_point_breakup as pointbackup', 'pointbackup.urpbreakup_urp_id', 'user_reward_points.urp_id')
        ->where('urpbreakup_expiry', '<', Carbon::now())->where('urp_used', 0)->where('pointbackup.urpbreakup_used', 0)
        ->groupBy('urp_user_id')->get();
        
        if (!empty($expiredRewardPoints) && count($expiredRewardPoints) > 0) {
            foreach ($expiredRewardPoints as $key => $reward) {
                $expiredRewardPointBreakupIds = UserRewardPoint::join('user_reward_point_breakup as pointbackup', 'pointbackup.urpbreakup_urp_id', 'user_reward_points.urp_id')
                ->where('urpbreakup_expiry', '<', Carbon::now())->where('urp_used', 0)->where('pointbackup.urpbreakup_used', 0)
                ->where('urp_user_id', $reward->urp_user_id)->get()->pluck('urpbreakup_id')->toArray();
                
                UserRewardPoint::create([
                    'urp_type' => UserRewardPoint::EXPIRED_POINT,
                    'urp_user_id' => $reward->urp_user_id,
                    'urp_referral_user_id' => 0,
                    'urp_comments' => 'Points expired',
                    'urp_points' => $reward->expired_points,
                    'urp_order_id' => 0,
                    'urp_date_added' => Carbon::now(),
                    'urp_date_expiry' => 'null',
                    'urp_used' => 0,
                ]);
                UserRewardPointBreakup::whereIn('urpbreakup_id', $expiredRewardPointBreakupIds)->delete();

                /*send email code*/
                $user = User::getUserById($reward->urp_user_id);
                $data = EmailHelper::getEmailData('rewards_expired');
                $priority = $data['body']->etpl_priority;
                $data['subject'] = str_replace('{rewardPoints}', $reward->expired_points, $data['body']->etpl_subject);
                $data['body'] = str_replace('{name}', $user->user_name, $data['body']->etpl_body);
                $data['body'] = str_replace('{rewardPoints}', $reward->expired_points, $data['body']);
                $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
                $data['to'] = $user->user_email;
                sendPushNotification($reward->urp_user_id, 'rewards_expired', ['reward_points' => $reward->expired_points]);
                dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);
            }
        }
    }
}
