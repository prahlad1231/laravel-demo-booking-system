<?php

namespace App\Http\Requests;

use App\Rules\FitsWithinCapacity;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array {
        return [
            'starts_at' => ['required', 'date', 'after:now'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'capacity_used' => [
                'required', 'integer', 'min:1',
                new FitsWithinCapacity(
                    $this->route('space'),
                    $this->date('starts_at'),
                    $this->date('ends_at'),
                ),
            ],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }
}
