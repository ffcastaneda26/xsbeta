<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use App\Http\Responses\LogoutResponse;
use App\Models\Product;
use App\Observers\ProductObserver;
use Filament\Forms\Components\Toggle;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        Product::observe(ProductObserver::class);

        Toggle::configureUsing(function (Toggle $toggle): void {
            $toggle
                ->translateLabel()
                ->inline(false)
                ->onIcon('heroicon-m-check-circle')
                ->offIcon('heroicon-m-x-circle')
                ->onColor('success')
                ->offColor('danger');
        });

        FilamentColor::register([
            // 'danger' => Color::Red,
            'danger' => Color::hex('#ff0000'),
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::Amber,
            'success' => Color::Green,
            'warning' => Color::Amber,
            'indigo' => Color::Indigo,
            'tlahuac' => Color::rgb('rgb(14, 7, 137)'),
            'escolares_validado' => Color::rgb('rgb(116, 242, 186)'),
            'escolares_revalidado' => Color::rgb('rgb(8, 140, 129)'),
            'escolares_rechazado' => Color::rgb('rgb(199, 54,26)'),
        ]);
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }
}
