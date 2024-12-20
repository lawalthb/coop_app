<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $faqs = [
            [
                'question' => 'How do I apply for membership?',
                'answer' => 'Download the membership form, fill it out completely, and submit it to our office with the required documents.',
                'order' => 1
            ],
            [
                'question' => 'What are the loan requirements?',
                'answer' => 'Members must have saved for at least 3 months and maintain regular monthly savings to qualify for loans.',
                'order' => 2
            ],
            [
                'question' => 'How are dividends calculated?',
                'answer' => 'Dividends are calculated based on your share capital and patronage of the cooperative\'s services throughout the year.',
                'order' => 3
            ]
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
