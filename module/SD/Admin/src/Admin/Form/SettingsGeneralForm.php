<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

final class SettingsGeneralForm extends Form implements InputFilterProviderInterface
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
        parent::__construct('settings-general');
    }

    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'site_name',
            'attributes' => [
                'required'    => true,
                'size'        => 40,
                'class'       => 'settings_site_name',
                'placeholder' => 'ENTER_SITE_NAMEENTER_SITE_NAME',
                'value'       => $this->config['site_name'],
            ],
            'options' => [
                'label' => 'SITE_NAME',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'site_tag_line',
            'attributes' => [
                'required'    => false,
                'size'        => 40,
                'class'       => 'settings_site_tag_line',
                'placeholder' => 'ENTER_SITE_TAG_LINE',
                'value'       => $this->config['site_tag_line'],
            ],
            'options' => [
                'label' => 'SITE_TAG_LINE',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'site_description',
            'attributes' => [
                'required'    => false,
                'size'        => 40,
                'class'       => 'settings_site_description',
                'placeholder' => 'DESCRIPTION',
                'value'       => $this->config['site_description'],
            ],
            'options' => [
                'label' => 'DESCRIPTION',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'site_keywords',
            'attributes' => [
                'required'    => false,
                'size'        => 40,
                'class'       => 'settings_site_keywords',
                'placeholder' => 'KEYWORDS',
                'value'       => $this->config['site_keywords'],
            ],
            'options' => [
                'label' => 'KEYWORDS',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'site_text',
            'attributes' => [
                'required'    => false,
                'size'        => 40,
                'class'       => 'settings_site_text',
                'placeholder' => 'ENTER_SITE_TEXT',
                'value'       => $this->config['site_text'],
            ],
            'options' => [
                'label' => 'SITE_TEXT',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Email',
            'name'       => 'system_email',
            'attributes' => [
                'required'    => false,
                'size'        => 40,
                'class'       => 'settings_system_email',
                'placeholder' => 'ENTER_SISTEM_EMAIL',
                'value'       => $this->config['system_email'],
            ],
            'options' => [
                'label' => 'SISTEM_EMAIL',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'timezone',
            'attributes' => [
                'required'    => false,
                'size'        => 40,
                'class'       => 'settings_timezone',
                'placeholder' => 'TIMEZONE',
                'value'       => $this->config['timezone'],
            ],
            'options' => [
                'label' => 'TIMEZONE',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Checkbox',
            'name'       => 'robots_indexing',
            'attributes' => [
                'required' => false,
                'class'    => 'settings_robots_indexing',
                'value'    => $this->config['robots_indexing'],
            ],
            'options' => [
                'label' => 'SITE_INDEXING',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Select',
            'name'       => 'date_format',
            'attributes' => [
                'required' => false,
                'class'    => 'settings_date_format',
            ],
            'options' => [
                'label'         => 'DATE',
                'value_options' => $this->config['date_formats'],
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Select',
            'name'       => 'time_format',
            'attributes' => [
                'required' => false,
                'class'    => 'settings_time_format',
            ],
            'options' => [
                'label'         => 'TIME',
                'value_options' => $this->config['time_formats'],
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
                'name'     => 'site_name',
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
                'name'     => 'site_tag_line',
                'required' => false,
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
                'name'     => 'site_description',
                'required' => false,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 0,
                            'max'      => 150,
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'site_keywords',
                'required' => false,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 0,
                            'max'      => 300,
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'site_text',
                'required' => false,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 0,
                            'max'      => 300,
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'system_email',
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
                'name'     => 'timezone',
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
                'name'     => 'robots_indexing',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
            [
                'name'     => 'date_format',
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
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'time_format',
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
                        ],
                    ],
                ],
            ],
        ];
    }
}
