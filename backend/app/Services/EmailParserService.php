<?php

namespace App\Services;

use App\Models\SuccessfulEmail;
use DOMDocument;
use eXorus\PhpMimeMailParser\Parser;

;

class EmailParserService
{
    public function parseEmails(int $size)
    {
        $count = 0;
        $query = SuccessfulEmail::whereNull('raw_text');

        if ($size < 2) {
            $query->cursor()->each(function ($email) use (&$count) {
                $this->processEmail($email, $count);
            });
        } else {
            $query->chunk($size, function ($emails) use (&$count) {
                foreach ($emails as $email) {
                    $this->processEmail($email, $count);
                }
            });
        }

        return $count;
    }

    private function processEmail(SuccessfulEmail $email, int &$count)
    {
        try {
            if ($this->parseRawTextAndUpdateEmail($email)) {
                $count++;
            }
        } catch (\Exception $e) {
            // Handle exception
        }
    }

    private function parseRawTextAndUpdateEmail(SuccessfulEmail $email): bool
    {
        $plainText = $this->extractPlainText($email->email);
        return $email->update(['raw_text' => $plainText]);
    }

    private function extractPlainText(string $rawEmailData): string
    {
        $parser = new Parser();
        $parser->setText($rawEmailData);

        return strip_tags(
            trim($parser->getMessageBody('text'))
        );
    }
}
