<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'logs';

    protected $fillable = [
        'level', 'message', 'context', 'created_at'
    ];

    protected $casts = [
        'context' => 'array'
    ];
}
