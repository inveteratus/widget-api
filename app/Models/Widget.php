<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $name
 * @property int $cost
 * @property bool $in_stock
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class Widget extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'cost',
        'in_stock',
    ];

    protected $casts = [
        'cost' => 'integer',
        'in_stock' => 'boolean',
    ];
}
