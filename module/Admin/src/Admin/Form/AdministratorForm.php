<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace Admin\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

final class AdministratorForm extends Form implements InputFilterProviderInterface
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

        $this->add(
            [
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'user',
            'options' => [
                'label'          => 'Caption',
                'object_manager' => $this->objectManager,
                'target_class'   => 'Admin\Entity\Administrator',
                'property'       => 'caption',
            ],
            'attributes' => [
                'required'     => 'true',
                'size'         => '40',
                'class'        => 'administrator-user ajax-search',
                'placeholder'  => 'User ID',
                'autocomplete' => 'off',
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
                'value' => 'Save',
            ],
            ]
        );

        $this->add(
            [
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
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
                'name'     => 'user',
                'required' => true,
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
