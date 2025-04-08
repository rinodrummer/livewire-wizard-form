<?php

use Livewire\Livewire;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardWithOneStep;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardWithManySteps;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardWithNoSteps;

test('wizard component throws exception if not steps are defined', function () {
    Livewire::test(WizardWithNoSteps::class);
})->throws(
    Illuminate\View\ViewException::class,
    'No steps have been define for wizard WizardWithNoSteps',
);

test('wizard component renders successfully', function () {
    Livewire::test(WizardWithOneStep::class)
        ->assertSuccessful()
        ->assertSee('This is a step');

    Livewire::test(WizardWithManySteps::class)
        ->assertSuccessful()
        ->assertSee('First step')
        ->assertDontSee('Second step');
});
