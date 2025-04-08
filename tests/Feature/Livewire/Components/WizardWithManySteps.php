<?php

namespace LivewireWizardForm\Tests\Feature\Livewire\Components;

use Livewire\Component;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;
use LivewireWizardForm\Wizard\IsWizard;

class WizardWithManySteps extends Component implements WizardComponent
{
    use IsWizard;

    public function steps(): array
    {
        return [
            'first',
            'second',
        ];
    }

    public function render()
    {
        return view('test::wizard');
    }
}
