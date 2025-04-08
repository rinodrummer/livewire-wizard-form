<?php

use LivewireWizardForm\Exceptions\StepStatePropertyNotSpecifiedException;
use LivewireWizardForm\Facades\WizardForm;
use LivewireWizardForm\Tests\Feature\Livewire\Components\StepExample;

it('binds the right step component without any parent', function () {
    WizardForm::permitOrphanedSteps();

    $step = new StepExample;

    $exception = new StepStatePropertyNotSpecifiedException($step);

    expect($step)
        ->toBeInstanceOf(StepExample::class)
        ->and($exception)
        ->toBeInstanceOf(StepStatePropertyNotSpecifiedException::class)
        ->and($exception->getStepComponent())
        ->toBe($step)
        ->and($exception->getWizardComponent())
        ->toBeNull();
});
