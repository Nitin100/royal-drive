<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Executive Travel, Refined to the Detail',
                'slug' => 'executive-travel-refined-to-the-detail',
                'meta_title' => 'Executive Travel, Refined to the Detail',
                'meta_description' => 'Discover how seamless chauffeur service elevates airport arrivals, client meetings and daily city movement.',
                'featured_image_path' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=1200&q=80',
                'content' => '<p>Luxury transport is not only about the vehicle. It is about silence, timing, precision and the ease of arriving exactly where you need to be.</p><p>For executive clients, a chauffeur service turns time into a controlled asset. It allows meetings to start on time, arrivals to feel composed, and journeys to be restorative rather than stressful.</p><h2>Why detail matters</h2><p>From flight tracking to route planning, discreet coordination and polished presentation, every touchpoint contributes to an exceptional journey.</p><p>When the experience is managed properly, the commute becomes part of the luxury routine rather than a friction point.</p>',
                'is_featured' => true,
            ],
            [
                'title' => 'Why Premium Transfer Planning Matters',
                'slug' => 'why-premium-transfer-planning-matters',
                'meta_title' => 'Why Premium Transfer Planning Matters',
                'meta_description' => 'A thoughtful transfer plan makes every mile smoother, more discreet and more reliable for busy professionals.',
                'featured_image_path' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1200&q=80',
                'content' => '<p>In premium travel, the difference between a good transfer and a great one often comes down to planning.</p><p>Professional route mapping, terminal coordination and clear communication create a calm experience even during busy travel periods.</p><h2>Small details, big impact</h2><p>Arrival windows, luggage handling, vehicle presentation and driver professionalism all contribute to the overall perception of the journey.</p><p>When these details are managed with intention, passengers feel looked after from first contact to final arrival.</p>',
                'is_featured' => true,
            ],
            [
                'title' => 'Luxury Arrivals for Unforgettable Events',
                'slug' => 'luxury-arrivals-for-unforgettable-events',
                'meta_title' => 'Luxury Arrivals for Unforgettable Events',
                'meta_description' => 'From gala evenings to private celebrations, seamless chauffeur service adds polish to every arrival.',
                'featured_image_path' => 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?auto=format&fit=crop&w=1200&q=80',
                'content' => '<p>Private events call for more than just reliable transport. They require presentation, punctuality and a smooth guest experience.</p><p>Whether it is a wedding celebration, gala evening or corporate reception, a luxury chauffeur arrival sets the tone before the event even begins.</p><h2>First impressions count</h2><p>Arriving in a quiet, refined vehicle with a well-presented chauffeur creates an immediate sense of confidence and ease.</p><p>It also helps guests feel welcome, relaxed and ready to enjoy the occasion.</p>',
                'is_featured' => true,
            ],
            [
                'title' => 'The Quiet Power of Airport Transfers',
                'slug' => 'the-quiet-power-of-airport-transfers',
                'meta_title' => 'The Quiet Power of Airport Transfers',
                'meta_description' => 'Thoughtful airport transfer planning removes stress and gives travellers a better way to begin or end a trip.',
                'featured_image_path' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1200&q=80',
                'content' => '<p>Airport transfers are rarely glamorous on paper, but they often define how a trip feels.</p><p>When a passenger arrives after a long flight, the value of a smooth, discreet pickup becomes immediately clear.</p><h2>Travel starts before the journey ends</h2><p>Professional flight monitoring, luggage assistance and a calm, punctual arrival can transform the start or end of a trip into a stress-free experience.</p><p>That is the quiet power of premium airport transfer service.</p>',
                'is_featured' => true,
            ],
            [
                'title' => 'What Defines a Truly Luxury Chauffeur Experience',
                'slug' => 'what-defines-a-truly-luxury-chauffeur-experience',
                'meta_title' => 'What Defines a Truly Luxury Chauffeur Experience',
                'meta_description' => 'Luxury chauffeur service is shaped by precision, discretion and thoughtful details that guests feel instantly.',
                'featured_image_path' => 'https://images.unsplash.com/photo-1503376780353-7c779a196171?auto=format&fit=crop&w=1200&q=80',
                'content' => '<p>The most memorable chauffeur experiences are rarely the loudest. They are the most considered.</p><p>From driver presentation and timing to vehicle quality and route awareness, luxury is carried in every detail.</p><h2>It is all in the experience</h2><p>Travel should feel effortless, private and polished — even in the busiest city routes or busiest travel days.</p><p>That is what separates a routine airport ride from a memorable luxury service.</p>',
                'is_featured' => true,
            ],
        ];

        foreach ($posts as $post) {
            Blog::updateOrCreate(
                ['slug' => $post['slug']],
                $post
            );
        }
    }
}
