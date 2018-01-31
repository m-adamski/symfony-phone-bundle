<?php

namespace Adamski\Symfony\PhoneNumberBundle\Form;

use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PhoneNumberType extends AbstractType {

    /**
     * @var PhoneNumberUtil
     */
    protected $phoneNumberUtil;

    /**
     * PhoneNumberType constructor.
     */
    public function __construct() {
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add("country", ChoiceType::class, [
            "choices" => $this->generateChoices()
        ])->add("number", TextType::class);
    }

    /**
     * Generate choices array.
     *
     * @return array
     */
    private function generateChoices() {
        $responseArray = [];

        foreach ($this->phoneNumberUtil->getSupportedRegions() as $key => $value) {
            $responseArray[$key] = sprintf("+%d", $key);
        }

        return $responseArray;
    }

}
