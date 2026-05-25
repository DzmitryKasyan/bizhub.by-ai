<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = ['name', 'email', 'subject', 'message', 'ip_address'];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
