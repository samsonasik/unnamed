<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller;

/**
 * @method object getTable($tableName)
 * @method mixed getParam($paramName = null)
 */
final class IndexController extends BaseController
{
    /**
     * Main websites view.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('application/index/index');

        return $this->getView();
    }

    /**
     * Select new language.
     *
     * This will reload the translations every time the method is being called.
     */
    protected function languageAction()
    {
        /** @var \SD\Admin\Entity\Language $language */
        $language = $this->getTable('SD\Admin\\Model\\LanguageTable')->getLanguage((int) $this->getParam('id'));

        $this->getTranslation()->offsetSet('language', $language->getId());
        $this->getTranslation()->offsetSet('languageName', $language->getName());

        return $this->redirect()->toUrl('/');
    }
}
