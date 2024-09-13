<?php

namespace HXM\LaravelDatabaseEmailTemplate\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class DatabaseEmailTemplateCreateCommand  extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'database-email-template:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Mail Database Email Template';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Mail';

    protected $fileViewName = null;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            return;
        }

        $this->writeTemplate();
    }

    protected function getFileView()
    {

        if (is_null($this->fileViewName)) {
            $this->fileViewName = $this->option('markdown') ?? 'mail.' . Str::snake($this->argument('name'));
        }
        return $this->fileViewName;
    }


    /**
     * Write the Markdown template for the mailable.
     *
     * @return void
     */
    protected function writeTemplate()
    {
        $path = $this->viewPath(str_replace('.', '/',  $this->getFileView()) . '.blade.php');

        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true);
        }

        $this->files->put($path, $this->option('markdown') ? file_get_contents(__DIR__ . '/stubs/markdown.stub') : "Email Content");
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $class = parent::buildClass($name);

        $class = str_replace('DummyDefaultView', $this->getFileView(), $class);
        $class = str_replace('DummyDefaultTemplate', $this->option('markdown') ? 'MARKDOWN' : "VIEW", $class);

        return $class;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/class.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Mail';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the mailable already exists'],

            ['markdown', 'm', InputOption::VALUE_OPTIONAL, 'Create a new Markdown template for the mailable'],
        ];
    }
}
