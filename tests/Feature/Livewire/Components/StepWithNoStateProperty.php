<?php

namespace LivewireWizardForm\Tests\Feature\Livewire\Components;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use LivewireWizardForm\Wizard\IsStep;
use LivewireWizardForm\Wizard\Contracts\StepComponent;

class StepWithNoStateProperty extends Component implements StepComponent
{
    use IsStep;

    public function render(): View
    {
        return view('test::step');
    }
}
