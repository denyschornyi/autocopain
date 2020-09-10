<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->truncate();
        DB::table('admins')->insert([
            'name' => 'AutoCopain Aide',
            'email' => 'info@autocopain.fr',
            'password' => bcrypt('123456'),
        ]);
    }
}
