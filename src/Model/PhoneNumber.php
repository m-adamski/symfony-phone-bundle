<?php

namespace Adamski\Symfony\PhoneNumberBundle\Model;

use Serializable;
use JsonSerializable;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumber as BasePhoneNumber;
use libphonenumber\NumberParseException;

class PhoneNumber implements JsonSerializable, Serializable {

    /**
     * @var string
     */
    protected $number;

    /**
     * @var string|null
     */
    protected $country;

    /**
     * @var PhoneNumberUtil
     */
    protected $phoneNumberUtil;

    /**
     * PhoneNumber constructor.
     *
     * @param string      $number
     * @param string|null $country
     */
    public function __construct(string $number, $country = null) {
        $this->number = $number;
        $this->country = $country;
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * Create the PhoneNumber instance.
     *
     * @param string $number
     * @param null   $country
     * @return static
     */
    public static function make(string $number, $country = null) {
        return new static($number, $country);
    }

    /**
     * Generate instance of BasePhoneNumber.
     *
     * @return bool|BasePhoneNumber
     */
    public function getPhoneNumberInstance() {
        try {
            return $this->phoneNumberUtil->parse($this->number, $this->country);
        } catch (NumberParseException $exception) {
            return false;
        }
    }

    /**
     * Format the phone number in international format.
     *
     * @return string
     */
    public function formatInternational() {
        return $this->format(PhoneNumberFormat::INTERNATIONAL);
    }

    /**
     * Format the phone number in national format.
     *
     * @return string
     */
    public function formatNational() {
        return $this->format(PhoneNumberFormat::NATIONAL);
    }

    /**
     * Format the phone number in E164 format.
     *
     * @return string
     */
    public function formatE164() {
        return $this->format(PhoneNumberFormat::E164);
    }

    /**
     * Format the phone number in RFC3966 format.
     *
     * @return string
     */
    public function formatRFC3966() {
        return $this->format(PhoneNumberFormat::RFC3966);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize() {
        return $this->formatE164();
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized) {
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
        $this->number = $serialized;
        $this->country = $this->phoneNumberUtil->getRegionCodeForNumber($this->getPhoneNumberInstance());
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() {
        return $this->formatE164();
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->formatE164();
    }

    /**
     * Format the phone number to specified format.
     *
     * @param int $format
     * @return bool|string
     */
    private function format(int $format) {
        if ($phoneNumberInstance = $this->getPhoneNumberInstance()) {
            return $this->phoneNumberUtil->format($phoneNumberInstance, $format);
        }

        return false;
    }
}
