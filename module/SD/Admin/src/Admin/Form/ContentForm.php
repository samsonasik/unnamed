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

use Doctrine\Common\Persistence\ObjectManager;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

final class ContentForm extends Form implements InputFilterProviderInterface
{
    /*
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct()
    {
        parent::__construct('content');
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

        /*
         * Specific image for this content
         */
        $this->add(
            [
            'type'    => 'Zend\Form\Element\File',
            'name'    => 'preview',
            'options' => [
                'label'          => 'IMAGE',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Content',
                'property'       => 'preview',
            ],
            'attributes' => [
                'id'    => 'preview',
                'class' => 'preview',
            ],
            ]
        );

        /*
         * Gallery for all contents
         */
        $this->add(
            [
            'type'       => 'Zend\Form\Element\File',
            'name'       => 'imageUpload',
            'attributes' => [
                'id'       => 'imgajax',
                'class'    => 'imgupload',
                'multiple' => true,
            ],
            'options' => [
                'label' => 'UPLOAD_IMAGES',
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'title',
            'options' => [
                'label'          => 'TITLE',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Content',
                'property'       => 'title',
            ],
            'attributes' => [
                'required'    => 'true',
                'size'        => '40',
                'id'          => 'seo-caption',
                'placeholder' => 'TITLE',
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'text',
            'options' => [
                'label'          => 'TEXT',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Content',
                'property'       => 'text',
            ],
            'attributes' => [
                'class' => 'ckeditor',
                'rows'  => '5',
                'cols'  => '80',
            ],
            ]
        );

        $valueOptions = [];
        for ($i = 1; $i < 100; $i++) {
            $valueOptions[$i] = $i;
        }
        $this->add([
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'name'    => 'menuOrder',
            'options' => [
                'object_manager'            => $this->objectManager,
                'disable_inarray_validator' => true,
                'target_class'              => 'SD\Admin\Entity\Content',
                'property'                  => 'menuOrder',
                'display_empty_item'        => true,
                'empty_item_label'          => 'MENU_ORDER_TEXT',
                'value_options'             => $valueOptions,
                'label'                     => 'MENU_ORDER',
            ],
        ]);

        $this->add(
            [
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'name'    => 'type',
            'options' => [
                'label'                     => 'TYPE',
                'object_manager'            => $this->objectManager,
                'disable_inarray_validator' => true,
                'target_class'              => 'SD\Admin\Entity\Content',
                'property'                  => 'type',
                'display_empty_item'        => true,
                'empty_item_label'          => 'CONTENT_TYPE',
                'value_options'             => [
                    '0' => 'MENU',
                    '1' => 'NEWS',
                ],
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'date',
            'options' => [
                'label'          => 'DATE',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Content',
                'property'       => 'date',
            ],
            'attributes' => [
                'size' => '20',
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'name'    => 'menu',
            'options' => [
                'label'                     => 'MENU',
                'disable_inarray_validator' => true,
                'object_manager'            => $this->objectManager,
                'target_class'              => 'SD\Admin\Entity\Menu',
                'property'                  => 'caption',
                'display_empty_item'        => true,
                'empty_item_label'          => 'CHOOSE_MENU',
                'is_method'                 => true,
                'find_method'               => [
                    'name' => 'getMenus',
                ],
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'name'    => 'language',
            'options' => [
                'label'                     => 'LANGUAGE',
                'disable_inarray_validator' => true,
                'object_manager'            => $this->objectManager,
                'target_class'              => 'SD\Admin\Entity\Language',
                'property'                  => 'name',
                'display_empty_item'        => true,
                'empty_item_label'          => 'CHOOSE_LANGUAGE',
                'is_method'                 => true,
                'find_method'               => [
                    'name' => 'getLanguages',
                ],
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

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Hidden',
            'name'    => 'id',
            'options' => [
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Content',
                'property'       => 'id',
            ],
            ]
        );

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Hidden',
            'name'    => 'titleLink',
            'options' => [
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Content',
                'property'       => 'titleLink',
            ],
            'attributes' => [
                'id' => 'titleLink',
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
                'name'     => 'title',
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
                            'max'      => 200,
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'text',
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
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'menuOrder',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
                'validators' => [
                    [
                        'name'    => 'Regex',
                        'options' => [
                            'pattern' => '/^[0-9]+$/',
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'language',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
                'validators' => [
                    [
                        'name'    => 'Regex',
                        'options' => [
                            'pattern' => '/^[0-9]+$/',
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'menu',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
                'validators' => [
                    [
                        'name'    => 'Regex',
                        'options' => [
                            'pattern' => '/^[0-9]+$/',
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'type',
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
                'name'     => 'date',
                'required' => false,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
            ],
            [
                'name'       => 'preview',
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
                'name'     => 'imageUpload',
                'required' => false,
            ],
            [
                'name'     => 'titleLink',
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                    ['name' => 'StringToLower'],
                ],
            ],
        ];
    }
}
