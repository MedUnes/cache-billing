<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Exception;

use Exception;

/**
 * Can also grow/be split over more Exception classes, in case refined and granular monitoring is needed.
 */
class CacheBillingException extends Exception
{
}
