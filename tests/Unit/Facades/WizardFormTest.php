<?php

use LivewireWizardForm\Facades\WizardForm as WizardFormFacade;
use LivewireWizardForm\WizardForm;

it('instantiates a the right facade', function () {
    expect(WizardFormFacade::getFacadeRoot())
        ->toBeInstanceOf(WizardForm::class);
});

it("doesn't let handle orphaned step components", function () {
    expect(WizardFormFacade::areOrphanedStepsPermitted())
        ->toBeFalse();
});

/*it('can toggle step components to be orphaned when permitted', function () {
    expect(WizardFormFacade::areOrphanedStepsPermitted())
        ->toBeFalse();

    WizardFormFacade::permitOrphanedSteps();

    expect(WizardFormFacade::areOrphanedStepsPermitted())
        ->toBeTrue();

    WizardFormFacade::prohibitOrphanedSteps();

    expect(WizardFormFacade::areOrphanedStepsPermitted())
        ->toBeFalse();
});*/
