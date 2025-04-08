<?php

namespace LivewireWizardForm\Exceptions\Concerns;

use LivewireWizardForm\Wizard\Contracts\StepComponent;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;

trait IsStepException
{
    protected StepComponent $step;

    protected ?WizardComponent $wizard;

    /**
     * Returns the step component which hasn't specified the step state property to be used.
     */
    public function getStepComponent(): StepComponent
    {
        return $this->step;
    }

    /**
     * Returns the step component's parent wizard.
     */
    public function getWizardComponent(): ?WizardComponent
    {
        return $this->wizard;
    }
}
