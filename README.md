# Livewire Wizard Form — A step form wizard package for Livewire

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rinodrummer/livewire-wizard-form.svg?style=flat-square)](https://packagist.org/packages/rinodrummer/livewire-wizard-form)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/rinodrummer/livewire-wizard-form/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/rinodrummer/livewire-wizard-form/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/rinodrummer/livewire-wizard-form/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/rinodrummer/livewire-wizard-form/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/rinodrummer/livewire-wizard-form.svg?style=flat-square)](https://packagist.org/packages/rinodrummer/livewire-wizard-form)

> [!WARNING]
> This package is currently under active development. Its use is at the developer's 
> discretion and may include breaking changes or experimental features.
> Proceed with caution and always test thoroughly before using in production.

This package is heavily inspired from [spatie/laravel-livewire-wizard],
and it lets create wizard forms with steps easily.

It provides a set of plug-and-play traits/interfaces that let you introduce
mechanics directly into your own [Livewire] components.

The concept behind this package is that **every aspect** of its sourcecode can be
adapted to your needs, by letting you override every bit you need.

## Requirements

The package is compatible with:

- [PHP] &ge; 8.2
- [Laravel] &ge; 10
- [Livewire] 3

## Installation

You can install the package via Composer:

```bash
composer require rinodrummer/livewire-wizard-form
```

## Usage

To create a new wizard component, you need to implement the `WizardComponent`
interface and use the `IsWizard` trait in a Livewire component you created normally.

This trait requires a method to be implemented, which is `steps()`:

```php
namespace App\Livewire;

use Livewire\Component;
use LivewireWizardForm\Wizard\IsWizard;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;

class CreateUserWizard extends Component implements WizardComponent {
    use IsWizard;
    
    public function steps(): array {
        return [
            // List of the step identifiers
        ];
    }
}
```

Usually, this method should return an array of strings, but enums can be used too!

When referencing to an enum (which should be a `\BackedEnum`), we need to pair the
`steps()` method with the `useEnum()` one, this method usually returns `null`, but in
this case we can override it by returning the class-string of the preferred enum
which cases are going to represent the steps:

```php
namespace App\Livewire;

use Livewire\Component;
use App\Enums\CreateUserStep;
use LivewireWizardForm\Wizard\IsWizard;
use LivewireWizardForm\Wizard\Contracts\WizardComponent;

/**
 * @implements WizardComponent<CreateUserStep>
 * @uses IsWizard<CreateUserStep>
 */
class CreateUserWizard extends Component implements WizardComponent {
    use IsWizard;
    
    public function useEnum(): ?string {
        return CreateUserStep::class;
    }
    
    public function steps(): array {
        return CreateUserStep::cases();
    }
}
```

Now, the package basically matched for every component named like `<step name>-step`,
but in some scenarios (especially if paired with enums) maybe needed to customize
the logic behind the step components naming/matching, in this case you can override
the `currentStepComponent()` method, like in the following example:

```php
public function currentStepComponent(): string
{
    $component = match ($this->currentStep()) {
        CreateUserStep::Profile => UserProfileStep::class,
        CreateUserStep::Contacts => UserContactsStep::class,
        CreateUserStep::Avatar => UserAvatarStep::class,
        CreateUserStep::Preferencies => UserPreferenciesStep::class,
    };

    // Uses Livewire's component name resolution to retrieve the component name
    return resolve(Livewire\Mechanisms\ComponentRegistry::class)
        ->getName($component);
}
```

Now it's possible to proceed with the wizard Blade part!

To render the current step, there is a custom Blade component that has to be included
in the Blade file of your component.

This is just wrapper for
[`livewire:dynamic-component`](https://livewire.laravel.com/docs/nesting#dynamic-child-components)
which passes the right set of properties to the current step component:

```bladehtml
<!-- create-user-wizard.blade.php -->
<div>
    <header>
        <h3>Create a new user</h3>
    </header>
    
    <!-- The current step component will be rendered here -->
    <x-wizard::steps />
</div>
```

Consider that this component will not add any wrapping element but directly include
your step components, but you can make this component look as you need!

We've set up our wizard component, now we can create a step component; make a new
Livewire component as usual and then add this package's magic:

```php
use Livewire\Component;
use LivewireWizardForm\Wizard\IsStep;
use LivewireWizardForm\Wizard\Contracts\StepComponent;

class UserProfileStep extends Component implements StepComponent
{
    use IsStep;

    [StepStateProperty]
    public array $data = [
        'username' => '',
        'email' => '',
        'first_name' => '',
        'last_name' => '',
    ];

    public function render()
    {
        return view('livewire.user-profile-step');
    }
}
```

A step must have a state property which will collect its data, and the eligible 
property can be marked in different ways:

1. By using the `StepStateProperty` attribute on a property;
2. By defining a method named `stepProperty()`;
3. By defining a property named `$stateProperty`.

You can adopt your favourite solution!

You can even choose to use [Forms](https://livewire.laravel.com/docs/forms):

```php
use Livewire\Component;
use LivewireWizardForm\Wizard\IsStep;
use App\Livewire\Forms\UserProfileForm;
use LivewireWizardForm\Wizard\Contracts\StepComponent;

class UserProfileStep extends Component implements StepComponent
{
    use IsStep;

    [StepStateProperty]
    public UserProfileForm $form;

    public function render()
    {
        return view('livewire.user-profile-step');
    }
}
```

Doing that, we can achieve a cleaner code while having all the validation logic in
a dedicated file, but right now, no validation will be executed when proceeding to
the next step.

To enable validation before proceeding, we can use one of the following solutions:

1. By using the `ValidatedStep` attribute on the component class;
2. By implementing the `ValidatesStep` interface;

Doing this, the package will validate the step and store the validated data in the
wizard state.

### Navigating through the steps

The package relies on Livewire [Events](https://livewire.laravel.com/docs/events) to
move from a step to another, the dispatched events are the following:

- `previous-step`
- `next-step`

The step component offers two methods to easily perform all the navigation logic:

```php
public function previousStep(bool $quietly = false): void;
public function nextStep(bool $quietly = false): void;
```

Every time they are called, they store the step state in the wizard state to
let the user continue where they left, but this mechanic can be skipped by setting
the `$quitely` argument to `true`.

### Submitting the wizard

To submit the wizard, we can rely on another event, `submit-wizard`.

This can be dispatched by calling one of the following methods:

```php
// Submits the wizard
public function submitWizard(): void;

// Submits the wizard if no other steps are defined, otherwise goes to the next one
public function proceedWithWizard(bool $quietly = false): bool;
```

## FAQs

### Can I add properties or methods to my components?

Absolutely! That's the meaning of this whole package!

Fill free to make your components the way you prefer, even overriding every method
provided by this project's traits!

### 

### Is there a way to easily manage a step icon or a label?

Yes! You can use the [Teleport](https://livewire.laravel.com/docs/teleport) feature
from Livewire (or the directive from [Alpine.js](https://alpinejs.dev/directives/teleport))!

You could do like this:

```bladehtml
<!-- create-user-wizard.blade.php -->
<div>
    <header>
        <div id="step-icon"></div>
        <h3 id="step-label"></h3>
    </header>
    
    <!-- ... -->
</div>

<!-- user-profile-step.blade.php -->
<form method="post" wire:submit.prevent="nextStep">
    <template x-teleport="#step-label">
        <span>{{ __('Create a new user') }}</span>
    </template>
    
    <template x-teleport="#step-icon">
        <svg><!-- ... --></svg>
    </template>
    
    <!-- ... -->
</form>
```

This is just an example, but you can do any magic you prefer to keep your UI aligned
with the current step!

## Testing

Right now the package has majorly been tested by being used in a project, but every
contribution, especially in matter of tests, is extremely welcomed!

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Gennaro Landolfi](https://github.com/rinodrummer)
- [All Contributors](../../contributors)

This package is heavily inspired by [spatie/laravel-livewire-wizard], and has been 
developed using [spatie/package-skeleton-laravel](https://github.com/spatie/package-skeleton-laravel).

Thanks [Spatie](https://spatie.be) to their incredible contribution to the [PHP]
and [Laravel] ecosystems!

A huge thanks goes to **Taylor Otwell** for [Laravel] and **Caleb Porzio** for
[Livewire].

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[PHP]: https://php.net
[Laravel]: https://laravel.com
[Livewire]: https://livewire.laravel.com
[spatie/laravel-livewire-wizard]: https://spatie.be/docs/laravel-livewire-wizard
