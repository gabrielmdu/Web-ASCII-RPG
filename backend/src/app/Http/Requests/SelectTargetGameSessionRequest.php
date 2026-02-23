<?php

namespace App\Http\Requests;

use App\Rules\ValidateChoiceIndex;
use Illuminate\Foundation\Http\FormRequest;

class SelectTargetGameSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $session = $this->route('session');
        return $session && $this->user()->can('selectTarget', $session);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'choiceIndex' => [
                'required',
                'integer',
                'min:1',
                new ValidateChoiceIndex($this->route('session')),
            ]
        ];
    }
}
