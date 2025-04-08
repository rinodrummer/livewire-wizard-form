@php
    /** @var \LivewireWizardForm\Wizard\Contracts\WizardComponent $this */
@endphp

<livewire:dynamic-component
    :is="$this->currentStepComponent()"
    :step="$this->currentStep()"
    :stepName="$this->stepName"
    :steps="$this->steps()"
    :state="$this->getCurrentStepState()"
    :key="$this->stepName"
/>
