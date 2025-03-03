<?php

namespace App\Models;

use Database\Factories\SuccessfulEmailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuccessfulEmail extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['email', 'raw_text'];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): SuccessfulEmailFactory
    {
        return SuccessfulEmailFactory::new();
    }
}
