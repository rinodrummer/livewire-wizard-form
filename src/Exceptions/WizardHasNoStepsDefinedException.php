<?php

namespace LivewireWizardForm\Exceptions;

use LivewireWizardForm\Exceptions\Concerns\IsWizardException;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;

class WizardHasNoStepsDefinedException extends \Exception
{
    use IsWizardException;

    public function __construct(
        protected WizardComponent $wizard,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $wizardClass = basename(str_replace('\\', '/', $wizard::class));

        parent::__construct(
            "No steps have been define for wizard $wizardClass",
            $code,
            $previous
        );
    }
}
