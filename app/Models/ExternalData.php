<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalData extends Model
{
    protected $fillable = [
        'source',
        'external_id',
        'raw_data',
        'normalized_data',
    ];

    protected $casts = [
        'raw_data' => 'array',
        'normalized_data' => 'array',
    ];
}
