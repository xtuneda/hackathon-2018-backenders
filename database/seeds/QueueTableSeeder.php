<?php

use App\Queue;
use Illuminate\Database\Seeder;

class QueueTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Queue::truncate();
        collect([
            [
                'host_user_id' => 1,
                'guest_user_id' => 2,
            ], [
                'host_user_id' => 1,
                'guest_user_id' => 3,
            ], [
                'host_user_id' => 1,
                'guest_user_id' => 4,
            ], [
                'host_user_id' => 1,
                'guest_user_id' => 5,
            ], [
                'host_user_id' => 1,
                'guest_user_id' => 6,
            ], [
                'host_user_id' => 4,
                'guest_user_id' => 1,
            ], [
                'host_user_id' => 4,
                'guest_user_id' => 2,
            ], [
                'host_user_id' => 4,
                'guest_user_id' => 5,
            ], [
                'host_user_id' => 3,
                'guest_user_id' => 2,
            ], [
                'host_user_id' => 3,
                'guest_user_id' => 4,
            ], [
                'host_user_id' => 3,
                'guest_user_id' => 5,
            ],
        ])->each(function($data) {
            Queue::create($data);
        });
    }
}
