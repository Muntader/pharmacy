<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Admin;
use App\Subuser;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // return User::create([
        //     'name' => 'Jane',
        //     'email' => 'john@jane.com',
        //     'username' => 'phar',
        //     'password' => bcrypt('password'),
        // ]);
        // return Admin::create([
        //     'name' => 'Jane',
        //     'email' => 'john@jane.com',
        //     'password' => bcrypt('password'),
        // ]);
        return Subuser::create([
            'name' => 'Jane',
            'email' => 'john@jane.com',
            'pharmacy_id' => '034d6780-d9b5-11e7-9df0-9b839e8c22e1',
            'password' => bcrypt('password'),
        ]);
    }
}
