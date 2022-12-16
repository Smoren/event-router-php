<?php

namespace Smoren\EventRouter\Exceptions;

use Smoren\ExtendedExceptions\BaseException;

class EventRouterException extends BaseException
{
    public const MAX_DEPTH_LEVEL_REACHED = 1;
}
