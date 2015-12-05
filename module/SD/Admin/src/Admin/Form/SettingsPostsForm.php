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

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

final class SettingsPostsForm extends Form implements InputFilterProviderInterface
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

        parent::__construct('settings-posts');
    }

    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Number',
            'name'       => 'content',
            'attributes' => [
                'required'    => true,
                'min'         => 1,
                'step'        => 1,
                'size'        => 40,
                'class'       => 'settings_content',
                'placeholder' => 'CONTENT_POSTS_PER_PAGE',
                'value'       => $this->config['content'],
            ],
            'options' => [
                'label' => 'CONTENT_POSTS_PER_PAGE',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Number',
            'name'       => 'menu',
            'attributes' => [
                'required'    => true,
                'min'         => 1,
                'step'        => 1,
                'size'        => 40,
                'class'       => 'settings_menu',
                'placeholder' => 'MENU_POSTS_PER_PAGE',
                'value'       => $this->config['menu'],
            ],
            'options' => [
                'label' => 'MENU_POSTS_PER_PAGE',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Number',
            'name'       => 'adminmenu',
            'attributes' => [
                'required'    => true,
                'min'         => 1,
                'step'        => 1,
                'size'        => 40,
                'class'       => 'settings_admin-menu',
                'placeholder' => 'ADMINMENU_POSTS_PER_PAGE',
                'value'       => $this->config['adminmenu'],
            ],
            'options' => [
                'label' => 'ADMINMENU_POSTS_PER_PAGE',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Number',
            'name'       => 'administrator',
            'attributes' => [
                'required'    => true,
                'min'         => 1,
                'step'        => 1,
                'size'        => 40,
                'class'       => 'settings_administrator',
                'placeholder' => 'ADMIN_POSTS_PER_PAGE',
                'value'       => $this->config['administrator'],
            ],
            'options' => [
                'label' => 'ADMIN_POSTS_PER_PAGE',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Number',
            'name'       => 'language',
            'attributes' => [
                'required'    => true,
                'min'         => 1,
                'step'        => 1,
                'size'        => 40,
                'class'       => 'settings_language',
                'placeholder' => 'LANGUAGE_POSTS_PER_PAGE',
                'value'       => $this->config['language'],
            ],
            'options' => [
                'label' => 'LANGUAGE_POSTS_PER_PAGE',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Number',
            'name'       => 'user',
            'attributes' => [
                'required'    => true,
                'min'         => 1,
                'step'        => 1,
                'size'        => 40,
                'class'       => 'settings_user',
                'placeholder' => 'USER_POSTS_PER_PAGE',
                'value'       => $this->config['user'],
            ],
            'options' => [
                'label' => 'USER_POSTS_PER_PAGE',
            ],
            ]
        );

        $this->add(
            [
            'type'       => 'Zend\Form\Element\Number',
            'name'       => 'news',
            'attributes' => [
                'required'    => true,
                'min'         => 1,
                'step'        => 1,
                'size'        => 40,
                'class'       => 'settings_news',
                'placeholder' => 'NEWS_POSTS_PER_PAGE',
                'value'       => $this->config['news'],
            ],
            'options' => [
                'label' => 'NEWS_POSTS_PER_PAGE',
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
                'name'     => 'content',
                'required' => true,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
            [
                'name'     => 'menu',
                'required' => true,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
            [
                'name'     => 'adminmenu',
                'required' => true,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
            [
                'name'     => 'administrator',
                'required' => true,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
            [
                'name'     => 'language',
                'required' => true,
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
            ],
            [
                'name'     => 'news',
                'required' => true,
                'filters'  => [
                    ['name' => 'Int'],
                ],
            ],
        ];
    }
}
