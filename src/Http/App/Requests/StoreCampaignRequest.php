<?php

namespace Spatie\Mailcoach\Http\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Mailcoach\Models\Template;
use Spatie\Mailcoach\Traits\UsesMailcoachModels;

class StoreCampaignRequest extends FormRequest
{
    use UsesMailcoachModels;

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email_list_id' => 'nullable',
        ];
    }

    public function template(): Template
    {
        $templateClass = $this->getTemplateClass();

        return $templateClass::find($this->template_id) ?? new $templateClass();
    }
}
