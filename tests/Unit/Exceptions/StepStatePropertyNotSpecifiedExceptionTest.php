<?php

use LivewireWizardForm\Facades\WizardForm;
use LivewireWizardForm\Tests\Feature\Livewire\Components\Step;
use LivewireWizardForm\Exceptions\StepStatePropertyNotSpecifiedException;

it('binds the right step component without any parent', function () {
    WizardForm::permitOrphanedSteps();

    $step = new Step();

    $exception = new StepStatePropertyNotSpecifiedException($step);

    expect($step)
        ->toBeInstanceOf(Step::class)
        ->and($exception)
        ->toBeInstanceOf(StepStatePropertyNotSpecifiedException::class)
        ->and($exception->getStepComponent())
        ->toBe($step)
        ->and($exception->getWizardComponent())
        ->toBeNull();
});
