<?php

namespace Stdio\StdioTemplate\Repository\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class RepositoryCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false && !$this->option('force')) {
            return;
        }

        $this->createRepositoryInterface();
    }

    protected function createRepositoryInterface()
    {
        $repositoryName = $this->getNameModel();

        $this->call('make:interface', [
            'name' => "{$repositoryName}Interface",
            '--force' => $this->option('force')
        ]);
    }

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/RepositoryEloquent.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories\Eloquents';
    }

    /**
     * Get array replacements.
     *
     * @return array
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return $this->replaceModel($stub);
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @param  string  $model
     * @return string
     */
    protected function replaceModel($stub)
    {
        $model = $this->userProviderModel();
        $prefixNameSpaceModel = $this->getNamespace($model);
        $nameModel = $this->getNameModel();
        $replace = [
            '{{ namespaceModel }}' => "$prefixNameSpaceModel\\$nameModel",
            '{{ repository }}'      => $nameModel,
            '{{ model }}'           => $nameModel,
            '{{ interface }}'       => $nameModel,
            '{{ class }}'           => ucfirst($this->argument('name'))
        ];
        $stub = str_replace(
            array_keys($replace),
            array_values($replace),
            $stub
        );

        return  $stub;
    }

    private function getNameModel()
    {
        $name = ucfirst($this->argument('name'));
        return str_replace('Repository', '', Str::studly(class_basename($name)));
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the repository already exists.'],
            ['interface', 'i', InputOption::VALUE_NONE, 'Create a new interface for the repository.'],
        ];
    }
}
