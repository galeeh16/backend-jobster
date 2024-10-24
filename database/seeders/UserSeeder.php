<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Administrator
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('Secret12345'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'is_admin' => true,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Create User Galih
        $galih_id = DB::table('users')->insertGetId([
            'name' => 'Galih Anggoro Jati',
            'email' => 'galih@example.com',
            'password' => Hash::make('Secret12345'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'user_type' => 'applier',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Create User Profile Galih
        DB::table('user_profile')->insert([
            'user_id' => $galih_id,
            'job_title_id' => 2,
            'location' => 'Riau',
            'full_address' => 'Bukit Lingkar, RT 10 RW 03',
            'about_me' => 'about me',
            'phone' => 'phone phone',
            'experience_year' => 5,
            'availibility_for_work' => true,
            'created_at' => date('Y-m-d H:i:s'),            
        ]);

        ////////////////////// Create Company KOPNUS
        $company_id = DB::table('users')->insertGetId([
            'name' => 'Koperasi Nusantara',
            'email' => 'kopnus@example.com',
            'password' => Hash::make('Secret12345'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'user_type' => 'company',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Create Company Profile KOPNUS
        DB::table('company_profile')->insert([
            'company_id' => $company_id,
            'address' => 'Jalan Prof Dr Soepomo',
            'location' => 'Jakarta',
            'company_size' => 1100,
            'founded_in' => '2004-01-01',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        ////////////////////// Create Company Bank Mandiri
        $company_id = DB::table('users')->insertGetId([
            'name' => 'Bank Mandiri',
            'email' => 'mandiri@example.com',
            'password' => Hash::make('Secret12345'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'user_type' => 'company',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Create Company Profile KOPNUS
        DB::table('company_profile')->insert([
            'company_id' => $company_id,
            'address' => 'Jalan Prof Dr Soepomo',
            'location' => 'Jakarta',
            'company_size' => 14000,
            'founded_in' => '1996-01-01',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        ////////////////////// Create Company Sinarmas
        $company_id = DB::table('users')->insertGetId([
            'name' => 'Sinarmas',
            'email' => 'sinarmas@example.com',
            'password' => Hash::make('Secret12345'),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'user_type' => 'company',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Create Company Profile Sinarmas
        DB::table('company_profile')->insert([
            'company_id' => $company_id,
            'address' => 'Jalan Merdeka',
            'location' => 'Jakarta',
            'company_size' => 10000,
            'founded_in' => '1986-01-01',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
