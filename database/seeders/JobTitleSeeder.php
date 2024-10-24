<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        DB::table('job_title')->insert([
            ['title_name' => 'Custom Title', 'created_at' => $now],
            ['title_name' => 'FullStack Programmer', 'created_at' => $now],
            ['title_name' => 'Front End Programmer', 'created_at' => $now],
            ['title_name' => 'Backend Programmer', 'created_at' => $now],
            ['title_name' => 'Odoo Programmer', 'created_at' => $now],
            ['title_name' => 'Head Core Development', 'created_at' => $now],
            ['title_name' => 'Product Manager', 'created_at' => $now],
            ['title_name' => 'Sales', 'created_at' => $now],
            ['title_name' => 'Business Analyst', 'created_at' => $now],
            ['title_name' => 'Quality Assurance (QA)', 'created_at' => $now],
            ['title_name' => 'Database Engineer', 'created_at' => $now],
            ['title_name' => 'Database Administrator', 'created_at' => $now],
            ['title_name' => 'DevOps', 'created_at' => $now],
            ['title_name' => 'IT Infrastructure', 'created_at' => $now],
            ['title_name' => 'IT Operational', 'created_at' => $now],
            ['title_name' => 'Product Development', 'created_at' => $now],
            ['title_name' => 'Sales', 'created_at' => $now],
            ['title_name' => 'Approval Kredit', 'created_at' => $now],
            ['title_name' => 'Approval Asuransi', 'created_at' => $now],
            ['title_name' => 'Call Center', 'created_at' => $now],
            ['title_name' => 'Telesales', 'created_at' => $now],
            ['title_name' => 'Business Konven', 'created_at' => $now],
            ['title_name' => 'Business Digital', 'created_at' => $now],
            ['title_name' => 'Legal', 'created_at' => $now],
            ['title_name' => 'General Affair', 'created_at' => $now],
        ]);
    }
}
