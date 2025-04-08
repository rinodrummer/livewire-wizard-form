<?php

namespace LivewireWizardForm\Wizard\Attributes;

use Attribute;
use LivewireWizardForm\Wizard\Contracts\ValidatesStep;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;

/**
 * Attribute used to represent a step which will be validated before heading to the next step.
 *
 * @see WizardComponent
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ValidatedStep implements ValidatesStep {}
