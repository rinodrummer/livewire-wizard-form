<?php

namespace LivewireWizardForm\Exceptions;


use LivewireWizardForm\Wizard\Contracts\StepComponent;
use LivewireWizardForm\Exceptions\Concerns\IsStepException;

class StepMustAlwaysBeChildOfWizardException extends \Exception
{
    use IsStepException;

    public function __construct(
        protected StepComponent $step,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $stepClass = basename(str_replace('\\', '/', $step::class));

        parent::__construct(
            "$stepClass can't match any parent wizard component",
            $code,
            $previous
        );
    }
}
