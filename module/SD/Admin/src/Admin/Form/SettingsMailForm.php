<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.22
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

final class SettingsMailForm extends Form implements InputFilterProviderInterface
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
        parent::__construct('settings-mail');
    }

    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'host',
            'attributes' => [
                'required'    => true,
                'size'        => 40,
                'class'       => 'settings_host',
                'placeholder' => 'ENTER_HOST',
                'value'       => $this->config['host'],
            ],
            'options' => [
                'label' => 'HOST',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'name',
            'attributes' => [
                'required'    => true,
                'size'        => 40,
                'class'       => 'settings_name',
                'placeholder' => 'ENTER_NAME',
                'value'       => $this->config['name'],
            ],
            'options' => [
                'label' => 'NAME',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'port',
            'attributes' => [
                'required'    => true,
                'size'        => 40,
                'class'       => 'settings_port',
                'placeholder' => 'ENTER_PORT',
                'value'       => $this->config['port'],
            ],
            'options' => [
                'label' => 'PORT',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'username',
            'attributes' => [
                'required'    => true,
                'size'        => 40,
                'class'       => 'settings_username',
                'placeholder' => 'ENTER_USERNAME',
                'value'       => $this->config['username'],
            ],
            'options' => [
                'label' => 'USERNAME',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'password',
            'attributes' => [
                'required'    => true,
                'size'        => 40,
                'class'       => 'settings_password',
                'placeholder' => 'ENTER_PASSWORD',
                'value'       => $this->config['password'],
            ],
            'options' => [
                'label' => 'PASSWORD',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'ssl',
            'attributes' => [
                'required'    => true,
                'size'        => 40,
                'class'       => 'settings_ssl',
                'placeholder' => 'ENTER_SSL_CERT_TYPE',
                'value'       => $this->config['ssl'],
            ],
            'options' => [
                'label' => 'SSL_CERT_TYPE',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Email',
            'name'       => 'from',
            'attributes' => [
                'required'    => true,
                'size'        => 40,
                'class'       => 'settings_from',
                'placeholder' => 'ENTER_ADMIN_EMAIL',
                'value'       => $this->config['from'],
            ],
            'options' => [
                'label' => 'ADMIN_EMAIL',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Select',
            'name'       => 'connection_class',
            'attributes' => [
                'required' => true,
                'class'    => 'settings_connection_class',
                'value'    => $this->config['connection_class'],
            ],
            'options' => [
                'label'         => 'CONNECTION_TYPE',
                'value_options' => $this->config['connection_classes'],
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Csrf',
            'name'    => 's',
            'options' => [
                'csrf_options' => [
                    'timeout' => 3600,
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
    }

    public function getInputFilterSpecification()
    {
        return [
            [
                'name'     => 'host',
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
                            'max'      => 255,
                        ],
                    ],
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
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 255,
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'port',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
            [
                'name'     => 'from',
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
                'name'     => 'connection_class',
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
                            'max'      => 255,
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'username',
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
                            'max'      => 255,
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'password',
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
                            'max'      => 255,
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'ssl',
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
                            'max'      => 255,
                        ],
                    ],
                ],
            ],
        ];
    }
}
