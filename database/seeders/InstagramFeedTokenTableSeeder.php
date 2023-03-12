<?php

namespace Database\Seeders;
use App\Models\InstagramFeedToken;
use Illuminate\Database\Seeder;

class InstagramFeedTokenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InstagramFeedToken::truncate();
        $recrod = [[
            'iftoken_id' => 1,
            'iftoken_username' => 'store_tribe',
            'iftoken_user_id' => '17841446235686780',
            'iftoken_access_code' => 'IGQVJVdk94eHoxcDlnNmlnWXg3aE9xZAmZABSjNNMzZA4alJfM1JCMmFLTmJRQWYwYVVLWmFlYzJKXzN4X1dUbnpRaElhNmZAyWWI1Njg2VDdZAUmhoSmEzSWdJbU02eFk1Q3NNYktYbHR3',
            'iftoken_expired_at' => '2021-05-04',
            'iftoken_created_at' => '2021-03-05 12:26:57'
         ]];

         InstagramFeedToken::insert($recrod);
    }
}
