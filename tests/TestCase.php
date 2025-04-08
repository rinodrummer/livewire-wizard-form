<?php

namespace LivewireWizardForm\Tests;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use LivewireWizardForm\Tests\Feature\Livewire\Components\FirstStep;
use LivewireWizardForm\Tests\Feature\Livewire\Components\SecondStep;
use LivewireWizardForm\Tests\Feature\Livewire\Components\Step;
use LivewireWizardForm\Tests\Feature\Livewire\Components\StepWithNoStateProperty;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardWithManySteps;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardWithNoSteps;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardWithOneStep;
use LivewireWizardForm\Tests\Feature\Livewire\Components\WizardWithStepWithoutStateProperty;
use LivewireWizardForm\WizardFormServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        View::addNamespace('test', __DIR__.'/resources/views');

        $this->registerTestComponents();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            WizardFormServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }

    protected function registerTestComponents(): void
    {
        // Wizards
        Livewire::component('wizard-with-no-steps', WizardWithNoSteps::class);
        Livewire::component('wizard-with-one-step', WizardWithOneStep::class);
        Livewire::component('wizard-with-many-steps', WizardWithManySteps::class);
        Livewire::component(
            'wizard-with-step-without-state-property',
            WizardWithStepWithoutStateProperty::class
        );

        // Steps
        Livewire::component('step', Step::class);
        Livewire::component('step-with-no-state-property', StepWithNoStateProperty::class);
        Livewire::component('first-step', FirstStep::class);
        Livewire::component('second-step', SecondStep::class);
    }
}
