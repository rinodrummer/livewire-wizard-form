<?php

namespace LivewireWizardForm\Tests\Feature\Livewire\Components;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use LivewireWizardForm\Wizard\IsStep;
use LivewireWizardForm\Wizard\Contracts\StepComponent;
use LivewireWizardForm\Wizard\Attributes\StepStateProperty;

class Step extends Component implements StepComponent
{
    use IsStep;

    #[StepStateProperty]
    public $data = [];

    public function render(): View
    {
        return view('test::step');
    }
}
