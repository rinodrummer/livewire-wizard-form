<?php

namespace LivewireWizardForm\Tests\Feature\Livewire\Components;

use Livewire\Component;
use LivewireWizardForm\Wizard\IsWizard;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;

class WizardWithOneStep extends Component implements WizardComponent
{
    use IsWizard;

    public function steps(): array
    {
        return [
            'step',
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
