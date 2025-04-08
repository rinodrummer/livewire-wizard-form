<?php

namespace LivewireWizardForm\Tests\Fixtures;

use Livewire\Component;
use LivewireWizardForm\Wizard\Attributes\StepStateProperty;
use LivewireWizardForm\Wizard\Attributes\ValidatedStep;
use LivewireWizardForm\Wizard\Contracts\StepComponent;
use LivewireWizardForm\Wizard\IsStep;

#[ValidatedStep]
class StepExample extends Component implements StepComponent
{
    use IsStep;

    #[StepStateProperty]
    public array $data = [];
}
