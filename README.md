# Laravel Database Email Template

Flexible customization of email template content, with blade file format. content will be stored in database.

Able to store:
- Template type: markdown, view
- Subject: only text
- Body


## Installation


```bash
composer require hxm/laravel-database-email-template
```


* Run migrations to create database table:
```bash
php artisan migrate
```

* Publishing the config file

```bash
php artisan vendor:publish --provider="HXM\LaravelDatabaseEmailTemplate\LaravelDatabaseEmailTemplateServiceProvider" --tag="database_email_template_config"
```



* Create new mailable with instance of DatabaseEmailTempplateIntercafe via command:
```bash
php artisan database-email-template:create DemoMailable --markdown=mail.demo_mailable

# this command will create new Class \App\Mail\DemoMailable
# new template file will be add on folder resources/mail
# markdown is option
```

* After that, you need to add this class `\App\Mail\DemoMailable` into config file. This is the default content of the config file:

```php

use HXM\LaravelDatabaseEmailTemplate\Mail\DemoDatabaseEmailTemplate;

return [
    /**
     * add Maiables list 
     */
    'mailables' => [
        \App\Mail\DemoMailable::class, //add new
        // DemoDatabaseEmailTemplate::class
    ],

    /**
     * Admin route configs
     */
    'route' => [
        'enable' => true,
        'prefix' => 'database-email-templates',
        'as' => 'database-email-templates',
        'middleware' => ['web', 'auth'],
    ],

    /**
     * view configs
     */
    'view' => [
        'namespace' => 'database_email_template'
    ]
];
```
* You can also add your mailable via the boot function in your ServiceProvider: 

```php
use HXM\LaravelDatabaseEmailTemplate\Facades\DatabaseEmailTemplate;

class AppServiceProvider extends ServiceProvider
{
    ...
    function boot() {
        //...
        DatabaseEmailTemplate::addMailable(\App\Mail\DemoMailable::class);
    }
}

```
This package will automatically register the event listeners and data will be inserted into database.

# Using

* Access Admin route index via browser by your config route prefix, by default `https://{yourhost}/database-email-templates`

![image](https://github.com/hoanxuanmai/laravel-database-email-template/blob/master/images/index.png?row=true)

* Create new 

![image](https://github.com/hoanxuanmai/laravel-database-email-template/blob/master/images/create.png?row=true)

* To save the content in the database, you must use the preview function, to make sure there are no errors, then the save button appears.

![image](https://github.com/hoanxuanmai/laravel-database-email-template/blob/master/images/preview.png?row=true)


## Please let me know if there is any problem or need any help. Your contribution is valuable to make the package better.


Please note currently for Laravel 7+ until tested and verified in lower versions. 
