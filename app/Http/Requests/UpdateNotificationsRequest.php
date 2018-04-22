<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
        ];
    }

    /**
     * Get the selected notification settings.
     *
     * @return array
     */
    public function getNotificationSettings(): array
    {
        return [
            'notify_messages' => $this->has('notify_messages') && $this->get('notify_messages') === 'on',
            'notify_shows' => $this->has('notify_shows') && $this->get('notify_shows') === 'on',
            'notify_features' => $this->has('notify_features') && $this->get('notify_features') === 'on',
        ];
    }
}
