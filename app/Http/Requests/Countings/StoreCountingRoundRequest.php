<?php

declare(strict_types=1);

namespace App\Http\Requests\Countings;

use Illuminate\Foundation\Http\FormRequest;

final class StoreCountingRoundRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return [
            'product_location_id' => 'required|exists:product_locations,id',
            'round_type' => 'required|in:c1,c2,c3',
            'capturador_ids' => 'required|array|min:1',
            'capturador_ids.*' => 'integer|exists:users,id',
        ];
    }
}
