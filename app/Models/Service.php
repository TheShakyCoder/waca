<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'description', 'icon', 'color', 'order'])]
class Service extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    /**
     * Generate a random pastel colour as a hex string (e.g. "#bcd9c4").
     * Each channel sits in the upper range to keep the colour soft and light.
     */
    public static function randomPastel(): string
    {
        return sprintf(
            '#%02x%02x%02x',
            random_int(170, 235),
            random_int(170, 235),
            random_int(170, 235),
        );
    }
}
