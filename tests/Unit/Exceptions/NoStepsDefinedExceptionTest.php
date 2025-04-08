<?php

use LivewireWizardForm\Exceptions\WizardHasNoStepsDefinedException;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardWithNoSteps;

it('binds the right wizard component', function () {
    $wizard = new WizardWithNoSteps;

    $exception = new WizardHasNoStepsDefinedException($wizard);

    expect($wizard)
        ->toBeInstanceOf(WizardWithNoSteps::class)
        ->and($exception)
        ->toBeInstanceOf(WizardHasNoStepsDefinedException::class)
        ->and($exception->getWizardComponent())
        ->toBe($wizard);
});
