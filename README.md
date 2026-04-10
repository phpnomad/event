# phpnomad/event

[![Latest Version](https://img.shields.io/packagist/v/phpnomad/event.svg)](https://packagist.org/packages/phpnomad/event)
[![Total Downloads](https://img.shields.io/packagist/dt/phpnomad/event.svg)](https://packagist.org/packages/phpnomad/event)
[![PHP Version](https://img.shields.io/packagist/php-v/phpnomad/event.svg)](https://packagist.org/packages/phpnomad/event)
[![License](https://img.shields.io/packagist/l/phpnomad/event.svg)](https://packagist.org/packages/phpnomad/event)

`phpnomad/event` provides interfaces for event-driven architecture in PHP applications. Components broadcast events when something happens, and listeners react without the publisher needing to know who is listening. That lets you add behavior like sending a welcome email, updating a cache, or writing an audit log by writing a new handler instead of modifying existing code.

This package is interfaces only. It has zero runtime dependencies and it does not ship a dispatcher. For a working event system you also install a concrete implementation. The recommended one is [`phpnomad/symfony-event-dispatcher-integration`](https://packagist.org/packages/phpnomad/symfony-event-dispatcher-integration), which adapts Symfony's EventDispatcher to the `EventStrategy` contract. The package is used in production by [Siren](https://sirenaffiliates.com) and by every PHPNomad package that needs to broadcast or listen for events.

## Installation

```bash
composer require phpnomad/event
```

For a working dispatcher, also install the Symfony integration:

```bash
composer require phpnomad/symfony-event-dispatcher-integration
```

## Quick Start

Define an event. It just needs a stable identifier and whatever payload you want handlers to see.

```php
use PHPNomad\Events\Interfaces\Event;

class UserCreatedEvent implements Event
{
    public function __construct(
        public readonly int $userId,
        public readonly string $email
    ) {}

    public static function getId(): string
    {
        return 'user.created';
    }
}
```

Write a handler that reacts to the event.

```php
use PHPNomad\Events\Interfaces\CanHandle;
use PHPNomad\Events\Interfaces\Event;

class SendWelcomeEmailHandler implements CanHandle
{
    public function __construct(private EmailService $email) {}

    public function handle(Event $event): void
    {
        $this->email->send($event->email, 'Welcome!');
    }
}
```

Declare the event-to-handler mapping on a module so the bootstrapper can wire it up.

```php
use PHPNomad\Events\Interfaces\HasListeners;

class UserModule implements HasListeners
{
    public function getListeners(): array
    {
        return [
            UserCreatedEvent::class => SendWelcomeEmailHandler::class,
        ];
    }
}
```

Broadcast the event from wherever the state change actually happens.

```php
use PHPNomad\Events\Interfaces\EventStrategy;

class UserService
{
    public function __construct(private EventStrategy $events) {}

    public function createUser(string $email): User
    {
        $user = new User($email);
        // persist the user...

        $this->events->broadcast(new UserCreatedEvent(
            userId: $user->getId(),
            email: $user->getEmail()
        ));

        return $user;
    }
}
```

The `UserService` has no knowledge of `SendWelcomeEmailHandler`. Adding a second handler for logging, analytics, or a Slack notification is a new class and one extra line in `getListeners()`. The service code does not change.

## Key Concepts

- `Event`: an object representing something that happened, identified by a static `getId()` string
- `EventStrategy`: the dispatcher interface with `broadcast()`, `attach()`, and `detach()`
- `CanHandle`: the contract a handler implements to react to an event
- `HasListeners`: modules implement this to declare their event-to-handler mappings
- `HasEventBindings`: flexible binding configuration for platform integration layers
- `ActionBindingStrategy`: bridges external systems like WordPress hooks into application events

## Documentation

Full documentation, including the interface reference and event design best practices, lives at [phpnomad.com](https://phpnomad.com).

## License

MIT. See [LICENSE.txt](LICENSE.txt).
