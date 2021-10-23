<?php

declare(strict_types=1);

namespace Kazuto\LaravelMoney;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MoneyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('money')
            ->hasConfigFile();
    }
}
