<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'description',
    'user_id',
    'category_id',
    'total_price',
    'compatibility_status',
    'compatibility_message',
    'is_blocked',
])]
class Build extends Model
{
    protected function casts(): array
    {
        return [
            'is_blocked' => 'boolean',
            'total_price' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** связь Многие-ко-Многим со справочником комплектующих через таблицу build_component */
    public function components(): BelongsToMany
    {
        return $this->belongsToMany(Component::class, 'build_component');
    }

    /** возвращает конкретное комплектующее сборки по его типу (cpu, motherboard, gpu, ram, psu, case) */
    public function part(string $type): ?Component
    {
        return $this->components->firstWhere('type', $type);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->oldest();
    }

    /**
     * Проверяет совместимость комплектующих и возвращает итоговую цену, цвет индикатора и описание.
     *
     * @return array{total_price: int, status: string, message: string}
     */
    public static function evaluateCompatibility(
        Component $cpu,
        Component $motherboard,
        Component $gpu,
        Component $ram,
        Component $psu,
        Component $pcCase
    ): array {
        $totalPrice = $cpu->price + $motherboard->price + $gpu->price + $ram->price + $psu->price + $pcCase->price;
        $requiredWatts = $cpu->power_w + $gpu->power_w + 100;

        $redErrors = [];
        $yellowWarnings = [];

        if ($cpu->socket !== $motherboard->socket) {
            $redErrors[] = "сокет процессора ({$cpu->socket}) не совпадает с сокетом платы ({$motherboard->socket})";
        }

        if ($ram->ram_type !== $motherboard->ram_type) {
            $redErrors[] = "тип памяти ({$ram->ram_type}) не подходит к плате ({$motherboard->ram_type})";
        }

        if ($psu->power_w < $requiredWatts) {
            $redErrors[] = "мощности БП ({$psu->power_w} Вт) не хватает, нужно минимум {$requiredWatts} Вт";
        }

        if ($redErrors !== []) {
            return [
                'total_price' => $totalPrice,
                'status' => 'red',
                'message' => 'Несовместимо: ' . implode('; ', $redErrors) . '.',
            ];
        }

        if (($gpu->tier - $cpu->tier) >= 2) {
            $yellowWarnings[] = "процессор ({$cpu->name}) слишком слабый для видеокарты ({$gpu->name}), в играх будет упор в процессор";
        } elseif (($cpu->tier - $gpu->tier) >= 3) {
            $yellowWarnings[] = "видеокарта ({$gpu->name}) слабовата для процессора ({$cpu->name})";
        }

        if ($psu->power_w < ($requiredWatts + 70)) {
            $yellowWarnings[] = "блок питания ({$psu->power_w} Вт) выбран почти впритык (потребление около {$requiredWatts} Вт)";
        }

        if ($yellowWarnings !== []) {
            return [
                'total_price' => $totalPrice,
                'status' => 'yellow',
                'message' => 'Работает, но есть замечания: ' . implode('; ', $yellowWarnings) . '.',
            ];
        }

        return [
            'total_price' => $totalPrice,
            'status' => 'green',
            'message' => "Сборка совместима. Сокет {$cpu->socket}, память {$ram->ram_type}, питание {$psu->power_w} Вт с запасом.",
        ];
    }
}
