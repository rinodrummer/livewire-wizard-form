<?php

namespace LivewireWizardForm\Tests\Feature\Livewire\Components;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use LivewireWizardForm\Wizard\IsWizard;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;

class WizardWithNoSteps extends Component implements WizardComponent
{
    use IsWizard;

    public function steps(): array
    {
        return [];
    }

    public function render(): View
    {
        return view('test::wizard');
    }
}
