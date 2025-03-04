<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SuccessfulEmail;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SuccessfulEmail>
 */
class SuccessfulEmailFactory extends Factory
{
    protected $model = SuccessfulEmail::class;
    protected $faker;
    protected $boundary;

    public function __construct(...$args)
    {
        parent::__construct(...$args);
        $this->faker = Faker::create();
        $this->boundary = md5(uniqid());
    }

    public function definition()
    {
        $from = $this->faker->companyEmail;
        $to = $this->faker->email;
        $subject = "How {$this->faker->company} Built {$this->faker->word} to Handle {$this->faker->numberBetween(1000, 5000000)} Containers";
        $textBody = $this->generateTextBody($subject);
        $htmlBody = $this->generateHtmlBody($subject);
        $rawEmail = $this->generateRawEmail($from, $to, $subject, $textBody, $htmlBody);

        return [
            'affiliate_id' => $this->faker->randomNumber(5),
            'envelope' => json_encode(['to' => $to]),
            'from' => $from,
            'subject' => $subject,
            'dkim' => 'pass',
            'SPF' => 'pass',
            'spam_score' => $this->faker->randomFloat(2, 0, 10),
            'email' => $rawEmail,
            'raw_text' => null, // This will be processed later
            'sender_ip' => $this->faker->ipv4,
            'to' => $to,
            'timestamp' => $this->faker->unixTime,
        ];
    }

    private function generateRawEmail($from, $to, $subject, $textBody, $htmlBody)
    {
        $boundary = $this->boundary;
        $messageId = "<" . uniqid() . "@{$this->faker->domainName}>";

        return <<<EOT
Return-Path: <$from>
Received: from {$this->faker->domainName} ([{$this->faker->ipv4}])
    by mx.fake-email-server.com with SMTP id {$this->faker->uuid};
    {$this->faker->date('D, d M Y H:i:s O')}
Message-ID: $messageId
From: $from
To: $to
Subject: $subject
MIME-Version: 1.0
Content-Type: multipart/alternative; boundary="$boundary"

--$boundary
Content-Type: text/plain; charset="utf-8"
Content-Transfer-Encoding: quoted-printable

$textBody

--$boundary
Content-Type: text/html; charset="utf-8"
Content-Transfer-Encoding: quoted-printable

$htmlBody

--$boundary--
EOT;
    }

    private function generateTextBody($subject)
    {
        return <<<TEXT
$subject

Hello {$this->faker->name},

This is an automatically generated email from {$this->faker->company}.

We are excited to share insights on:
- {$this->faker->sentence()}
- {$this->faker->sentence()}
- {$this->faker->sentence()}

Visit our blog for more details: {$this->faker->url}

Best regards,
{$this->faker->company}
TEXT;
    }

    private function generateHtmlBody($subject)
    {
        return <<<HTML
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        h1 { color: #0056b3; }
        .footer { font-size: 12px; color: #777; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>$subject</h1>
    <p>Hello <strong>{$this->faker->name}</strong>,</p>
    <p>We are excited to share insights on:</p>
    <ul>
        <li>{$this->faker->sentence()}</li>
        <li>{$this->faker->sentence()}</li>
        <li>{$this->faker->sentence()}</li>
    </ul>
    <p><a href="{$this->faker->url}">Read More</a></p>

    <div class="footer">
        <p>Best regards,</p>
        <p><strong>{$this->faker->company}</strong></p>
        <p><a href="mailto:{$this->faker->email}">Contact us</a></p>
    </div>
</body>
</html>
HTML;
    }
}
