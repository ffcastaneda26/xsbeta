<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

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

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['es','en'])
                ->circular()
                ->flags([
                    'es'=> asset('images/flags/spain.jpeg'),
                    'en'=> asset('images/flags/usa.jpeg'),
                ])
                ->displayLocale('es')
                ->labels([
                    'es' => 'Español',
                    'en' => 'Inglés',
                ])
                ->flagsOnly();
        });

        Toggle::configureUsing(function (Toggle $toggle): void {
            $toggle
            ->translateLabel()
            ->inline(false)
            ->onIcon('heroicon-m-check-circle')
            ->offIcon('heroicon-m-x-circle')
            ->onColor('success')
            ->offColor('danger');
        });


    }
}
