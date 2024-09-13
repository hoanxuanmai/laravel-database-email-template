<?php

namespace HXM\LaravelDatabaseEmailTemplate;

use HXM\LaravelDatabaseEmailTemplate\Console\DatabaseEmailTemplateCreateCommand;
use HXM\LaravelDatabaseEmailTemplate\Facades\DatabaseEmailTemplate;
use HXM\LaravelDatabaseEmailTemplate\Http\Controllers\DatabaseEmailTemplateController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelDatabaseEmailTemplateServiceProvider extends ServiceProvider
{
    function register() {}

    function boot()
    {

        $this->mergeConfigFrom(__DIR__ . '/../config/database_email_template.php', DatabaseEmailTemplateInstance::getConfigKey());

        if ($this->app->runningInConsole()) {
            $this->loadInConsole();
        }

        $this->loadViewsFrom(__DIR__ . '/../view', config('database_email_template.view.namespace', DatabaseEmailTemplateInstance::$viewDefaultNamespace));

        $this->app->singleton(DatabaseEmailTemplateInstance::class, function ($app) {
            return new DatabaseEmailTemplateInstance();
        });
        class_alias(DatabaseEmailTemplate::class, 'DatabaseEmailTemplate');

        if (config('database_email_template.route.enable', true)) {
            $this->loadRoute();
        }
    }

    protected function loadInConsole()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/database_email_template.php' => config_path('database_email_template.php')
        ], 'database_email_template_config');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrantions')
        ], 'database_email_template_migration');

        $this->commands([DatabaseEmailTemplateCreateCommand::class]);
    }

    protected function loadRoute()
    {

        Route::group([
            'prefix' => config('database_email_template.route.prefix', 'database-email-templates'),
            'middleware' => config('database_email_template.route.middleware', []),
            'as' => config('database_email_template.route.as', 'database-email-templates') . "."
        ], function () {
            Route::any('/loadDefaultData', [DatabaseEmailTemplateController::class, 'loadDefaultData'])->name('loadDefaultData');
            Route::get('/', [DatabaseEmailTemplateController::class, 'index'])->name('index');
            Route::get('/create', [DatabaseEmailTemplateController::class, 'create'])->name('create');
            Route::post('/', [DatabaseEmailTemplateController::class, 'store'])->name('store');
            Route::get('/{emailTemplate}', [DatabaseEmailTemplateController::class, 'show'])->name('show');
            Route::get('/{emailTemplate}/edit', [DatabaseEmailTemplateController::class, 'edit'])->name('edit');
            Route::put('/{emailTemplate}', [DatabaseEmailTemplateController::class, 'update'])->name('update');
            Route::delete('/{emailTemplate}', [DatabaseEmailTemplateController::class, 'destroy'])->name('destroy');
            Route::put('/', [DatabaseEmailTemplateController::class, 'show'])->name('preview');
        });
    }
}
