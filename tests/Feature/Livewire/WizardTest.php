<?php

use Livewire\Livewire;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardExample;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardWithNoSteps;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardWithOneStep;

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

    Livewire::test(WizardExample::class)
        ->assertSuccessful()
        ->assertSee('First step')
        ->assertDontSee('Second step');
});
