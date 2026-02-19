<?php

namespace App\Http\Requests;

use App\Models\GameSession;
use App\Rules\MaxActiveSessions;
use App\Rules\UniqueActiveGameSession;
use Illuminate\Foundation\Http\FormRequest;

class StoreGameSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', GameSession::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'game_id' => [
                'required',
                'exists:games,id',
                new MaxActiveSessions(),
                new UniqueActiveGameSession(),
            ]
        ];
    }
}
