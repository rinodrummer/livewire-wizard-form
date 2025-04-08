<?php

namespace LivewireWizardForm\Tests\Feature\Livewire\Components;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use LivewireWizardForm\Wizard\Attributes\StepStateProperty;
use LivewireWizardForm\Wizard\Contracts\StepComponent;
use LivewireWizardForm\Wizard\IsStep;

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
