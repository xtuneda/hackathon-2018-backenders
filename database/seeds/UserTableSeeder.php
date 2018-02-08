<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        collect([
            [
                'email' => 'tobias.olsson@exaktasoftware.se',
                'password' => Hash::make('123123'),
                'name' => 'Tobias',
            ], [
                'email' => 'albert.engvie@exaktasoftware.se',
                'password' => Hash::make('123123'),
                'name' => 'Albert',
            ], [
                'email' => 'daniel.tuner@exaktasoftware.se',
                'password' => Hash::make('123123'),
                'name' => 'Daniel',
            ], [
                'email' => 'simon.dubios@exaktasoftware.se',
                'password' => Hash::make('123123'),
                'name' => 'Simon',
            ], [
                'email' => 'david.andersson@exaktasoftware.se',
                'password' => Hash::make('123123'),
                'name' => 'David',
            ], [
                'email' => 'viktor.maar@exaktasoftware.se',
                'password' => Hash::make('123123'),
                'name' => 'Viktor',
            ],
        ])->each(function($userData) {
            User::create($userData);
        });
    }
}
