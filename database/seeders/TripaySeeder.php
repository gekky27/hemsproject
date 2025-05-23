<?php

namespace Database\Seeders;

use App\Models\Tripay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TripaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tripay::create([
            'tripay_mode' => 'sandbox',
            'tripay_merchant' => 'T39523',
            'tripay_api' => 'DEV-WnphbJJTmniPnpntlhOf5BYNszcGSlaOc3o8USht',
            'tripay_private' => 'kkJoN-yJjYI-oBhFB-DechT-MLwEG',
        ]);
    }
}
