<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['type', 'name', 'socket', 'ram_type', 'power_w', 'tier', 'price'])]
class Component extends Model
{
    public const TYPES = [
        'cpu' => 'Процессор',
        'motherboard' => 'Материнская плата',
        'gpu' => 'Видеокарта',
        'ram' => 'Оперативная память',
        'psu' => 'Блок питания',
        'case' => 'Корпус',
    ];

    public function builds(): BelongsToMany
    {
        return $this->belongsToMany(Build::class, 'build_component');
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
