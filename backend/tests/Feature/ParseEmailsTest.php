<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\SuccessfulEmailSeeder;
use App\Models\SuccessfulEmail;

class ParseEmailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_parses_emails_and_updates_raw_text()
    {
        // Arrange: Seed the database with SuccessfulEmail records
        $this->seed(SuccessfulEmailSeeder::class);

        // Act: Run the command for all the emails
        $this->artisan('emails:parse 1')
            ->assertExitCode(0);

        // Assert: Check that the raw_text field is updated
        SuccessfulEmail::all()->each(function ($email) {
            $this->assertNotNull($email->fresh()->raw_text);
        });
    }
}
