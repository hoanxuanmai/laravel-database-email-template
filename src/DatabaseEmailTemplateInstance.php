<?php

namespace HXM\LaravelDatabaseEmailTemplate;

use \Exception;
use HXM\LaravelDatabaseEmailTemplate\Contracts\DatabaseEmailTempalteInterface;
use Illuminate\Support\Arr;

class DatabaseEmailTemplateInstance
{
    const MARKDOWN = "markdown";
    const VIEW = "view";

    static $route = true;
    static $viewDefaultNamespace = 'database_email_template';

    protected $_mailables = [];

    public function __construct()
    {
        $this->addMailable(...Arr::wrap(config(static::getConfigKey() . '.mailables', [])));
    }


    /**
     * Summary of addMailable
     * @param string $mailables
     * @return void
     */
    function addMailable(string ...$mailables)
    {
        foreach ($mailables as $mailable) {
            in_array($mailable, $this->_mailables) || $this->_mailables[] = $mailable;
        }
    }

    static function getConfigKey(): string
    {
        return 'database_email_template';
    }


    public function getEmailTemplateTypes(): array
    {
        return [
            static::MARKDOWN => __(static::MARKDOWN),
            static::VIEW => __(static::VIEW),
        ];
    }

    function getMailablesList(): array
    {
        return $this->_mailables;
    }

    function getRouteByName(string $name, $parameters = [], bool $absolute = true): string
    {
        return route(config(static::getConfigKey() . '.route.as', 'database-email-template') . ".{$name}", $parameters, $absolute);
    }


    /**
     * Summary of getViewByName
     * @param string $name
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function getViewByName(string $name, array $data = [], array $mergeData = [])
    {
        $namespace =  config(static::getConfigKey() . '.view.namespace', static::$viewDefaultNamespace);
        return view($namespace ? "{$namespace}::{$name}" : $name, $data, $mergeData);
    }
}
