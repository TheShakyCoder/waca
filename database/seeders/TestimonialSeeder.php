<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'title'    => 'A true second home',
                'comment'  => 'The centre has become a true second home for me. The staff are wonderful and there is always something going on for everyone.',
                'name'     => 'Margaret Hughes',
                'order'    => 1,
                'homepage' => true,
                'featured' => true,
            ],
            [
                'title'    => 'My kids love it here',
                'comment'  => 'My children look forward to the activities every week. It is brilliant to have such a welcoming space right on our doorstep.',
                'name'     => 'David Okoro',
                'order'    => 2,
                'homepage' => true,
                'featured' => false,
            ],
            [
                'title'    => 'Made so many friends',
                'comment'  => 'Since joining I have made so many new friends. The volunteers go above and beyond to make everyone feel included.',
                'name'     => 'Susan Patel',
                'order'    => 3,
                'homepage' => true,
                'featured' => false,
            ],
            [
                'title'    => 'Warm and welcoming',
                'comment'  => 'From the moment I walked in I felt welcome. It is a genuinely warm place that brings the whole neighbourhood together.',
                'name'     => 'Tom Bradley',
                'order'    => 4,
                'homepage' => false,
                'featured' => false,
            ],
            [
                'title'    => 'The heart of our village',
                'comment'  => 'This centre is the beating heart of our village. Whatever your age, there is a place for you here.',
                'name'     => 'Eleanor Wright',
                'order'    => 5,
                'homepage' => false,
                'featured' => false,
            ],
            [
                'title'    => 'Support when it mattered',
                'comment'  => 'When times were hard the team here gave me real support and a friendly face. I will always be grateful.',
                'name'     => 'James Fielding',
                'order'    => 6,
                'homepage' => false,
                'featured' => false,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(['title' => $testimonial['title']], $testimonial);
        }
    }
}
