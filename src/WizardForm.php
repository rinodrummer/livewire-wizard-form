<?php

namespace LivewireWizardForm;

use LivewireWizardForm\Wizard\Contracts\StepComponent;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;

// @codeCoverageIgnoreStart
class WizardForm
{
    /**
     * Determines if the package should permit to step components to not have a parent wizard
     * component.
     *
     * @var bool
     *
     * @see StepComponent
     * @see WizardComponent
     */
    protected bool $permitsOrphanedSteps = false;

    /**
     * Enables step components to not have a parent wizard component.
     * Should be used for tests.
     *
     * @return void
     */
    public function permitOrphanedSteps(): void
    {
        $this->permitsOrphanedSteps = true;
    }

    /**
     * Disables step components to not have a parent wizard component.
     * Should be used for tests.
     *
     * @return void
     */
    public function prohibitOrphanedSteps(): void
    {
        $this->permitsOrphanedSteps = false;
    }

    /**
     * Returns the actual possibility of a step components to not have a parent wizard component.
     *
     * @return bool
     */
    public function areOrphanedStepsPermitted(): bool
    {
        return $this->permitsOrphanedSteps;
    }
}
// @codeCoverageIgnoreEnd
