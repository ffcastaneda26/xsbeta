<?php

namespace App\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Illuminate\Support\Facades\App;
use Filament\Support\Contracts\HasLabel;


enum PlanTypeEnum:string implements HasLabel,HasColor,HasIcon
{
    case Monthly = 'Monthly';
    case Annualy = 'Annualy';



    public function getLabel(): ?string
    {
        if(App::isLocale('en')){
            return match ($this) {
                self::Monthly => 'Monthly',
                self::Annualy=> 'Annualy',
            };
        }
        return match ($this) {
            self::Monthly => 'Mensual',
            self::Annualy=> 'Anual'
        };

    }

    public function getColor(): array|string|null
    {
        return match ($this) {

            self::Monthly => 'warning',
            self::Annualy=> 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Monthly => 'heroicon-m-bell-alert',
            self::Annualy=> 'heroicon-m-check',
        };
    }

    public function getFontAwasomeIcon(): ?string
    {
        return match ($this) {
            self::Monthly => 'fa-regular fa-bell',
            self::Annualy=> 'fa-solid fa-check-circle',
        };

    }
}
