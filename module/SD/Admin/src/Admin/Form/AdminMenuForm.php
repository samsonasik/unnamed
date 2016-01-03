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

final class AdminMenuForm extends Form implements InputFilterProviderInterface
{
    /*
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct()
    {
        parent::__construct('admin-menu');
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
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'caption',
            'options' => [
                'label'          => 'CAPTION',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\AdminMenu',
                'property'       => 'caption',
            ],
            'attributes' => [
                'required'    => 'true',
                'size'        => '40',
                'class'       => 'admin-menu-caption',
                'placeholder' => 'CAPTION',
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
                'target_class'              => 'SD\Admin\Entity\AdminMenu',
                'property'                  => 'menuOrder',
                'display_empty_item'        => true,
                'empty_item_label'          => 'MENU_ORDER_TEXT',
                'value_options'             => $valueOptions,
                'label'                     => 'MENU_ORDER',
            ],
        ]);

        $this->add([
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'controller',
            'options' => [
                'label'          => 'CONTROLLER',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\AdminMenu',
                'property'       => 'controller',
            ],
            'attributes' => [
                'size'        => '40',
                'class'       => 'admin-menu-controller',
                'placeholder' => 'CONTROLLER',
            ],
        ]);

        $this->add([
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'class',
            'options' => [
                'label'          => 'CSS_CLASS',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\AdminMenu',
                'property'       => 'class',
            ],
            'attributes' => [
                'size'        => '40',
                'class'       => 'admin-menu-class',
                'placeholder' => 'CSS_CLASS',
            ],
        ]);

        $this->add([
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'action',
            'options' => [
                'label'          => 'ACTION',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\AdminMenu',
                'property'       => 'action',
            ],
            'attributes' => [
                'size'        => '40',
                'class'       => 'admin-menu-action',
                'placeholder' => 'ACTION',
            ],
        ]);

        $this->add([
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'description',
            'options' => [
                'label'          => 'DESCRIPTION',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\AdminMenu',
                'property'       => 'caption',
            ],
            'attributes' => [
                'size'        => '40',
                'class'       => 'admin-menu-description',
                'placeholder' => 'DESCRIPTION',
            ],
        ]);

        $this->add([
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'name'    => 'parent',
            'options' => [
                'label'                     => 'Parent admin menu',
                'disable_inarray_validator' => true,
                'object_manager'            => $this->objectManager,
                'target_class'              => 'SD\Admin\Entity\AdminMenu',
                'property'                  => 'caption',
                'display_empty_item'        => true,
                'empty_item_label'          => 'Select parent admin menu',
                'is_method'                 => true,
                'find_method'               => [
                    'name' => 'getParentMenus',
                ],
            ],
        ]);

        $this->add([
            'type'    => 'Zend\Form\Element\Csrf',
            'name'    => 's',
            'options' => [
                'csrf_options' => [
                    'timeout' => 1400,
                ],
            ],
        ]);

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
                'target_class'   => 'SD\Admin\Entity\AdminMenu',
                'property'       => 'id',
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
                'name'     => 'controller',
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
                            'max'      => 200,
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'action',
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
                            'max'      => 200,
                        ],
                    ],
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
        ];
    }
}
