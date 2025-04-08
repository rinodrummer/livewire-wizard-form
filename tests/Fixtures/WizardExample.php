<?php

namespace LivewireWizardForm\Tests\Fixtures;

use Livewire\Component;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;
use LivewireWizardForm\Wizard\IsWizard;

class WizardExample extends Component implements WizardComponent
{
    use IsWizard;

    public function steps(): array
    {
        return [
            'first',
            'second',
        ];
    }
}
