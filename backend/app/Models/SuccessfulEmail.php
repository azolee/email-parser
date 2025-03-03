<?php

namespace App\Models;

use Database\Factories\SuccessfulEmailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuccessfulEmail extends Model
{
    use SoftDeletes, HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): SuccessfulEmailFactory
    {
        return SuccessfulEmailFactory::new();
    }
}
