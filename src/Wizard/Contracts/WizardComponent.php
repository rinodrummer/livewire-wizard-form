<?php

namespace LivewireWizardForm\Wizard\Contracts;

use BackedEnum;

/**
 * Provides a set of utility methods to make the given Livewire component a wizard for step forms.
 *
 * @template T of BackedEnum
 *
 * @property-read array<string, array> $wizardState Collects the state of the wizard.
 * @property-read string $stepName Stores the name of the current step.
 */
interface WizardComponent
{
    /**
     * Specify which enum class to be used to represent a step.
     * If `null`, a string will be used to reference the step.
     *
     * @return class-string<BackedEnum>|null
     */
    public function useEnum(): ?string;

    /**
     * Sets the list of steps to be executed.
     *
     * @return array<int, string|T>
     */
    public function steps(): array;

    /**
     * Retrieves the current step.
     * If {@see self::useEnum()} is set to an enum, returns the enum case related to the current
     * step name.
     *
     * @return string|BackedEnum|null
     */
    public function currentStep(): string|BackedEnum|null;

    /**
     * Returns the name of the component to be used for a given step.
     *
     * @return string
     */
    public function currentStepComponent(): string;

    /**
     * Navigates to the previous step, if possible.
     *
     * @param array $data Data of the current step to be stored in the wizard.
     * @param bool $quietly If `true`, doesn't store the step data in the state.
     *
     * @return bool
     */
    public function previousStep(array $data = [], bool $quietly = false): bool;

    /**
     * Navigates to the next step, if possible.
     *
     * @param array $data Data of the current step to be stored in the wizard.
     * @param bool $quietly If `true`, doesn't store the step data in the state.
     *
     * @return bool
     */
    public function nextStep(array $data = [], bool $quietly = false): bool;

    /**
     * Jumps to a given step, if possibile.
     *
     * @param string|BackedEnum $step
     *
     * @param array $data Data of the current step to be stored in the wizard.
     * @param bool $quietly If `true`, doesn't store the step data in the state.
     *
     * @return bool
     */
    public function setStep(string|BackedEnum $step, array $data = [], bool $quietly = false): bool;

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
     * Retrieves the state of the current step, if already present in the global wizard state.
     *
     * @return array<string, array>|null
     */
    public function getCurrentStepState(): ?array;
}
