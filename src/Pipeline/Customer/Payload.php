<?php

declare(strict_types=1);

/*
 *
 *     This file is part of medunes/cache-billing.
 *
 *     (c) medunes <contact@medunes.net>
 *
 *     This source file is subject to the MIT license that is bundled
 *     with this source code in the file LICENSE.
 *
 */

namespace App\Pipeline\Customer;

use App\Pipeline\BasePayload;

/** @codeCoverageIgnore  */
class Payload extends BasePayload
{
    private array $customer;
    private string $username;

    public function getCustomer(): array
    {
        return $this->customer;
    }

    public function setCustomer(array $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
}
