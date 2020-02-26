<?php

/**
 * Numeric timestamp to DateTime helper
 *
 * @author  Mike Rockett
 * @license ISC
 */

namespace Rockett\Support;

use DateTime;

final class ParseTimestamp
{
  public static function fromInt(int $timestamp): DateTime
  {
    return new DateTime(date('c', $timestamp));
  }
}
