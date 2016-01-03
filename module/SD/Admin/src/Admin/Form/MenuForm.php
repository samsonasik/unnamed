<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

final class MenuForm extends Form implements InputFilterProviderInterface
{
    /*
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct()
    {
        parent::__construct('menu');
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

        $this->add([
            'type'       => 'Zend\Form\Element\Text',
            'name'       => 'caption',
            'attributes' => [
                'required'    => 'true',
                'size'        => '40',
                'id'          => 'seo-caption',
                'placeholder' => 'CAPTION',
            ],
            'options' => [
                'label'          => 'CAPTION',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Menu',
                'property'       => 'caption',
            ],
        ]);

        $valueOptions = [];
        for ($i = 1; $i < 150; $i++) {
            $valueOptions[$i] = $i;
        }
        $this->add([
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'name'    => 'menuOrder',
            'options' => [
                'object_manager'            => $this->objectManager,
                'disable_inarray_validator' => true,
                'target_class'              => 'SD\Admin\Entity\Menu',
                'property'                  => 'menuOrder',
                'display_empty_item'        => true,
                'empty_item_label'          => 'MENU_ORDER_TEXT',
                'value_options'             => $valueOptions,
                'label'                     => 'MENU_ORDER',
            ],
        ]);

        $this->add([
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'keywords',
            'options' => [
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Menu',
                'property'       => 'keywords',
                'label'          => 'KEYWORDS',
            ],
            'attributes' => [
                'size'        => '40',
                'placeholder' => 'KEYWORDS_LIMIT',
            ],
        ]);

        $this->add([
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'description',
            'options' => [
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Menu',
                'property'       => 'description',
                'label'          => 'DESCRIPTION',
            ],
            'attributes' => [
                'size'        => '40',
                'placeholder' => 'DESCRIPTION',
            ],
        ]);

        $this->add([
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'language',
            'options' => [
                'label'          => 'LANGUAGE',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Menu',
                'property'       => 'language',
            ],
            'attributes' => [
                'size'        => '40',
                'placeholder' => 'LANGUAGE',
            ],
        ]);

        $this->add([
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'name'    => 'parent',
            'options' => [
                'label'                     => 'PARENT',
                'disable_inarray_validator' => true,
                'object_manager'            => $this->objectManager,
                'target_class'              => 'SD\Admin\Entity\Menu',
                'property'                  => 'caption',
                'display_empty_item'        => true,
                'empty_item_label'          => 'PARENT_TEXT',
                'is_method'                 => true,
                'find_method'               => [
                    'name' => 'getMenus',
                ],
            ],
        ]);

        $valueOptions = [];
        $valueOptions[0] = 'Main menu';
        $valueOptions[1] = 'Left menu';
        $valueOptions[2] = 'Right menu';
        $valueOptions[3] = 'Footer menu';
        $this->add([
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'name'    => 'menutype',
            'options' => [
                'object_manager'            => $this->objectManager,
                'disable_inarray_validator' => true,
                'target_class'              => 'SD\Admin\Entity\Menu',
                'property'                  => 'menutype',
                'display_empty_item'        => true,
                'empty_item_label'          => 'MENU_TYPE_TEXT',
                'value_options'             => $valueOptions,
                'label'                     => 'MENU_TYPE',
            ],
        ]);

        $this->add([
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'class',
            'options' => [
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Menu',
                'property'       => 'class',
                'label'          => 'CSS_CLASS',
            ],
            'attributes' => [
                'size'        => '40',
                'class'       => 'admin-menu-class',
                'placeholder' => 'CSS_CLASS',
            ],
        ]);

        $valueOptions = [];
        // 0 index missed intentionally
        $valueOptions[1] = 'Column one';
        $valueOptions[2] = 'Column two';
        $valueOptions[3] = 'Column three';
        $valueOptions[4] = 'Column four';
        $this->add([
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'name'    => 'footercolumn',
            'options' => [
                'object_manager'     => $this->objectManager,
                'target_class'       => 'SD\Admin\Entity\Menu',
                'property'           => 'footercolumn',
                'display_empty_item' => true,
                'empty_item_label'   => 'FOOTER_COLUMN_TEXT',
                'value_options'      => $valueOptions,
                'label'              => 'FOOTER_COLUMN',
            ],
        ]);

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

        $this->add([
            'name'       => 'submit',
            'attributes' => [
                'type'  => 'submit',
                'id'    => 'submitbutton',
                'value' => 'SAVE',
            ],
        ]);

        $this->add([
            'type'    => 'Zend\Form\Element\Hidden',
            'name'    => 'id',
            'options' => [
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Menu',
                'property'       => 'id',
            ],
        ]);

        $this->add([
            'type'       => 'Zend\Form\Element\Hidden',
            'name'       => 'menulink',
            'attributes' => [
                'id' => 'menulink',
            ],
            'options' => [
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Menu',
                'property'       => 'menulink',
            ],
        ]);
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
                'name'     => 'caption',
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
                'name'     => 'parent',
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
                'name'     => 'keywords',
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
                'name'     => 'description',
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
                'name'     => 'menutype',
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
                'name'     => 'footercolumn',
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
                'name'     => 'menulink',
                'required' => false,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                    ['name' => 'StringToLower'],
                ],
            ],
            [
                'name'     => 'class',
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
                            'max'      => 50,
                        ],
                    ],
                ],
            ],
        ];
    }
}
