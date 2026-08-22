<?php

namespace Database\Seeders;

use App\Models\Enquiry;
use Illuminate\Database\Seeder;

class EnquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Ava Johnson', 'Liam Smith', 'Sophia Brown', 'Noah Davis', 'Olivia Wilson',
            'Ethan Moore', 'Mia Taylor', 'Lucas Anderson', 'Emma Thomas', 'James Jackson',
            'Isabella White', 'Benjamin Harris', 'Charlotte Martin', 'Henry Thompson', 'Amelia Garcia',
            'Alexander Martinez', 'Harper Robinson', 'Daniel Clark', 'Ella Rodriguez', 'Michael Lewis',
            'Abigail Lee', 'Sebastian Walker', 'Emily Hall', 'Logan Allen', 'Scarlett Young',
            'Jack King', 'Grace Wright', 'Owen Scott', 'Chloe Green', 'Leo Baker',
            'Avery Adams', 'Nora Nelson', 'Leo Mitchell', 'Layla Perez', 'Hudson Roberts',
            'Victoria Turner', 'Wyatt Phillips', 'Zoe Campbell', 'Gabriel Parker', 'Lucy Evans',
        ];

        $subjects = [
            'Airport transfer inquiry', 'Luxury chauffeur request', 'Tour package information',
            'Fleet availability question', 'Booking quote request', 'Corporate travel enquiry',
            'Weekend tour availability', 'Wedding transport request', 'City tour details',
            'Group travel planning', 'Special event arrangement', 'Service pricing question',
            'Hotel pickup consultation', 'One-way transfer request', 'Long distance booking',
            'Airport meet and greet', 'Chauffeur driver request', 'Multi-day itinerary',
        ];

        $messages = [
            'Hi, I would like more information about your chauffeur service and availability for the upcoming weekend.',
            'Could you please send me a quote for a one-way airport transfer with a premium vehicle?',
            'I am planning a family trip and need details about group booking options and luggage capacity.',
            'Please advise the earliest available slot for a private city tour and the pricing for two adults.',
            'We are organizing a corporate event and would like to discuss long-term transport arrangements.',
            'I would like to know if you offer chauffeur service for special occasions and hotel pickups.',
            'Can you share more details about your fleet and whether a child seat can be arranged?',
            'We need a reliable transfer from the airport to our hotel for a large family group.',
            'Could you confirm availability for a full-day tour with a professional chauffeur?',
            'I am interested in arranging a return transfer and would like to know the total cost.',
        ];

        $statuses = ['new', 'in_progress', 'resolved', 'closed'];
        $sources = ['home', 'about', 'contact', 'service', 'tour', 'booking'];

        $records = [];

        for ($i = 0; $i < 40; $i++) {
            $createdAt = now()->subDays(rand(1, 75))->subHours(rand(1, 23))->subMinutes(rand(1, 59));
            $name = $names[$i % count($names)];
            $email = strtolower(str_replace(' ', '.', $name)) . '+' . ($i + 1) . '@example.com';

            $records[] = [
                'name' => $name,
                'email' => $email,
                'phone' => '+1 ' . rand(200, 999) . ' ' . rand(100, 999) . ' ' . rand(1000, 9999),
                'subject' => $subjects[$i % count($subjects)],
                'message' => $messages[$i % count($messages)],
                'status' => $statuses[array_rand($statuses)],
                'source' => $sources[array_rand($sources)],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        Enquiry::query()->insert($records);
    }
}
