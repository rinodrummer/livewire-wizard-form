<?php

namespace LivewireWizardForm\Wizard;

use BackedEnum;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use LivewireWizardForm\Exceptions\WizardHasNoStepsDefinedException;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;

/**
 * Trait used to make the given Livewire component a wizard for step forms.
 * Is usually paired with {@see WizardComponent} interface.
 *
 * @template T of BackedEnum
 *
 * @phpstan-require-extends Component
 *
 * @phpstan-require-implements WizardComponent
 */
trait IsWizard
{
    /**
     * Collects the state of the wizard.
     * The state can be easily retrieved using {@see self::getWizardState()}.
     *
     * @var array<string, array>
     */
    #[Locked]
    public array $wizardState = [];

    /**
     * Stores the name of the current step.
     * To get a better-typed value use {@see self::currentStep()}.
     */
    #[Locked]
    public ?string $stepName = null;

    /**
     * Performs the mount of this trait when used by a Livewire {@link Component}.
     *
     *
     *
     * @throws WizardHasNoStepsDefinedException
     */
    public function mountIsWizard(?string $step = null): void
    {
        if (empty($this->steps())) {
            throw new WizardHasNoStepsDefinedException($this);
        }

        $this->setStep($step ?? $this->steps()[0]);
    }

    // - Step Management

    /** {@inheritDoc} */
    public function currentStep(): string|BackedEnum|null
    {
        /** @var BackedEnum $enum */
        $enum = $this->useEnum();

        if (! $enum) {
            return $this->stepName;
        }

        return $enum::tryFrom($this->stepName);
    }

    /** {@inheritDoc} */
    public function currentStepComponent(): string
    {
        return $this->stepName.'-step';
    }

    // --- Step Navigation

    /** {@inheritDoc} */
    #[On('previous-step')]
    public function previousStep(array $data = [], bool $quietly = false): bool
    {
        $key = $this->getPreviousStep();

        if (is_null($key)) {
            return false;
        }

        $steps = $this->steps();

        return $this->setStep($steps[$key - 1], $data, $quietly);
    }

    /** {@inheritDoc} */
    #[On('next-step')]
    public function nextStep(array $data = [], bool $quietly = false): bool
    {
        $key = $this->getNextStep();

        if (is_null($key)) {
            return false;
        }

        $steps = $this->steps();

        return $this->setStep($steps[$key + 1], $data, $quietly);
    }

    /** {@inheritDoc} */
    public function setStep(string|BackedEnum $step, array $data = [], bool $quietly = false): bool
    {
        if (! in_array($step, $this->steps())) {
            return false;
        }

        $currentStepName = $this->stepName;

        $this->stepName = is_string($step) ? $step : $step->value;

        if (! $quietly && $currentStepName) {
            $this->wizardState[$currentStepName] = $data;
        }

        return true;
    }

    // --- Adjacent Steps Detection

    /** {@inheritDoc} */
    public function hasPreviousStep(): bool
    {
        $key = $this->getPreviousStep();

        if (is_null($key)) {
            return false;
        }

        return true;
    }

    /** {@inheritDoc} */
    public function hasNextStep(): bool
    {
        $key = $this->getNextStep();

        if (is_null($key)) {
            return false;
        }

        return true;
    }

    // --- Step Typing

    /** {@inheritDoc} */
    public function useEnum(): ?string
    {
        return null;
    }

    // --- Step Definition

    /** {@inheritDoc} */
    abstract public function steps(): array;

    // --- Step Utilities

    /**
     * Gets the previous step's index, if possible.
     */
    protected function getPreviousStep(): ?int
    {
        $steps = $this->steps();

        $key = array_search($this->currentStep(), $steps);

        if ($key == 0) {
            return null;
        }

        return $key;
    }

    /**
     * Gets the next step's index, if possible.
     */
    protected function getNextStep(): ?int
    {
        $steps = $this->steps();

        $key = array_search($this->currentStep(), $steps);

        if ($key === false || $key == count($steps) - 1) {
            return null;
        }

        return $key;
    }

    /**
     * Normalizes the step name as a string if an enum case is passed.
     *
     * @param  string|T  $step
     */
    protected function getStepName(string|BackedEnum $step): string
    {
        if (is_string($step)) {
            return $step;
        }

        return $step->value;
    }

    // - State Management

    /** {@inheritDoc} */
    public function getWizardState(mixed $step = null): ?array
    {
        if (! $step) {
            return $this->wizardState;
        }

        $stepName = $this->getStepName($step);

        return $this->wizardState[$stepName] ?? null;
    }

    /** {@inheritDoc} */
    public function getCurrentStepState(): ?array
    {
        return $this->getWizardState($this->currentStep());
    }
}
