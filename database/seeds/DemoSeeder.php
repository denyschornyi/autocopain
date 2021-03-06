<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cards')->truncate();
        DB::table('promocodes')->truncate();
        DB::table('promocode_usages')->truncate();
        DB::table('provider_devices')->truncate();
        DB::table('provider_documents')->truncate();
        DB::table('provider_profiles')->truncate();
        DB::table('provider_services')->truncate();
        DB::table('request_filters')->truncate();
        DB::table('user_request_payments')->truncate();
        DB::table('user_request_ratings')->truncate();
        DB::table('user_requests')->truncate();
        DB::table('users')->truncate();
        DB::table('users')->insert([[
            'first_name' => 'AutoCopain',
            'last_name' => 'Demo',
            'email' => 'info@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Emilia',
            'last_name' => 'Epps',
            'email' => 'emilia@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Perry',
            'last_name' => 'Kingsley',
            'email' => 'perry@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Joseph',
            'last_name' => 'Garrison',
            'email' => 'joseph@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Ella',
            'last_name' => 'Morrissey',
            'email' => 'morrissey@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Elizabeth',
            'last_name' => 'Forshee',
            'email' => 'elizabeth@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Douglas',
            'last_name' => 'Arce',
            'email' => 'douglas@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Sara',
            'last_name' => 'Nixon',
            'email' => 'sara@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Edward',
            'last_name' => 'Jett',
            'email' => 'edward@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Marilyn',
            'last_name' => 'Bradley',
            'email' => 'marilyn@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Herman',
            'last_name' => 'Thompson',
            'email' => 'herman@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Darrin',
            'last_name' => 'Neely',
            'email' => 'darrin@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Ralph',
            'last_name' => 'Vaughn',
            'email' => 'ralph@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Arturo',
            'last_name' => 'Gibson',
            'email' => 'arturo@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Kevin',
            'last_name' => 'Delacruz',
            'email' => 'kevin@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Catherine',
            'last_name' => 'Ferguson',
            'email' => 'catherine@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Gary',
            'last_name' => 'Maple',
            'email' => 'gary@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Robert',
            'last_name' => 'Ferguson',
            'email' => 'robert@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Charles',
            'last_name' => 'Ferguson',
            'email' => 'charles@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Michael',
            'last_name' => 'Ferguson',
            'email' => 'michael@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Patrick',
            'last_name' => 'Ferguson',
            'email' => 'patrick@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Arturo',
            'last_name' => 'Arturo',
            'email' => 'arturon@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Anthony',
            'last_name' => 'Ken',
            'email' => 'ken@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Anthony',
            'last_name' => 'Clinton',
            'email' => 'clinton@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Gabriel',
            'last_name' => 'Ferguson',
            'email' => 'gabriel@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Gabriel',
            'last_name' => 'Scott',
            'email' => 'scott@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Raymon',
            'last_name' => 'Ferguson',
            'email' => 'raymon@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Shon',
            'last_name' => 'Ferguson',
            'email' => 'shon@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Dennis',
            'last_name' => 'Ferguson',
            'email' => 'dennis@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Wayne',
            'last_name' => 'Ferguson',
            'email' => 'wayne@autocopain.com',
            'password' => bcrypt('123456'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'picture' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ]]);

        DB::table('providers')->truncate();
        DB::table('providers')->insert([[
            'first_name' => 'Appoets',
            'last_name' => 'Demo',
            'email' => 'demo@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Thomas',
            'last_name' => 'Jenkins',
            'email' => 'thomas@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Rachel',
            'last_name' => 'Burns',
            'email' => 'rachel@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Lorraine',
            'last_name' => 'Harris',
            'email' => 'lorraine@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Adam',
            'last_name' => 'Wagner',
            'email' => 'adam@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Christine',
            'last_name' => 'Forshee',
            'email' => 'christine@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Logan',
            'last_name' => 'Arce',
            'email' => 'logan@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Joe',
            'last_name' => 'Demo',
            'email' => 'joe@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Price',
            'last_name' => 'Jett',
            'email' => 'price@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Lloyd',
            'last_name' => 'Bradley',
            'email' => 'lloyd@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Evans',
            'last_name' => 'Thompson',
            'email' => 'evans@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Jerry',
            'last_name' => 'Neely',
            'email' => 'jerry@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Teresa',
            'last_name' => 'Vaughn',
            'email' => 'teresa@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Sarah',
            'last_name' => 'Gibson',
            'email' => 'sarah@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Regina',
            'last_name' => 'Delacruz',
            'email' => 'regina@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Anthony',
            'last_name' => 'Ferguson',
            'email' => 'anthony@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Gary',
            'last_name' => 'Maple',
            'email' => 'rasheed@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Robert',
            'last_name' => 'Ferguson',
            'email' => 'jack@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Charles',
            'last_name' => 'Ferguson',
            'email' => 'bobby@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Michael',
            'last_name' => 'Ferguson',
            'email' => 'chunky@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Patrick',
            'last_name' => 'Ferguson',
            'email' => 'silk@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Arturo',
            'last_name' => 'Arturo',
            'email' => 'gil@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Anthony',
            'last_name' => 'Ken',
            'email' => 'stego@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Anthony',
            'last_name' => 'Clinton',
            'email' => 'rodney@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Gabriel',
            'last_name' => 'Ferguson',
            'email' => 'spork@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Paul',
            'last_name' => 'Ferguson',
            'email' => 'paul@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Gabriel',
            'last_name' => 'Giuseppe',
            'email' => 'giuseppe@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Pog',
            'last_name' => 'Ferguson',
            'email' => 'pog@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Steve',
            'last_name' => 'Ferguson',
            'email' => 'steve@autocopain.com ',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ],[
            'first_name' => 'Pog',
            'last_name' => 'AJJS',
            'email' => 'beauregard@autocopain.com',
            'password' => bcrypt('123456'),
            'status' => 'approved',
            'latitude' => '13.00',
            'longitude' => '80.00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'avatar' => 'http://lorempixel.com/512/512/business/Xuber-Services',
        ]]);

        DB::table('provider_services')->truncate();
        DB::table('provider_services')->insert([[
            'provider_id' => 1,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 2,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 3,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 4,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 5,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 6,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 7,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 8,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 9,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 10,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 11,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 12,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 13,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 14,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 15,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 16,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 17,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 18,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 19,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 20,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 21,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 22,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 23,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 24,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 25,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 26,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 27,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 28,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 29,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],[
            'provider_id' => 30,
            'service_type_id' => 1,
            'status' => 'active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]]);
    }
}
