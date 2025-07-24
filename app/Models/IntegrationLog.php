<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationLog extends Model
{
    protected $fillable = [
        'source',
        'request_url',
        'response',
        'success',
        'error_message',
    ];
}
