<?php

namespace LivewireWizardForm\Wizard\Contracts;

use BackedEnum;
use Livewire\Component;
use LivewireWizardForm\Exceptions\StepStatePropertyNotSpecifiedException;
use LivewireWizardForm\Exceptions\StepMustAlwaysBeChildOfWizardException;

/**
 * Provides a set of utility methods to make the given Livewire component step form in a wizard.
 *
 * @template T of BackedEnum
 *
 * @property-read (string|T) $step Stores the name or the enum case for the current step.
 * @property-read string $stepName Stores the name of the current step.
 * @property-read array $steps Collects the steps of the wizard, used for internal scopes.
 */
interface StepComponent
{
    /**
     * Navigates to the previous step, if possible.
     * Should dispatch the `previous-step` event.
     *
     * @param bool $quietly If `true`, doesn't store the step data in the state.
     *
     * @return void
     *
     * @see Component::dispatch()
     */
    public function previousStep(bool $quietly = false): void;

    /**
     * Navigates to the next step, if possible.
     * Should dispatch the `next-step` event.
     *
     * If the step must be validated, it performs the validation before proceeding to the next step
     * and uses the validated data as step state.
     *
     * @param bool $quietly If `true`, doesn't store the step data in the state.
     *
     * @return void
     *
     * @see Component::dispatch()
     */
    public function nextStep(bool $quietly = false): void;

    /**
     * Submits the whole wizard form. It's usually used when no further steps are defined.
     * Should dispatch the `submit-wizard` event.
     *
     * If the step must be validated, it performs the validation before proceeding to submit the
     * wizard.
     *
     * @return void
     *
     * @see Component::dispatch()
     */
    public function submitWizard(): void;

    /**
     * Calls {@link self::nextStep()} or {@link self::submitWizard()} if the step doesn't have any
     * following step.
     * Returns `true` if the wizard can proceed to next steps, or `false` if no further steps are
     * defined and the wizard is going to be submitted.
     *
     * @param bool $quietly If `true`, doesn't store the step data in the state when going to the
     * next step.
     *
     * @return bool
     *
     * @see self::hasNextStep()
     * @see Component::dispatch()
     */
    public function proceedWithWizard(bool $quietly = false): bool;

    /**
     * Detects if the current step has a previous one or not.
     *
     * @return bool
     */
    public function hasPreviousStep(): bool;

    /**
     * Detects if the current step has a next one or not.
     *
     * @return bool
     */
    public function hasNextStep(): bool;

    /**
     * Retrieves the state of the whole wizard component or the one of the given if a step is
     * provided.
     *
     * @param string|BackedEnum|null $step
     *
     * @return array<string, array>|null
     */
    public function getWizardState(mixed $step = null): ?array;

    /**
     * Retrieves the state of the current step.
     *
     * @return array
     *
     * @throws StepStatePropertyNotSpecifiedException
     */
    public function getStepState(): array;

    /**
     * Sets the state of the current step.
     *
     * @param array|null $data
     *
     * @return void
     *
     * @throws StepStatePropertyNotSpecifiedException
     */
    public function setStepState(?array $data = []): void;

    /**
     * Searches for the parent wizard component in the component stack.
     *
     * @return WizardComponent|null
     *
     * @throws StepMustAlwaysBeChildOfWizardException
     *
     * @see HandleComponents::$componentStack
     */
    public function getParentWizardComponent(): ?WizardComponent;
}
