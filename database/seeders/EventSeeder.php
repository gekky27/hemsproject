<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('event_seats')->truncate();
        DB::table('events')->truncate();
        DB::table('venue_seats')->truncate();
        DB::table('organizers')->truncate();
        DB::table('users')->truncate();
        DB::table('venues')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Create users first
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'no_whatsapp' => '081234567801',
                'role' => 'admin',
                'password' => Hash::make('123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Musik Untuk Bangsa Manager',
                'email' => 'mub@example.com',
                'email_verified_at' => now(),
                'no_whatsapp' => '081234567802',
                'role' => 'organizer',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Java Festival Manager',
                'email' => 'java@example.com',
                'email_verified_at' => now(),
                'no_whatsapp' => '081234567803',
                'role' => 'organizer',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sound Rhythm Manager',
                'email' => 'sound@example.com',
                'email_verified_at' => now(),
                'no_whatsapp' => '081234567804',
                'role' => 'organizer',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Massive Music Manager',
                'email' => 'massive@example.com',
                'email_verified_at' => now(),
                'no_whatsapp' => '081234567805',
                'role' => 'organizer',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Soulnation Manager',
                'email' => 'soul@example.com',
                'email_verified_at' => now(),
                'no_whatsapp' => '081234567806',
                'role' => 'organizer',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Indonesia Live Manager',
                'email' => 'live@example.com',
                'email_verified_at' => now(),
                'no_whatsapp' => '081234567807',
                'role' => 'organizer',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Komunitas Musik Manager',
                'email' => 'komunitas@example.com',
                'email_verified_at' => now(),
                'no_whatsapp' => '081234567808',
                'role' => 'organizer',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rave Culture Manager',
                'email' => 'rave@example.com',
                'email_verified_at' => now(),
                'no_whatsapp' => '081234567809',
                'role' => 'organizer',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Synchronize Manager',
                'email' => 'sync@example.com',
                'email_verified_at' => now(),
                'no_whatsapp' => '081234567810',
                'role' => 'organizer',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bali Music Manager',
                'email' => 'bali@example.com',
                'email_verified_at' => now(),
                'no_whatsapp' => '081234567811',
                'role' => 'organizer',
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@gmail.com',
                'email_verified_at' => now(),
                'no_whatsapp' => '081234567812',
                'role' => 'user',
                'password' => Hash::make('123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);

        $venues = [
            [
                'name' => 'Gelora Bung Karno',
                'alamat' => 'Jl. Pintu Satu Senayan, Jakarta Pusat',
                'total_capacity' => 80000,
                'description' => 'Stadion utama yang biasa digunakan untuk konser besar di Jakarta',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Indonesia Convention Exhibition (ICE)',
                'alamat' => 'Jl. BSD Grand Boulevard, Tangerang',
                'total_capacity' => 50000,
                'description' => 'Venue terbesar di Tangerang yang sering digunakan untuk konser dan exhibition',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tennis Indoor Senayan',
                'alamat' => 'Jl. Pintu Senayan, Jakarta Selatan',
                'total_capacity' => 10000,
                'description' => 'Gedung serbaguna yang sering digunakan untuk konser musik',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Istora Senayan',
                'alamat' => 'Gelora Bung Karno, Jakarta Selatan',
                'total_capacity' => 7000,
                'description' => 'Venue indoor multifungsi yang terletak di kompleks GBK',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jakarta International Expo (JIExpo)',
                'alamat' => 'Jl. Benyamin Sueb, Kemayoran, Jakarta Pusat',
                'total_capacity' => 45000,
                'description' => 'Pusat pameran dan konvensi terbesar di Jakarta',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lapangan D Senayan',
                'alamat' => 'Kompleks GBK, Jakarta Selatan',
                'total_capacity' => 30000,
                'description' => 'Lapangan terbuka yang sering digunakan untuk konser outdoor',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Balai Sarbini',
                'alamat' => 'Jl. Jend. Sudirman, Jakarta Selatan',
                'total_capacity' => 2500,
                'description' => 'Gedung pertunjukan dengan akustik yang baik untuk musik',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'The Kasablanka Hall',
                'alamat' => 'Mall Kota Kasablanka, Jakarta Selatan',
                'total_capacity' => 5000,
                'description' => 'Venue modern di dalam mall yang sering mengadakan konser',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Stadion Manahan Solo',
                'alamat' => 'Jl. Adi Sucipto, Solo, Jawa Tengah',
                'total_capacity' => 25000,
                'description' => 'Stadion multifungsi di Solo yang juga digunakan untuk konser',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Garuda Wisnu Kencana (GWK)',
                'alamat' => 'Jl. Raya Uluwatu, Bali',
                'total_capacity' => 20000,
                'description' => 'Venue outdoor yang spektakuler di Bali dengan latar belakang patung GWK',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $venueIds = [];
        foreach ($venues as $venue) {
            $venueId = DB::table('venues')->insertGetId($venue);
            $venueIds[] = $venueId;
            $this->createVenueSeats($venueId, $venue['name']);
        }

        $organizers = [
            [
                'user_id' => 2, // Musik Untuk Bangsa Manager
                'name' => 'Musik Untuk Bangsa',
                'slug' => 'musik-untuk-bangsa',
                'organizer_type' => 'Corporate',
                'auditorium_type' => 'Indoor',
                'logo' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3, // Java Festival Manager
                'name' => 'Java Festival Production',
                'slug' => 'java-festival-production',
                'organizer_type' => 'Corporate',
                'auditorium_type' => 'Outdoor',
                'logo' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4, // Sound Rhythm Manager
                'name' => 'Sound Rhythm',
                'slug' => 'sound-rhythm',
                'organizer_type' => 'Independent',
                'auditorium_type' => 'Indoor',
                'logo' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5, // Massive Music Manager
                'name' => 'Massive Music Entertainment',
                'slug' => 'massive-music-entertainment',
                'organizer_type' => 'Corporate',
                'auditorium_type' => 'Indoor',
                'logo' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 6, // Soulnation Manager
                'name' => 'Soulnation Events',
                'slug' => 'soulnation-events',
                'organizer_type' => 'Corporate',
                'auditorium_type' => 'Outdoor',
                'logo' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 7, // Indonesia Live Manager
                'name' => 'Indonesia Live Entertainment',
                'slug' => 'indonesia-live-entertainment',
                'organizer_type' => 'Corporate',
                'auditorium_type' => 'Indoor',
                'logo' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 8, // Komunitas Musik Manager
                'name' => 'Komunitas Musik Tanah Air',
                'slug' => 'komunitas-musik-tanah-air',
                'organizer_type' => 'Independent',
                'auditorium_type' => 'Indoor',
                'logo' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 9, // Rave Culture Manager
                'name' => 'Rave Culture Indonesia',
                'slug' => 'rave-culture-indonesia',
                'organizer_type' => 'Independent',
                'auditorium_type' => 'Outdoor',
                'logo' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 10, // Synchronize Manager
                'name' => 'Synchronize Festival',
                'slug' => 'synchronize-festival',
                'organizer_type' => 'Corporate',
                'auditorium_type' => 'Outdoor',
                'logo' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 11, // Bali Music Manager
                'name' => 'Bali Music Festival',
                'slug' => 'bali-music-festival',
                'organizer_type' => 'Corporate',
                'auditorium_type' => 'Outdoor',
                'logo' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('organizers')->insert($organizers);

        $events = [
            [
                'organizers_id' => 1,
                'venues_id' => 1,
                'name' => 'Rock in Jakarta 2023',
                'slug' => 'rock-in-jakarta-2023',
                'description' => 'Festival musik rock terbesar di Indonesia tahun 2023',
                'event_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'event_time' => '19:00:00',
                'ticket_price' => 750000,
                'status' => 'ready',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizers_id' => 2,
                'venues_id' => 2,
                'name' => 'Jazz Night Festival',
                'slug' => 'jazz-night-festival',
                'description' => 'Malam jazz dengan musisi-musisi terbaik dalam dan luar negeri',
                'event_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'event_time' => '20:00:00',
                'ticket_price' => 950000,
                'status' => 'ready',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizers_id' => 3,
                'venues_id' => 3,
                'name' => 'Konser Akustik Nusantara',
                'slug' => 'konser-akustik-nusantara',
                'description' => 'Konser akustik yang menampilkan talenta-talenta Indonesia',
                'event_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'event_time' => '19:30:00',
                'ticket_price' => 500000,
                'status' => 'ready',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizers_id' => 4,
                'venues_id' => 4,
                'name' => 'K-Pop Super Concert',
                'slug' => 'k-pop-super-concert',
                'description' => 'Konser K-Pop dengan bintang tamu dari Korea Selatan',
                'event_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'event_time' => '18:30:00',
                'ticket_price' => 1250000,
                'status' => 'ready',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizers_id' => 5,
                'venues_id' => 5,
                'name' => 'Electronic Dance Festival',
                'slug' => 'electronic-dance-festival',
                'description' => 'Festival musik elektronik dengan DJ internasional',
                'event_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'event_time' => '22:00:00',
                'ticket_price' => 850000,
                'status' => 'ready',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizers_id' => 6,
                'venues_id' => 6,
                'name' => 'Konser Dangdut Spektakuler',
                'slug' => 'konser-dangdut-spektakuler',
                'description' => 'Konser dangdut dengan penampil-penampil terbaik tanah air',
                'event_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'event_time' => '19:00:00',
                'ticket_price' => 350000,
                'status' => 'ready',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizers_id' => 7,
                'venues_id' => 7,
                'name' => 'Indie Music Showcase',
                'slug' => 'indie-music-showcase',
                'description' => 'Panggung musik untuk band-band indie Indonesia',
                'event_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'event_time' => '19:00:00',
                'ticket_price' => 200000,
                'status' => 'ready',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizers_id' => 8,
                'venues_id' => 8,
                'name' => 'Trance Nation Indonesia',
                'slug' => 'trance-nation-indonesia',
                'description' => 'Festival trance music terbesar di Indonesia',
                'event_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'event_time' => '21:00:00',
                'ticket_price' => 650000,
                'status' => 'ready',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizers_id' => 9,
                'venues_id' => 9,
                'name' => 'Folk & Traditional Music Festival',
                'slug' => 'folk-traditional-music-festival',
                'description' => 'Festival musik tradisional dari berbagai daerah di Indonesia',
                'event_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'event_time' => '16:00:00',
                'ticket_price' => 300000,
                'status' => 'ready',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizers_id' => 10,
                'venues_id' => 10,
                'name' => 'Sunset Music Festival Bali',
                'slug' => 'sunset-music-festival-bali',
                'description' => 'Festival musik dengan latar belakang sunset di Bali',
                'event_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'event_time' => '17:00:00',
                'ticket_price' => 1100000,
                'status' => 'ready',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($events as $key => $event) {
            $eventId = DB::table('events')->insertGetId($event);
            $venueId = $event['venues_id'];

            $this->createEventSeats($eventId, $venueId);
            $availableSeats = DB::table('event_seats')
                ->where('event_id', $eventId)
                ->where('status', 'available')
                ->count();

            DB::table('events')
                ->where('id', $eventId)
                ->update(['available_seats' => $availableSeats]);

            if ($availableSeats == 0) {
                DB::table('events')
                    ->where('id', $eventId)
                    ->update(['status' => 'soldout']);
            }
        }
    }

    /**
     * Create seats for a venue
     */
    private function createVenueSeats($venueId, $venueName): void
    {
        $rowsConfig = [
            'Gelora Bung Karno' => ['rows' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'], 'seatsPerRow' => 30],
            'Indonesia Convention Exhibition (ICE)' => ['rows' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'], 'seatsPerRow' => 25],
            'Tennis Indoor Senayan' => ['rows' => ['A', 'B', 'C', 'D', 'E'], 'seatsPerRow' => 20],
            'Istora Senayan' => ['rows' => ['A', 'B', 'C', 'D'], 'seatsPerRow' => 15],
            'Jakarta International Expo (JIExpo)' => ['rows' => ['A', 'B', 'C', 'D', 'E', 'F', 'G'], 'seatsPerRow' => 25],
            'Lapangan D Senayan' => ['rows' => ['A', 'B', 'C', 'D', 'E', 'F'], 'seatsPerRow' => 20],
            'Balai Sarbini' => ['rows' => ['A', 'B', 'C'], 'seatsPerRow' => 15],
            'The Kasablanka Hall' => ['rows' => ['A', 'B', 'C', 'D'], 'seatsPerRow' => 18],
            'Stadion Manahan Solo' => ['rows' => ['A', 'B', 'C', 'D', 'E'], 'seatsPerRow' => 20],
            'Garuda Wisnu Kencana (GWK)' => ['rows' => ['A', 'B', 'C', 'D', 'E'], 'seatsPerRow' => 20],
        ];

        $config = $rowsConfig[$venueName] ?? ['rows' => ['A', 'B', 'C', 'D'], 'seatsPerRow' => 10];
        $rows = $config['rows'];
        $seatsPerRow = $config['seatsPerRow'];
        $venueSeats = [];

        foreach ($rows as $rowIndex => $rowName) {
            for ($seatNum = 1; $seatNum <= $seatsPerRow; $seatNum++) {
                $xCoordinate = $seatNum * 30;
                $yCoordinate = $rowIndex * 40;

                $venueSeats[] = [
                    'venues_id' => $venueId,
                    'row_name' => $rowName,
                    'seat_number' => $seatNum,
                    'x_coordinate' => $xCoordinate,
                    'y_coordinate' => $yCoordinate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('venue_seats')->insert($venueSeats);
    }

    /**
     * Create event seats for an event
     */
    private function createEventSeats($eventId, $venueId): void
    {
        $venueSeats = DB::table('venue_seats')
            ->where('venues_id', $venueId)
            ->get();

        $eventSeats = [];
        $availableCount = 0;

        foreach ($venueSeats as $venueSeat) {
            $status = 'available';

            if ($venueSeat->row_name === 'A' && $venueSeat->seat_number % 2 === 0) {
                $status = 'unavailable';
            }

            if ($venueSeat->row_name === 'J' && $venueSeat->seat_number % 3 === 0) {
                $status = 'booked';
            }

            if ($venueSeat->row_name === 'E' && $venueSeat->seat_number % 5 === 0) {
                $status = 'sold';
            }

            $eventSeats[] = [
                'event_id' => $eventId,
                'venue_seat_id' => $venueSeat->id,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($status === 'available') {
                $availableCount++;
            }
        }

        DB::table('event_seats')->insert($eventSeats);
    }
}
