<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StdioAppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->repositoryBoot();
    }

    /**
     * Bind interface -> repository
     *
     * @return void
     */
    private function repositoryBoot()
    {
        // $this->bootMultilple([
        //     interface1::class,
        //     interface2::class
        // ], repository::class);
    }

    /**
     * @param $interfaces list interface repository
     * @param $target repository
     * 
     * @return void
     */
    private function bootMultilple(array $interfaces, $target)
    {
        foreach ($interfaces as $interface) {
            $this->app->singleton($interface, $target);
        }
    }
}
