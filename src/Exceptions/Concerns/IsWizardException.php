<?php

namespace LivewireWizardForm\Exceptions\Concerns;

use LivewireWizardForm\Wizard\Contracts\WizardComponent;

trait IsWizardException
{
    protected WizardComponent $wizard;

    /**
     * Returns the step component's parent wizard.
     */
    public function getWizardComponent(): WizardComponent
    {
        return $this->wizard;
    }
}
