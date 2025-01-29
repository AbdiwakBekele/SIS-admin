<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $subscriber = $this->route('subscriber');

        return [
            'name' => 'nullable|min:2|max:100',
            'email' => ['required', 'email', Rule::unique('subscribers')->ignore($subscriber)],
            'unsubscribed_at' => 'nullable|date_format:Y-m-d H:i:s',
        ];
    }

    public function withValidator($validator)
    {
        if (! $validator->passes()) {
            return;
        }

        $validator->after(function ($validator) {
            $uuid = $this->route('subscriber');
        });
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => __('subscriber.props.name'),
            'email' => __('subscriber.props.email'),
            'unsubscribed_at' => __('subscriber.props.unsubscribed_at'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
