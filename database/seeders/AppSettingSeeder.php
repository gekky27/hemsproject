<?php

namespace Database\Seeders;

use App\Models\AppSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppSettings::create([
            'name' => 'HEMS',
            'url' => 'http://127.0.0.1:8000',
            'slogan' => 'Bringing events to life',
            'deskripsi' => 'HEMS is a platform that allows you to book events easily and quickly.',
            'email' => 'booking@mail.com',
            'whatsapp' => '081234567890',
            'instagram' => 'eventbooking',
        ]);
    }
}
