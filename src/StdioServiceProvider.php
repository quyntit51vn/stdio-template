<?php

namespace Stdio\StdioTemplate;

use Illuminate\Support\ServiceProvider;
use Stdio\StdioTemplate\DTO\Command\DTOCommand;
use Stdio\StdioTemplate\Repository\Commands\InterfaceCommand;
use Stdio\StdioTemplate\Repository\Commands\RepositoryCommand;

class StdioServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->publishes([
            __DIR__ . '/publishs/Helpers/CollectionStdio.php' => base_path('app/StdioHelpers/CollectionStdio.php')
        ], 'stdio-helper');

        $this->publishes([
            __DIR__ . '/publishs/Providers/StdioAppServiceProvider.php' => base_path('app/Providers/StdioAppServiceProvider.php')
        ], 'stdio-service-provider');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Repository
        $this->commands(RepositoryCommand::class);
        $this->commands(InterfaceCommand::class);

        // DTO
        $this->commands(DTOCommand::class);
    }
}
