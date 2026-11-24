<?php

declare(strict_types=1);

namespace Koriym\QueryLocator\Exception;

use LogicException;

/** @psalm-api */
class QueryFileNotFoundException extends LogicException implements ExceptionInterface
{
}
