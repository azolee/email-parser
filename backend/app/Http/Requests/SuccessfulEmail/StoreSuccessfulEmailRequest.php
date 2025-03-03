<?php

namespace App\Http\Requests\SuccessfulEmail;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuccessfulEmailRequest extends FormRequest
{
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'affiliate_id' => ['required', 'integer'],
            'envelope' => ['required', 'string'],
            'from' => ['required', 'string'],
            'subject' => ['required', 'string'],
            'dkim' => ['nullable', 'string'],
            'SPF' => ['nullable', 'string'],
            'spam_score' => ['nullable', 'numeric'],
            'email' => ['required', 'string'],
            'raw_text' => ['nullable', 'string'],
            'sender_ip' => ['nullable', 'string', 'max:50'],
            'to' => ['required', 'string'],
            'timestamp' => ['required', 'integer'],
        ];
    }
}
