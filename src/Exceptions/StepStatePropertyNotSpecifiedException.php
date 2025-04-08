<?php

namespace LivewireWizardForm\Exceptions;

use LivewireWizardForm\Exceptions\Concerns\IsStepException;
use LivewireWizardForm\Facades\WizardForm;
use LivewireWizardForm\Wizard\Contracts\StepComponent;

class StepStatePropertyNotSpecifiedException extends \Exception
{
    use IsStepException;

    /**
     * @throws StepMustAlwaysBeChildOfWizardException
     */
    public function __construct(
        protected StepComponent $step,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $this->wizard = $step->getParentWizardComponent();

        $stepClass = basename(str_replace('\\', '/', $this->step::class));

        $target = $stepClass;

        if (! WizardForm::areOrphanedStepsPermitted()) {
            $wizardClass = basename(str_replace('\\', '/', $this->wizard::class));

            $target .= " of $wizardClass";
        }

        parent::__construct(
            "Step state property has not been specified for $target",
            $code,
            $previous
        );
    }
}
