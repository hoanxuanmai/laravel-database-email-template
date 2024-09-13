<?php

namespace HXM\LaravelDatabaseEmailTemplate;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class EmailTemplateComponent extends Component
{
    protected $_template;
    public function __construct($template)
    {
        $this->_template = $template;
    }

    public function render()
    {
        return $this->_template;
    }
    public function resolveView(): string
    {
        $view = $this->_template;

        $factory = Container::getInstance()->make('view');

        return Str::length($view) < 200 && $factory->exists($view)
            ? $view
            : $this->createBladeViewFromString($factory, $view);
    }
}
