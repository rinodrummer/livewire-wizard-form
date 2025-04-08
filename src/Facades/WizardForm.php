<?php

namespace LivewireWizardForm\Facades;

use Illuminate\Support\Facades\Facade;
use LivewireWizardForm\WizardForm as BaseWizardForm;

class WizardForm extends Facade
{
    /** {@inheritDoc} */
    protected static function getFacadeAccessor(): string
    {
        return BaseWizardForm::class;
    }
}
