# Simple Dependency Injection

## Getting Started

1. Create a new instance of \Di\ObjectManager

```php
$objectManager = new \Di\ObjectManager;
```
2. Use ObjectManager to retrieve other classes

```php
$someClass = $objectManager->get(\Namespace\Class::class);
```