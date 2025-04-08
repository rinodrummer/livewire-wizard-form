<?php

arch()->preset()->laravel();
arch()->preset()->security();

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('exceptions are in the right namespace')
    ->expect('LivewireWizardForm\\Wizard\\Exceptions')
    ->toBeClasses()
    ->toImplement(Throwable::class)
    ->toHaveSuffix('Exception');

arch('attributes are in the right namespace')
    ->expect('LivewireWizardForm\\Wizard\\Attributes')
    ->toBeClasses()
    ->toHaveAttribute(Attribute::class);

arch('contracts (interfaces) are in the right namespace')
    ->expect('LivewireWizardForm\\Wizard\\Contracts')
    ->toBeInterfaces();

arch('main traits are in the right namespace')
    ->expect('LivewireWizardForm\\Wizard')
    ->toBeTraits()
    ->ignoring('LivewireWizardForm\\Wizard\\**');

arch('every property and method is totally documented')
    ->expect('LivewireWizardForm\\Wizard')
    ->toHaveMethodsDocumented()
    ->toHavePropertiesDocumented();
