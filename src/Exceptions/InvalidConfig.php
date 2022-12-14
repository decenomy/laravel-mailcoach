<?php

namespace Spatie\Mailcoach\Exceptions;

use Exception;

class InvalidConfig extends Exception
{
    public static function invalidAction(string $actionName, string $configuredClass, string $actionClass): self
    {
        return new static("The class currently specified in the `mailcoach.actions.{$actionName}` key '{$configuredClass}' should be or extend `{$actionClass}`.");
    }
}
