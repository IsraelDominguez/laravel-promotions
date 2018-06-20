<?php namespace Genetsis\Promotions\Providers;

use Genetsis\Promotions\Commands\InstallPromotions;
use Genetsis\Promotions\Events\PromoUserSubscriber;
use Illuminate\Support\ServiceProvider;

class PromotionServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->handleMigrations();
        $this->handleRoutes();
        $this->handleViews();
        $this->handleEvents();
        $this->handleCommands();
        $this->registerModelFactories();

        \AdminMenu::add('promotion::partials.promotion_menu');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make(\Illuminate\Database\Eloquent\Factory::class)->load(__DIR__.'/../../database/factories');
    }

    private function registerModelFactories() {
        $this->app->make(\Illuminate\Database\Eloquent\Factory::class)->load(__DIR__.'/../../database/factories');
    }

    private function handleMigrations() {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }

    private function handleRoutes() {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
    }

    private function handleViews() {
        $this->loadViewsFrom(__DIR__.'/../../views', 'promotion');
    }

    private function handleEvents() {
        //\Event::listen( \Genetsis\Promotions\Events\PromoUserCreated::class,\Genetsis\Promotions\Events\PromoUserNotification::class);
        \Event::subscribe(PromoUserSubscriber::class);
    }

    private function handleCommands() {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallPromotions::class
            ]);
        }
    }
}
