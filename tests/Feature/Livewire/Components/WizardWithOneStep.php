<?php

namespace LivewireWizardForm\Tests\Feature\Livewire\Components;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;
use LivewireWizardForm\Wizard\IsWizard;

class WizardWithOneStep extends Component implements WizardComponent
{
    use IsWizard;

    public function steps(): array
    {
        return [
            'step',
        ];
    }

    public function render(): View
    {
        return view('test::wizard');
    }

    public function currentStepComponent(): string
    {
        return $this->currentStep();
    }
}
