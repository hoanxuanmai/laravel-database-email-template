<?php

namespace HXM\LaravelDatabaseEmailTemplate\Http\Requests;

use HXM\LaravelDatabaseEmailTemplate\Contracts\DatabaseEmailTempalteInterface;
use HXM\LaravelDatabaseEmailTemplate\Models\EmailTemplate;
use HXM\LaravelDatabaseEmailTemplate\Facades\DatabaseEmailTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EmailTemplateRequest extends FormRequest
{
    public ?EmailTemplate $template = null;

    public string $table = '';

    function __construct()
    {
        $this->table = (new EmailTemplate())->getTable();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mailable'  => [
                Rule::requiredIf(is_null($this->template)),
                Rule::unique($this->table, 'type')->ignore($this->template)
            ],
            'type' => ['required', Rule::in(array_keys(DatabaseEmailTemplate::getEmailTemplateTypes()))],
            'subject' => 'required',
            'content' => 'required',
        ];
    }

    function prepareForValidation()
    {
        $this->template = $this->route()->parameter('emailTemplate');
    }

    function passedValidation()
    {
        try {
            $mailable = optional($this->template)->mailable ?? $this->input('mailable');
            /**
             * @var DatabaseEmailTempalteInterface $mailable
             */
            $mailable = $mailable::makePreview();

            $mailable->setEmailTemplate(new EmailTemplate(array_merge(['mailable' => $mailable], $this->request->all())));

            $mailable->render();
        } catch (\Exception $e) {
            throw ValidationException::withMessages(['render' => $e->getMessage()]);
        }
    }
}
