<?php

namespace Favon\Tv\Http\Requests;

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
            'list_status_id' => 'required',
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
            'list_status_id.required' => 'Please select the status for this entry',
            'progress.integer' => 'Watch progress must be a valid number',
        ];
    }

    /**
     * Get the request input.
     *
     * @return array
     */
    public function values(): array
    {
        return [
            'list_status_id' => (int)$this->get('list_status_id'),
            'progress' => (int)$this->get('progress'),
            'score' => $this->has('score') ? (int)$this->get('score') : null,
        ];
    }
}
