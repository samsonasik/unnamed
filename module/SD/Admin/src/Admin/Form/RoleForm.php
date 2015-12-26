<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
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

final class RoleForm extends Form implements InputFilterProviderInterface
{
    /*
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct()
    {
        parent::__construct('role');
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
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'name',
            'options' => [
                'label'          => 'NAME',
                'object_manager' => $this->objectManager,
                'target_class'   => 'SD\Admin\Entity\Role',
                'property'       => 'name',
            ],
            'attributes' => [
                'required'     => 'true',
                'size'         => '40',
                'class'        => 'role-name',
                'placeholder'  => 'NAME',
            ],
            ]
        );

        $valueOptions = [];
        for ($i = 1; $i < 10; $i++) {
            $valueOptions[$i] = $i;
        }
        $this->add([
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'name'    => 'level',
            'options' => [
                'object_manager'            => $this->objectManager,
                'disable_inarray_validator' => true,
                'label'                     => 'LEVEL',
                'target_class'              => 'SD\Admin\Entity\Role',
                'property'                  => 'level',
                'value_options'             => $valueOptions,
            ],
            'attributes' => [
                'required'     => 'true',
                'class'        => 'role-level',
            ],
            ]
        );

        $valueOptions = [
            0 => 'Resource',
            1 => 'Role',
            2 => 'Permissions',
        ];
        $this->add([
            'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
            'name'    => 'type',
            'options' => [
                'object_manager'            => $this->objectManager,
                'disable_inarray_validator' => true,
                'label'                     => 'TYPE',
                'target_class'              => 'SD\Admin\Entity\Role',
                'property'                  => 'type',
                'value_options'             => $valueOptions,
            ],
            'attributes' => [
                'required'     => 'true',
                'class'        => 'role-type',
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
                'target_class'   => 'SD\Admin\Entity\Role',
                'property'       => 'name',
            ],
            'attributes' => [
                'required'     => 'true',
                'size'         => '40',
                'class'        => 'role-name',
                'placeholder'  => 'NAME',
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
                'target_class'   => 'SD\Admin\Entity\Role',
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
                'name'     => 'type',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
                'validators' => [
                    [
                        'name'    => 'Regex',
                        'options' => [
                            'pattern' => '/^[0-2]+$/',
                        ],
                    ],
                ],
            ],
            [
                'name'     => 'level',
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
