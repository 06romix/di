<?php
declare(strict_types=1);

namespace Di;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DefaultPreference
{
    public function __construct(string $class)
    {
    }
}
