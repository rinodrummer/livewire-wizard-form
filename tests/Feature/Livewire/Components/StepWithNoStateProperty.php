<?php

namespace LivewireWizardForm\Tests\Feature\Livewire\Components;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use LivewireWizardForm\Wizard\Contracts\StepComponent;
use LivewireWizardForm\Wizard\IsStep;

class StepWithNoStateProperty extends Component implements StepComponent
{
    use IsStep;

    public function render(): View
    {
        return view('test::step');
    }
}
