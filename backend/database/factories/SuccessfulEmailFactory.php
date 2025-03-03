<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SuccessfulEmail;

class SuccessfulEmailFactory extends Factory
{
    protected $model = SuccessfulEmail::class;

    public function definition()
    {
        $faker = $this->faker;

        return [
            'affiliate_id' => $faker->randomNumber(5),
            'envelope' => json_encode(['to' => $faker->email]),
            'from' => $faker->email,
            'subject' => $faker->sentence,
            'dkim' => 'pass',
            'SPF' => 'pass',
            'spam_score' => $faker->randomFloat(2, 0, 10),
            'email' => $this->generateRealisticEmail(),
            'raw_text' => null, // This will be parsed later
            'sender_ip' => $faker->ipv4,
            'to' => $faker->email,
            'timestamp' => $faker->unixTime,
        ];
    }

    private function generateRealisticEmail()
    {
        $faker = $this->faker;

        return <<<HTML
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; color: #333; }
                h1 { color: #0056b3; }
                p { font-size: 14px; line-height: 1.6; }
                .footer { font-size: 12px; color: #777; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; }
                .cta-button { display: inline-block; background-color: #28a745; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <h1>{$faker->sentence}</h1>
            <p>Hello <strong>{$faker->name}</strong>,</p>
            <p>We noticed you haven't logged in for a while. Come back and check out our new features!</p>

            <ul>
                <li>New personalized recommendations</li>
                <li>Improved user interface</li>
                <li>Exclusive discounts for VIP members</li>
            </ul>

            <p><a href="{$faker->url}" class="cta-button">Visit Your Dashboard</a></p>

            <hr>
            <h2>Your Order Summary</h2>
            <table border="1" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
                <tr>
                    <td>{$faker->word}</td>
                    <td>\${$faker->randomFloat(2, 10, 100)}</td>
                    <td>{$faker->randomDigitNotZero()}</td>
                </tr>
                <tr>
                    <td>{$faker->word}</td>
                    <td>\${$faker->randomFloat(2, 10, 100)}</td>
                    <td>{$faker->randomDigitNotZero()}</td>
                </tr>
            </table>

            <div class="footer">
                <p>Best regards,</p>
                <p><strong>{$faker->company}</strong></p>
                <p>Contact us at <a href="mailto:{$faker->email}">{$faker->email}</a></p>
                <p>Follow us on <a href="{$faker->url}">Social Media</a></p>
            </div>
        </body>
        </html>
        HTML;
    }
}
