<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    public const SOURCE_OPTIONS = [
        'home',
        'about',
        'contact',
        'service',
        'tour',
        'fleet',
        'booking',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'source',
    ];

    public static function sourceOptions(): array
    {
        return self::SOURCE_OPTIONS;
    }
}
