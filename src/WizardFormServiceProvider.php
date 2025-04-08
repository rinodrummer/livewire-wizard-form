<?php

namespace LivewireWizardForm;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class WizardFormServiceProvider extends PackageServiceProvider
{
    /**
     * Configures the package.
     */
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('livewire-wizard-form')
            ->hasViews('wizard');
    }
}
