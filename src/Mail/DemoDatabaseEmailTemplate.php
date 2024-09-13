<?php

namespace HXM\LaravelDatabaseEmailTemplate\Mail;

use App\User;
use HXM\LaravelDatabaseEmailTemplate\Contracts\DatabaseEmailTempalteInterface;
use HXM\LaravelDatabaseEmailTemplate\DatabaseEmailTemplateInstance;
use HXM\LaravelDatabaseEmailTemplate\Traits\MailableHasDatabaseEmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemoDatabaseEmailTemplate  extends Mailable  implements DatabaseEmailTempalteInterface
{
    use Queueable, SerializesModels;
    use MailableHasDatabaseEmailTemplate;

    public $user;

    function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getDefaultTemplateType(): string
    {
        return DatabaseEmailTemplateInstance::MARKDOWN;
    }


    public function getDefaultTemplatePath(): string
    {
        return 'mail.demo';
    }


    static function makePreview(): self
    {
        return app()->make(static::class, [
            'user' => auth()->user()
        ]);
    }
}
