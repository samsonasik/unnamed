<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

final class SettingsForm extends Form implements InputFilterProviderInterface
{
    /*
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct()
    {
        parent::__construct('settings-user');
    }

    /**
     * @param ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        $this->add(
            [
            'type'    => 'Zend\Form\Element\File',
            'name'    => 'image',
            'options' => [
                'label'          => 'IMAGE',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\User',
                'property'       => 'image',
            ],
            'attributes' => [
                'id'    => 'image',
                'class' => 'image',
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'name',
            'options' => [
                'label'          => 'NAME',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\User',
                'property'       => 'name',
            ],
            'attributes' => [
                'required'    => 'true',
                'size'        => '40',
                'placeholder' => 'ENTER_NAME',
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'surname',
            'options' => [
                'label'          => 'SURNAME',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\User',
                'property'       => 'surname',
            ],
            'attributes' => [
                'size'        => '40',
                'placeholder' => 'ENTER_SURNAME',
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Email',
            'name'    => 'email',
            'options' => [
                'label'          => 'EMAIL',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\User',
                'property'       => 'email',
            ],
            'attributes' => [
                'required'    => 'true',
                'min'         => '3',
                'size'        => '30',
                'placeholder' => 'johnsmith@example.com',
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'birthDate',
            'options' => [
                'label'          => 'BIRTHDATE',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\User',
                'property'       => 'birthDate',
            ],
            'attributes' => [
                'size'        => '40',
                'class'       => 'datetimepicker',
                'placeholder' => 'YYYY-MM-DD',
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Checkbox',
            'name'    => 'hideEmail',
            'options' => [
                'label'          => 'HIDE_EMAIL',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\User',
                'property'       => 'hideEmail',
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Csrf',
            'name'    => 's',
            'options' => [
                'csrf_options' => [
                    'timeout' => 1400,
                ],
            ],
            ]
        );

        $this->add(
            [
            'name'       => 'submit',
            'attributes' => [
                'type'  => 'submit',
                'id'    => 'submitbutton',
                'value' => 'SAVE',
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Hidden',
            'name'    => 'id',
            'options' => [
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\User',
                'property'       => 'id',
            ],
            ]
        );
    }

    public function getInputFilterSpecification()
    {
        return [
            [
                'name'     => 'id',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
            [
                'name'     => 'name',
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'surname',
                'required' => false,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'email',
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'EmailAddress',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'messages' => ['emailAddressInvalidFormat' => "Email address doesn't appear to be valid."],
                        ],
                    ],
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 5,
                        ],
                    ],
                    ['name' => 'NotEmpty'],
                ],
            ],
            [
                'name'     => 'birthDate',
                'required' => false,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'isDisabled',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
                'validators' => [
                    [
                        'name'    => 'Regex',
                        'options' => [
                            'pattern' => '/^[0-1]+$/',
                        ],
                    ],
                ],
            ],
            [
                'name'       => 'image',
                'required'   => false,
                'validators' => [
                    [
                        'name'    => 'Zend\Validator\File\Size',
                        'options' => [
                            'min'           => '5kB',
                            'max'           => '5MB',
                            'useByteString' => true,
                        ],
                    ],
                    [
                        'name'    => 'Zend\Validator\File\Extension',
                        'options' => [
                            'extension' => [
                                'jpg',
                                'gif',
                                'png',
                                'jpeg',
                                'bmp',
                                'webp',
                            ],
                            'case' => true,
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'hideEmail',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
                'validators' => [
                    [
                        'name'    => 'Regex',
                        'options' => [
                            'pattern' => '/^[0-1]+$/',
                        ],
                    ],
                ],
            ],
        ];
    }
}
