<?php

declare(strict_types=1);

namespace Koriym\QueryLocator\Exception;

use LogicException;

/** @psalm-api */
class RuntimeException extends LogicException implements ExceptionInterface
{
}
