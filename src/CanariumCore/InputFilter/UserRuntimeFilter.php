<?php

namespace CanariumCore\InputFilter;
use Zend\InputFilter\InputFilter;

class UserRuntimeFilter extends InputFilter
{
    protected $emailValidator;

    public function __construct($emailValidator, $validatePassword = true)
    {
        $this->setEmailValidator($emailValidator);

        $this->add(array(
            'name'       => 'email',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                ),
                $this->getEmailValidator()
            ),
        ));

        if ($validatePassword) {
            $this->add(array(
                'name'       => 'password',
                'required'   => true,
                'filters'    => array(array('name' => 'StringTrim')),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 6,
                        ),
                    ),
                ),
            ));

            $this->add(array(
                'name'       => 'passwordVerify',
                'required'   => true,
                'filters'    => array(array('name' => 'StringTrim')),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 6,
                        ),
                    ),
                    array(
                        'name'    => 'Identical',
                        'options' => array(
                            'token' => 'password',
                        ),
                    ),
                ),
            ));
        }
    }

    public function getEmailValidator()
    {
        return $this->emailValidator;
    }

    public function setEmailValidator($emailValidator)
    {
        $this->emailValidator = $emailValidator;
        return $this;
    }
}
