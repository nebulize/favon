<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTvSeasonListEntryRequest extends FormRequest
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
            'tv_season_id' => 'required|exists:tv_seasons,id',
            'status' => 'required',
            'progress' => 'integer',
        ];
    }

    /**
     * Get the validation error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'tv_season_id.required' => 'Please select a tv season to add to your list.',
            'status.required' => 'Please select the status for this entry',
            'progress.integer' => 'Watch progress must be a valid number',
        ];
    }
}
