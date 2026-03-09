<?php

namespace App\Http\Requests;

use App\Enums\GameSearchSort;
use App\Http\Requests\Concerns\NormalizeInputTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GameIndexRequest extends FormRequest
{
    use NormalizeInputTrait;

    protected function prepareForValidation()
    {
        $this->merge([
            'public' => $this->toBoolean($this->public, false),
            'asc' => $this->toBoolean($this->asc),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:50'],
            'sort' => ['nullable', Rule::enum(GameSearchSort::class)->except(GameSearchSort::ID)],
            'public' => ['nullable', 'boolean'],
            'asc' => ['nullable', 'boolean'],
        ];
    }
}
