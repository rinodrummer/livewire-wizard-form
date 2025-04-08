<?php

namespace LivewireWizardForm\Wizard;

use BackedEnum;
use Livewire\Form;
use ReflectionClass;
use Livewire\Component;
use Livewire\Attributes\Locked;
use LivewireWizardForm\Facades\WizardForm;
use LivewireWizardForm\Wizard\Contracts\ValidatesStep;
use LivewireWizardForm\Wizard\Contracts\StepComponent;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;
use LivewireWizardForm\Wizard\Attributes\ValidatedStep;
use LivewireWizardForm\Wizard\Attributes\StepStateProperty;
use Livewire\Mechanisms\HandleComponents\HandleComponents;
use LivewireWizardForm\Exceptions\StepStatePropertyNotSpecifiedException;
use LivewireWizardForm\Exceptions\StepMustAlwaysBeChildOfWizardException;

/**
 * Trait used to make the given Livewire component a step in a step form.
 *  Is usually paired with {@link StepComponent} interface.
 *
 * @template T of BackedEnum
 *
 * @phpstan-require-extends Component
 * @phpstan-require-implements StepComponent<T>
 */
trait IsStep
{
    /** @var string|T Stores the name or the enum case for the current step. */
    #[Locked]
    public mixed $step;

    /** @var string $stepName Stores the name of the current step. */
    #[Locked]
    public string $stepName;

    /** @var array $steps Collects the steps of the wizard, used for internal scopes. */
    #[Locked]
    public array $steps = [];

    /**
     * Performs the mount of this trait when used by a Livewire {@link Component}.
     *
     * @param array|null $state Initial state.
     *
     * @return void
     *
     * @throws StepStatePropertyNotSpecifiedException
     * @throws StepMustAlwaysBeChildOfWizardException
     */
    public function mountIsStep(?array $state = []): void
    {
        // Used to check if the step has a parenting wizard component
        $this->getParentWizardComponent();

        $this->setStepState($state ?? []);
    }

    // - Step Management

    // --- Step Navigation

    /** @inheritDoc */
    public function previousStep(bool $quietly = false): void
    {
        $data = rescue(
            /** @phpstan-ignore-next-line */
            fn(): array => $this->getStepState(),
            []
        );

        $this->dispatch('previous-step', $data, $quietly);
    }

    /** @inheritDoc */
    public function nextStep(bool $quietly = false): void
    {
        $data = $this->finalizeStepState();

        $this->dispatch('next-step', $data, $quietly);
    }

    /** @inheritDoc */
    public function submitWizard(): void
    {
        $data = $this->finalizeStepState();

        $this->dispatch('submit-wizard', $data);
    }

    /** @inheritDoc */
    public function proceedWithWizard(bool $quietly = false): bool
    {
        if ($this->hasNextStep()) {
            $this->nextStep($quietly);
            return true;
        }

        $this->submitWizard();

        return false;
    }

    // --- Adjacent Steps Detection

    /** @inheritDoc */
    public function hasPreviousStep(): bool
    {
        $key = $this->findStepKey();

        if ($key == 0) {
            return false;
        }

        return true;
    }

    /** @inheritDoc */
    public function hasNextStep(): bool
    {
        $key = $this->findStepKey();

        if ($key === false) {
            return false;
        }

        if ($key == sizeof($this->steps) - 1) {
            return false;
        }

        return true;
    }

    // --- Step Utilities

    /**
     * Searches for the current step in the list of steps.
     *
     * @return false|int|string
     */
    protected function findStepKey(): false|int|string
    {
        return array_search($this->step, $this->steps);
    }

    // - State Management

    /** @inheritDoc */
    public function getWizardState(mixed $step = null): ?array {
        $parent = $this->getParentWizardComponent();

        return $parent?->getWizardState($step);
    }

    /** @inheritDoc */
    public function getStepState(): array
    {
        $dataProp = &$this->{$this->getStatePropertyName()};

        if ($dataProp instanceof Form) {
            return $dataProp->all();
        }

        return $dataProp;
    }

    /** @inheritDoc */
    public function setStepState(?array $data = []): void
    {
        $dataProp = &$this->{$this->getStatePropertyName()};

        if ($dataProp instanceof Form) {
            $dataProp->fill($data);
        }
        else {
            $dataProp = [ ...$dataProp, ...$data ];
        }
    }

    /** @inheritDoc
     * @throws \Throwable
     */
    public function getParentWizardComponent(): ?WizardComponent
    {
        $stack = array_reverse(resolve(HandleComponents::class)::$componentStack);

        $canLookForParent = false;

        foreach ($stack as $component) {
            if (!$canLookForParent) {
                if ($component === $this) {
                    $canLookForParent = true;
                }

                continue;
            }

            if ($component instanceof WizardComponent) {
                return $component;
            }
        }

        if (!WizardForm::areOrphanedStepsPermitted()) {
            throw new StepMustAlwaysBeChildOfWizardException($this);
        }

        return null;
    }

    /**
     * Retrieves the current step state to be stored in the wizard state.
     * It performs a validation if the component class has the {@link ValidatedStep} attribute or
     * if it implements the {@link ValidatesStep} interface.
     *
     * @return array
     *
     * @throws StepStatePropertyNotSpecifiedException
     */
    protected function finalizeStepState(): array
    {
        $data = $this->getStepState();

        $attributes = (new ReflectionClass($this))
            ->getAttributes(ValidatedStep::class);

        if (!empty($attributes) || $this instanceof ValidatesStep) {
            $data = [ ...$data, ...$this->validateStep() ];
        }

        return $data;
    }

    /**
     * Retrieves the state property name by using one of the following elements:
     *
     * - A property named `stateProperty`;
     * - A method named `stateProperty`;
     * - A property having the {@link StepStateProperty} attribute assigned.
     *
     * @throws StepStatePropertyNotSpecifiedException No one of the elements is defined.
     *
     * {@internal {@link ReflectionClass::getProperties(), ReflectionClass::getAttributes(), StepStateProperty}}
     */
    protected function getStatePropertyName(): string
    {
        $stateProperty = match (true) {
            method_exists($this, 'stateProperty') => $this->stateProperty(),
            property_exists($this, 'stateProperty') => $this->stateProperty,
            default => '',
        };

        $stateProperty = trim($stateProperty);

        if ($stateProperty) {
            return $stateProperty;
        }

        $reflectionClass = new ReflectionClass($this);

        foreach ($reflectionClass->getProperties() as $property) {
            $attributes = $property->getAttributes(StepStateProperty::class);

            if (!empty($attributes)) {
                return $property->getName();
            }
        }

        throw new StepStatePropertyNotSpecifiedException($this);
    }

    /**
     * Used to easily override the validation logic.
     *
     * @return array
     *
     * @see self::validate()
     */
    protected function validateStep(): array
    {
        return $this->validate();
    }
}
