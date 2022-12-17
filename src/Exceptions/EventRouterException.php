<?php

namespace Smoren\EventRouter\Exceptions;

use Smoren\ExtendedExceptions\BaseException;

/**
 * EventRouterException class
 */
class EventRouterException extends BaseException
{
    public const MAX_DEPTH_LEVEL_REACHED = 1;
}
