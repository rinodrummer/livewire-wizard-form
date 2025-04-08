<?php

namespace LivewireWizardForm\Tests\Feature\Livewire\Components;

use Livewire\Component;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;
use LivewireWizardForm\Wizard\IsWizard;

class WizardWithStepWithoutStateProperty extends Component implements WizardComponent
{
    use IsWizard;

    public function steps(): array
    {
        return [
            'step-with-no-state-property',
        ];
    }

    public function render()
    {
        return view('test::wizard');
    }

    public function currentStepComponent(): string
    {
        return $this->currentStep();
    }
}
