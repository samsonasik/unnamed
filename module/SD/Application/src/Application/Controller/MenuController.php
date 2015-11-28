<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller;

use Doctrine\ORM\Query\Expr\Join;

/**
 * @method object getTable($tableName)
 * @method mixed initMetaTags(array $content = [])
 * @method mixed getParam($paramName = null)
 */
final class MenuController extends BaseController
{
    /**
     * Get the contents for the menu/submenu.
     *
     * @return \Zend\View\Model\ViewModel
     */
    protected function postAction()
    {
        $this->getView()->setTemplate('application/menu/post');

        /** @var \Doctrine\ORM\QueryBuilder $contents */
        $contents = $this->getTable('SD\Admin\\Model\\ContentTable')->queryBuilder();
        $contents->select('m.menulink, m.parent, m.keywords, m.description', 'c.menu, c.text, c.id, c.title, c.preview')
                    ->from('SD\Admin\Entity\Menu', 'm')
                    ->innerJoin(
                        'SD\Admin\Entity\Content',
                        'c',
                        Join::WITH,
                        'c.menu = m.id'
                    )
                    ->where('m.menulink = :menulink AND c.type = 0 AND c.language = :language')
                    ->setParameter(':menulink', (string) $this->getParam('post'))
                    ->setParameter(':language', (int) $this->language());

        $contents = $contents->getQuery()->getResult();
        if (!empty($contents)) {
            $this->initMetaTags($contents[0]);
            $this->getView()->setVariable('contents', $contents);
        }

        return $this->getView();
    }
}
