<?php

namespace Adamski\Symfony\PhoneNumberBundle\Validator\Constraints;

use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Adamski\Symfony\PhoneNumberBundle\Model\PhoneNumber;

class PhoneNumberValidator extends ConstraintValidator {

    /**
     * @var PhoneNumberUtil
     */
    protected $phoneNumberUtil;

    /**
     * PhoneNumberValidator constructor.
     */
    public function __construct() {
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint) {
        if ($value && $value instanceof PhoneNumber && !$value->isValidNumber()) {
            $this->context->buildViolation($constraint->getMessage())->addViolation();
        }
    }
}
