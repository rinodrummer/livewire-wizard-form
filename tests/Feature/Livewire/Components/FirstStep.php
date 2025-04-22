<?php

namespace LivewireWizardForm\Tests\Feature\Livewire\Components;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use LivewireWizardForm\Wizard\Attributes\StepStateProperty;
use LivewireWizardForm\Wizard\Contracts\StepComponent;
use LivewireWizardForm\Wizard\IsStep;

class FirstStep extends Component implements StepComponent
{
    use IsStep;

    #[StepStateProperty]
    public $data = [
        'test1_1' => null,
        'test1_2' => null,
    ];

    public function render(): View
    {
        return view('test::first-step');
    }
}
