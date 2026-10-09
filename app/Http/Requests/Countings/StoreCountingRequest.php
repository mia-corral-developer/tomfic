<?php

declare(strict_types=1);

namespace App\Http\Requests\Countings;

use Illuminate\Foundation\Http\FormRequest;

final class StoreCountingRequest extends FormRequest
{
    /** @return array<string, string> */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }
}
