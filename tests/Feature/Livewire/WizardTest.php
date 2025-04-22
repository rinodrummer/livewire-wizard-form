<?php

use Livewire\Livewire;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardUsingSession;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardWithManySteps;
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

    Livewire::test(WizardWithManySteps::class)
        ->assertSuccessful()
        ->assertSee('First step')
        ->assertDontSee('Second step');

});

test('event-driven wizard step navigation works correctly', function () {
    Livewire::test(WizardWithManySteps::class)
        ->assertSuccessful()
        ->assertSet('stepName', 'first')
        ->assertSee('First step')
        ->assertDontSee('Second step')
        ->dispatch('next-step', ['foo' => 'bar'])
        ->assertSet('stepName', 'second')
        ->assertDontSee('First step')
        ->assertSee('Second step')
        ->dispatch('previous-step', ['bar' => 'baz'])
        ->assertSet('stepName', 'first')
        ->assertSee('First step')
        ->assertDontSee('Second step')
        ->dispatch('next-step', ['foo' => 'bar'])
        ->assertSet('stepName', 'second')
        ->assertDontSee('First step')
        ->assertSee('Second step');
});

test('navigating to previous step from first one saves the actual state but stays at the current step', function () {
    Livewire::test(WizardWithManySteps::class)
        ->assertSuccessful()
        ->assertSet('stepName', 'first')
        ->assertSee('First step')
        ->assertDontSee('Second step')
        ->dispatch('previous-step', ['foo' => 'bar'])
        ->assertSet('wizardState', ['first' => ['foo' => 'bar']])
        ->assertSet('stepName', 'first')
        ->assertSee('First step')
        ->assertDontSee('Second step');
})
    ->todo('Solve the rendering issue when navigating back');

test('navigating to next step from last one saves the actual state but stays at the current step', function () {
    $component = Livewire::test(WizardWithManySteps::class);
    $component->assertSuccessful()
        ->assertSet('stepName', 'first')
        ->assertSee('First step')
        ->assertDontSee('Second step')
        ->dispatch('next-step', ['foo' => 'bar']);

    usleep(500);

    $component->assertSet('wizardState', [
        'first' => ['foo' => 'bar'],
    ])
        ->assertSet('stepName', 'second')
        ->assertDontSee('First step')
        ->assertSee('Second step')
        ->dispatch('next-step', ['bar' => 'baz']);

    usleep(500);

    $component->assertSet('wizardState', [
        'first' => ['foo' => 'bar'],
        'second' => ['bar' => 'baz'],
    ])
        ->assertSet('stepName', 'second')
        ->assertDontSee('First step')
        ->assertSee('Second step');
})
    ->todo('Solve the rendering issue when navigating forward');

test('wizard stores a state in session when using the right attribute', function () {
    Livewire::test(WizardUsingSession::class)
        ->dispatch('next-step', ['test1_1' => 'foo'])
        ->assertSessionHas('state', [
            'first' => ['test1_1' => 'foo'],
        ]);
});

test('wizard stores multiple step states in session when using the right attribute', function () {
    $firstStepData = ['test1_1' => 'foo'];
    $secondStepData = [
        'test2_1' => 'bar',
        'test2_2' => 'baz',
    ];

    Livewire::test(WizardUsingSession::class)
        ->assertSet('stepName', 'first')
        ->dispatch('next-step', $firstStepData)
        ->assertSet('stepName', 'second')
        ->dispatch('next-step', $secondStepData)
        ->assertSessionHas(
            'state',
            [
                'first' => $firstStepData,
                'second' => $secondStepData,
            ]
        );
});
