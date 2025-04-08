<?php

namespace LivewireWizardForm\Wizard\Attributes;

use Attribute;
use LivewireWizardForm\Wizard\Contracts\StepComponent;

/**
 * Attribute used to mark a state property for a step.
 *
 * @see StepComponent
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class StepStateProperty {}
