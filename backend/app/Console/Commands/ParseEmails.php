<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EmailParserService;

class ParseEmails extends Command
{
    protected $signature = 'emails:parse {size=50}';
    protected $description = 'Parse raw emails and extract plain text body';
    private $emailParserService;

    public function __construct(EmailParserService $emailParserService)
    {
        parent::__construct();
        $this->emailParserService = $emailParserService;
    }

    public function handle()
    {
        $size = intval($this->argument('size'));
        $count = $this->emailParserService->parseEmails($size);
        $this->info("Parsed {$count} emails.");
    }
}
