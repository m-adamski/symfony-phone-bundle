<?php

namespace Adamski\Symfony\PhoneNumberBundle\Form;

use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            "label"   => false,
            "choices" => $this->generateChoices($options["countries"]),
            "data"    => 48
        ])->add("number", TextType::class, [
            "label" => false,
            "attr"  => [
                "placeholder" => $options["placeholder"]
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            "countries"   => [],
            "selected"    => false,
            "placeholder" => false
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return "phone_number";
    }

    /**
     * Generate choices array.
     *
     * @param array $customChoices
     * @return array
     */
    private function generateChoices(array $customChoices) {

        $responseArray = [];

        // Generate response array from given custom countries
        if ($customChoices && count($customChoices) > 0) {
            foreach ($customChoices as $regionCode) {
                $countryCode = $this->phoneNumberUtil->getCountryCodeForRegion($regionCode);

                if ($countryCode !== 0) {
                    $responseArray[sprintf("%s (+%d)", strtoupper($regionCode), $countryCode)] = $countryCode;
                }
            }

            return $responseArray;
        }

        // Generate response array from default countries collection
        foreach ($this->phoneNumberUtil->getSupportedRegions() as $key => $value) {
            $responseArray[sprintf("%s (+%d)", $value, $key)] = $key;
        }

        return $responseArray;
    }
}
