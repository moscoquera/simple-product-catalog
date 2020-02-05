<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_user = new User();
        $admin_user->password=Hash::make('admin');
        $admin_user->email='admin@admin.com';
        $admin_user->name='admin';
        $admin_user->save();
    }
}
