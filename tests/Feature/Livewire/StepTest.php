<?php

use Livewire\Livewire;
use LivewireWizardForm\Facades\WizardForm;
use LivewireWizardForm\Tests\Feature\Livewire\Components\StepExample;
use LivewireWizardForm\Tests\Feature\Livewire\Components\StepWithNoStateProperty;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardWithStepWithoutStateProperty;

test('steps component cannot be orphan of a wizard', function () {
    WizardForm::prohibitOrphanedSteps();

    Livewire::test(StepExample::class)
        ->assertDontSee('This is a step');
})->throws(
    \Illuminate\View\ViewException::class,
    "Step can't match any parent wizard component"
)->todo('Fix test not throwing exception when running coverage testing');

test('step component renders successfully', function () {
    WizardForm::permitOrphanedSteps();

    expect(WizardForm::areOrphanedStepsPermitted())->toBeTrue();

    Livewire::test(StepExample::class)
        ->assertSee('This is a step');
});

describe('step component throws exception if not state property specified', function () {
    test('step component without a parent wizard', function () {
        WizardForm::permitOrphanedSteps();

        Livewire::test(StepWithNoStateProperty::class)
            ->assertDontSee('This is a step');
    })->throws(
        Illuminate\View\ViewException::class,
        'Step state property has not been specified for StepWithNoStateProperty',
    );

    test('step component with a parent wizard', function () {
        WizardForm::prohibitOrphanedSteps();

        Livewire::test(WizardWithStepWithoutStateProperty::class)
            ->assertDontSee('This is a step');
    })->throws(
        Illuminate\View\ViewException::class,
        'Step state property has not been specified for StepWithNoStateProperty of WizardWithStepWithoutStateProperty',
    );
});
