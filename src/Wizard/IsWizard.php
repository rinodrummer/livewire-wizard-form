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
 * @phpstan-require-implements WizardComponent<T>
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
        /** @var class-string<T>|null $enum */
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

    /**
     * Performs a navigation to a given step by its index.
     * Stores the state before moving to the chosen step, but can be skipped.
     */
    protected function navigateToStep(mixed $index = null, array $data = [], bool $quietly = false): bool
    {
        $hasForwardingStep = ! is_null($index);
        $hasSetStep = false;

        $this->storeStepState($data, $quietly);

        if ($hasForwardingStep) {
            $steps = $this->steps();

            $hasSetStep = $this->setStep(
                $steps[$index],
                $data
            );
        }

        return $hasForwardingStep && $hasSetStep;
    }

    /** {@inheritDoc} */
    #[On('previous-step')]
    public function previousStep(array $data = [], bool $quietly = false): bool
    {
        return $this->navigateToStep(
            $this->getPreviousStepIndex(),
            $data,
            $quietly
        );
    }

    /** {@inheritDoc} */
    #[On('next-step')]
    public function nextStep(array $data = [], bool $quietly = false): bool
    {
        return $this->navigateToStep(
            $this->getNextStepIndex(),
            $data,
            $quietly
        );
    }

    /**
     * Stores the state of the current step in the wizard state, if possible or not explicitly
     * skipped.
     */
    protected function storeStepState(array $data = [], bool $quietly = false): void
    {
        $currentStepName = &$this->stepName;

        if (! $quietly && $currentStepName) {
            $this->wizardState[$currentStepName] = $data;
        }
    }

    /** {@inheritDoc} */
    public function setStep(string|BackedEnum $step, ?array $data = [], bool $quietly = false): bool
    {
        $this->storeStepState($data, $quietly);

        $newStepName = is_string($step) ? $step : $step->value;

        if ($newStepName === $this->stepName || ! in_array($step, $this->steps())) {
            return false;
        }

        $this->stepName = $newStepName;

        return true;
    }

    // --- Adjacent Steps Detection

    /** {@inheritDoc} */
    public function hasPreviousStep(): bool
    {
        $key = $this->getPreviousStepIndex();

        return ! is_null($key);
    }

    /** {@inheritDoc} */
    public function hasNextStep(): bool
    {
        $key = $this->getNextStepIndex();

        return ! is_null($key);
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

    protected function getCurrentStepIndex(): int
    {
        return array_search($this->currentStep(), $this->steps());
    }

    /**
     * Gets the previous step's index, if possible.
     */
    protected function getPreviousStepIndex(): ?int
    {
        $key = $this->getCurrentStepIndex();

        if ($key == 0) {
            return null;
        }

        return $key - 1;
    }

    /**
     * Gets the next step's index, if possible.
     */
    protected function getNextStepIndex(): ?int
    {
        $steps = $this->steps();

        $key = array_search($this->currentStep(), $steps);

        if ($key === false || $key == count($steps) - 1) {
            return null;
        }

        return $key + 1;
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
