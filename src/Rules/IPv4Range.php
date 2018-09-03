<?php

/**
 * Linna Filter
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2018, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Filter\Rules;

use InvalidArgumentException;

/**
 * Check if provided ipv4 is in CIDR range.
 *
 */
class IPv4Range extends Ip implements RuleValidateInterface
{
    /**
     * @var array Rule properties
     */
    public static $config = [
        'class' => 'Ip',
        'full_class' => __CLASS__,
        'alias' => ['ipv4range', 'ip4r'],
        'args_count' => 1,
        'args_type' => [],
        'has_validate' => true
    ];

    /**
     * @var string Error message
     */
    private $message = '';

    /**
     * Validate.
     *
     * @return bool
     */
    public function validate(): bool
    {
        $args = func_get_args();

        return $this->concreteValidate($args[0], $args[1]);
    }

    /**
     * Concrete validate.
     *
     * @param string $received
     * @param string $range
     *
     * @return bool
     */
    private function concreteValidate(string $received, string $range): bool
    {
        [$address, $bitSuffix] = $this->getRange($range);

        if (parent::validate($received)) {
            $this->message = 'Received value is not a valid ip address';
            return true;
        }

        $decimalWildcard = pow(2, (32 - $bitSuffix)) - 1;
        $decimalBits = ~ $decimalWildcard;

        $result = ((ip2long($received) & $decimalBits) === (ip2long($address) & $decimalBits));

        if ($result) {
            return false;
        }

        $this->message = "Received ip is not in ({$range}) range";
        return true;
    }

    /**
     * Get ip and bits for subnet mask.
     *
     * @param string $range
     *
     * @return array
     *
     * @throws InvalidArgumentException If range is not in valid format
     */
    private function getRange(string $range): array
    {
        $cidr = explode('/', $range, 2);

        $address = $cidr[0];
        $bitSuffix = $cidr[1] ?? null;

        if (empty($bitSuffix)) {
            throw new InvalidArgumentException('Range must be in valid IP/CIDR format, empty bits for suffix.');
        }

        if ((int) $bitSuffix < 1 || (int) $bitSuffix > 32) {
            throw new InvalidArgumentException('Range must be in valid IP/CIDR format, invalid bits suffix range.');
        }

        if (parent::validate($address)) {
            throw new InvalidArgumentException('Range must be in valid IP/CIDR format, invalid address.');
        }

        return [$address, $bitSuffix];
    }

    /**
     * Return error message.
     *
     * @return string Error message
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
