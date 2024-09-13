<?php

namespace HXM\LaravelDatabaseEmailTemplate\Traits;


use HXM\LaravelDatabaseEmailTemplate\EmailTemplateComponent;
use HXM\LaravelDatabaseEmailTemplate\Models\EmailTemplate;
use HXM\LaravelDatabaseEmailTemplate\Contracts\DatabaseEmailTempalteInterface;
use HXM\LaravelDatabaseEmailTemplate\DatabaseEmailTemplateInstance;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Mail\Mailable;
use Illuminate\View\Component;


trait MailableHasDatabaseEmailTemplate
{
    protected $_emailTemplate;

    /**
     * @return string
     */
    abstract public function getDefaultTemplateType(): string;
    abstract public function getDefaultTemplatePath(): string;

    abstract static function makePreview(): DatabaseEmailTempalteInterface;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->getEmailTemplate()) {
            $this->applyEmailTemplateFromDatabase();
        } else {
            $this->_applyTemplate($this, $this->getDefaultTemplatePath(), $this->getDefaultTemplateType());
        }
        return $this;
    }


    /**
     * @param \Illuminate\Mail\Mailable|\Illuminate\Notifications\Messages\MailMessage $instance
     * @return Renderable
     */
    function applyTemlateToInstance($instance)
    {
        if ($this->getEmailTemplate()) {
            return $this->applyEmailTemplateFromDatabase($instance);
        }
        return $this->_applyTemplate($instance, $this->getDefaultTemplatePath(), $this->getDefaultTemplateType(), $this->buildViewData());
    }

    public function setEmailTemplate(EmailTemplate $emailTemplate): DatabaseEmailTempalteInterface
    {
        $this->_emailTemplate = $emailTemplate;
        return $this;
    }
    public function getEmailTemplate(): ?EmailTemplate
    {

        is_null($this->_emailTemplate) && $this->_emailTemplate = EmailTemplate::where('mailable', static::class)->first();
        return $this->_emailTemplate;
    }

    public function buildComponentFromDatabase(): ?Component
    {

        $template = $this->getEmailTemplate();

        if ($template) {
            return new EmailTemplateComponent($template->content);
        }
        return null;
    }

    public function applyEmailTemplateFromDatabase($instance = null, $data = []): Renderable
    {
        is_null($instance) && $instance = $this;

        $emailTemplate = $this->getEmailTemplate();
        /** @var Mailable $instance */
        $instance->subject($emailTemplate->subject);

        return $this->_applyTemplate($instance, $this->buildComponentFromDatabase()->resolveView(), $emailTemplate->type, $data);
    }
    /**
     * Summary of _applyTemplate
     * @param \Illuminate\Mail\Mailable|\Illuminate\Notifications\Messages\MailMessage $mailable
     * @param string $templatePath
     * @param string $type
     * @param array $data
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function _applyTemplate($mailable, string $templatePath, string $type, array $data = []): Renderable
    {

        switch ($type) {
            case DatabaseEmailTemplateInstance::MARKDOWN:
                $mailable->markdown($templatePath, $data);
                break;
            default:
                $mailable->view($templatePath, $data);
        }
        return $mailable;
    }
}
