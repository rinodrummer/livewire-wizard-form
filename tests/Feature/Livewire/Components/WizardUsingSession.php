<?php

namespace LivewireWizardForm\Tests\Feature\Livewire\Components;

use Livewire\Component;
use Illuminate\Contracts\View\View;
use LivewireWizardForm\Wizard\IsWizard;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;
use LivewireWizardForm\Wizard\Attributes\KeepStateInSession;

#[KeepStateInSession('state')]
class WizardUsingSession extends Component implements WizardComponent
{
    use IsWizard;

    public function steps(): array
    {
        return [
            'first',
            'second',
        ];
    }

    public function render(): View
    {
        return view('test::wizard');
    }
}
