<?php

namespace LivewireWizardForm\Wizard\Attributes;

use Attribute;
use Livewire\Features\SupportAttributes\AttributeLevel;
use Livewire\Features\SupportSession\BaseSession;

#[Attribute(Attribute::TARGET_CLASS)]
class KeepStateInSession extends BaseSession
{
    /**
     * Constructs the attribute
     */
    public function __construct(
        protected $key
    ) {
        $this->level = AttributeLevel::ROOT;
        $this->levelName = 'wizardState';

        parent::__construct($key);
    }

    /**
     * Boots the Livewire attribute.
     *
     * @param $component
     * @param AttributeLevel $level
     * @param $name
     * @param $subName
     * @param $subTarget
     *
     * @return void
     */
    public function __boot($component, AttributeLevel $level, $name = null, $subName = null, $subTarget = null)
    {
        parent::__boot(
            $component,
            AttributeLevel::PROPERTY,
            'wizardState',
            $subName,
            $subTarget
        );
    }
}
