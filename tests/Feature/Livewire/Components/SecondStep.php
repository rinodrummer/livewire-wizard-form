<?php

namespace LivewireWizardForm\Tests\Feature\Livewire\Components;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use LivewireWizardForm\Wizard\Attributes\StepStateProperty;
use LivewireWizardForm\Wizard\Contracts\StepComponent;
use LivewireWizardForm\Wizard\IsStep;

class SecondStep extends Component implements StepComponent
{
    use IsStep;

    #[StepStateProperty]
    public $data = [
        'test2_1' => null,
        'test2_2' => null,
    ];

    public function render(): View
    {
        return view('test::second-step');
    }
}
