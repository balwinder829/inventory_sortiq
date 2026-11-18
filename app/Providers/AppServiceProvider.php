<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         require_once app_path('Helpers/helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {   
        Gate::before(function ($user, $ability) {
            return $user->is_superadmin ? true : null;
        });
        
        Blade::directive('currency', function ($amount) {
            return "<?php echo format_currency($amount); ?>";
        });

        \App\Models\Item::observe(\App\Observers\ItemObserver::class);
    }
}
