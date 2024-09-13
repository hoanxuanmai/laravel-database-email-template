<?php

namespace HXM\LaravelDatabaseEmailTemplate\Contracts;

use HXM\LaravelDatabaseEmailTemplate\Models\EmailTemplate;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;

interface DatabaseEmailTempalteInterface
{
    public function getDefaultTemplateType(): string;
    public function getDefaultTemplatePath(): string;

    /**
     * @param \Illuminate\Mail\Mailable|\Illuminate\Notifications\Messages\MailMessage $instance
     * @return Renderable
     */
    function applyTemlateToInstance($instance);

    /**
     *
     * @return \App\Contracts\MailableFromDatabaseInterface
     */
    static function makePreview(): DatabaseEmailTempalteInterface;

    public function build();

    function render();

    public function setEmailTemplate(EmailTemplate $emailTemplate): DatabaseEmailTempalteInterface;

    public function getEmailTemplate(): ?EmailTemplate;

    public function buildComponentFromDatabase(): ?Component;

    public function applyEmailTemplateFromDatabase($instance = null, $data = []): Renderable;
}
