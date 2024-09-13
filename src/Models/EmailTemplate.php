<?php

namespace HXM\LaravelDatabaseEmailTemplate\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'hxm_email_templates';

    protected $fillable = ['mailable', 'type', 'subject', 'content'];
}
