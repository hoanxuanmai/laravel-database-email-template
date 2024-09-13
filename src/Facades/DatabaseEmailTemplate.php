<?php

namespace HXM\LaravelDatabaseEmailTemplate\Facades;

use \Illuminate\Contracts\View\Factory;
use \Illuminate\View\View;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void addMailable(string ...$mailables)
 * @method static array getEmailTemplateTypes()
 * @method static array getMailablesList()
 * @method static string getRouteByName(string $name, $parameters = [], bool $absolute = true)
 * @method static Factory|View getViewByName(string $name, array $data = [], array $mergeData = [])
 */
class DatabaseEmailTemplate extends Facade
{
    static function getFacadeAccessor()
    {
        return \HXM\LaravelDatabaseEmailTemplate\DatabaseEmailTemplateInstance::class;
    }
}
