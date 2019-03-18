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
 * Check if provided IP is in CIDR range.
 * Support IPv4 and IPv6.
 */
class IPRange extends Ip implements RuleValidateInterface
{
    /**
     * @var array Rule properties
     */
    public static $config = [
        'full_class' => __CLASS__,
        'alias' => ['iprange', 'iprng', 'ipr'],
        'args_count' => 1,
        'args_type' => []
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
        $args = \func_get_args();

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
    public function concreteValidate(string $received, string $range): bool
    {
        if (parent::validate($received)) {
            $this->message = 'Received value is not a valid ip address';
            return true;
        }

        //separate address and bit suffix
        $cidr = \explode('/', $range, 2);

        $address = $cidr[0];
        $version = $this->checkVersion($cidr[0]);

        $bits = $cidr[1] ?? 0;
        $bits = $this->checkSuffix((int) $bits, $version);

        $ipv4 = $ipv6 = true;

        if ($version === 4) {
            $ipv4 = !$this->inRangeIpv4($received, $address, $bits);
        }

        if ($version === 6) {
            $ipv6 = !$this->inRangeIpv6($received, $address, $bits);
        }

        if ($ipv4 && $ipv6) {
            $this->message = "Received ip is not in ({$range}) range";
            return true;
        }

        return false;
    }

    /**
     * Check the version of ip address.
     *
     * @param string $ip
     *
     * @return int
     *
     * @throws InvalidArgumentException If provided address is not a valid ip.
     */
    private function checkVersion(string $ip): int
    {
        if (\filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return 4;
        }

        if (\filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return 6;
        }

        throw new InvalidArgumentException('Range must be in valid IP/CIDR format, invalid address.');
    }

    /**
     * Check if an ipv4 is in cidr range.
     *
     * @param string $received
     * @param string $address
     * @param int    $bits
     *
     * @return bool
     */
    private function inRangeIpv4(string $received, string $address, int $bits): bool
    {
        $decimalWildcard = \pow(2, (32 - $bits)) - 1;
        $decimalBits = ~ $decimalWildcard;

        return ((\ip2long($received) & $decimalBits) === (\ip2long($address) & $decimalBits));
    }

    /**
     * Check if an ipv6 is in cidr range.
     *
     * @param string $received
     * @param string $address
     * @param int    $bits
     *
     * @return bool
     */
    private function inRangeIpv6(string $received, string $address, int $bits): bool
    {
        $binaryIp = $this->inetToBits($received);
        $binaryNet = $this->inetToBits($address);

        $ipNetBits = \substr($binaryIp, 0, $bits);
        $netBits = \substr($binaryNet, 0, $bits);

        return $ipNetBits === $netBits;
    }

    /**
     * Convert an ipv6 address to a string of bits.
     *
     * @param string $ipv6
     *
     * @return string
     */
    private function inetToBits(string $ipv6): string
    {
        $unpck = \str_split(\unpack('A16', \inet_pton($ipv6))[1]);

        foreach ($unpck as $key => $char) {
            $unpck[$key] = \str_pad(\decbin(\ord($char)), 8, '0', STR_PAD_LEFT);
        }

        return \implode('', $unpck);
    }

    /**
     * Check for a valid bits suffix in cidr notation.
     *
     * @param int $bits
     * @param int $version
     *
     * @return int
     *
     * @throws InvalidArgumentException If suffix is empty and if suffix is out of range.
     */
    private function checkSuffix(int $bits, int $version): int
    {
        $versions = [4 => 32, 6 => 128];
        $maxBits = $versions[$version];

        if (empty($bits)) {
            throw new InvalidArgumentException('Range must be in valid IP/CIDR format, empty bits for suffix.');
        }

        if ((int) $bits < 1 || (int) $bits > $maxBits) {
            throw new InvalidArgumentException('Range must be in valid IP/CIDR format, invalid bits suffix range.');
        }

        return $bits;
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
