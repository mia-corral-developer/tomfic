<?php

declare(strict_types=1);

namespace App\Http\Requests\Countings;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Captura de un producto dentro de una ronda (capturador).
 * Re-scan REEMPLAZA: no hay round_type — el mismo (round, product) hace upsert.
 */
final class CaptureCountingItemRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'units' => 'required|integer|min:0',
            'boxes' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|max:4096',
        ];
    }
}
