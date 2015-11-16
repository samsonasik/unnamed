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

final class SettingsDiscussionForm extends Form implements InputFilterProviderInterface
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
        parent::__construct('settings-discussion');
    }

    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Checkbox',
            'name'       => 'allow_comments',
            'attributes' => [
                'class' => 'settings_allow_comments',
                'value' => $this->config['allow_comments'],
            ],
            'options' => [
                'label' => 'ALLOW_COMMENTS',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Checkbox',
            'name'       => 'comment_registration',
            'attributes' => [
                'class' => 'settings_site_comment_registration',
                'value' => $this->config['comment_registration'],
            ],
            'options' => [
                'label' => 'COMMENT_REGISTRATION',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Checkbox',
            'name'       => 'comments_notify',
            'attributes' => [
                'class' => 'settings_comments_notify',
                'value' => $this->config['comments_notify'],
            ],
            'options' => [
                'label' => 'COMMENT_NOTIFY',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Checkbox',
            'name'       => 'moderation_notify',
            'attributes' => [
                'class' => 'settings_moderation_notify',
                'value' => $this->config['moderation_notify'],
            ],
            'options' => [
                'label' => 'COMMENT_MODERATION',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Checkbox',
            'name'       => 'comment_moderation',
            'attributes' => [
                'class' => 'settings_comment_moderation',
                'value' => $this->config['comment_moderation'],
            ],
            'options' => [
                'label' => 'COMMENT_MANUAL_APPROVE',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Checkbox',
            'name'       => 'show_avatars',
            'attributes' => [
                'class' => 'settings_show_avatars',
                'value' => $this->config['show_avatars'],
            ],
            'options' => [
                'label' => 'SHOW_AVATARS',
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
                'name'     => 'allow_comments',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
            [
                'name'     => 'comment_registration',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
            [
                'name'     => 'comments_notify',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
            [
                'name'     => 'moderation_notify',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
            [
                'name'     => 'comment_moderation',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
            [
                'name'     => 'show_avatars',
                'required' => false,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
        ];
    }
}
