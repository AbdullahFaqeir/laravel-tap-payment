<?php


namespace Rywan\LaravelTapPayment\Providers;
use Illuminate\Support\ServiceProvider;

class TapServiceProvider extends ServiceProvider
{

  /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/tappayment.php' => config_path('tappayment.php'),
        ], 'tappayment-config');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $packageConfigFile = __DIR__.'/../../config/tappayment.php';

        $this->mergeConfigFrom(
            $packageConfigFile, 'tappayment'
        );

        //$this->registerBindings();
    }


    /**
     * Registers app bindings and aliases.
     */
    protected function registerBindings()
    {
        $this->app->singleton(TapPayment::class, function () {
            return new TapPayment();
        });

        $this->app->alias(TapPayment::class, 'TapPayment');
    }
}
