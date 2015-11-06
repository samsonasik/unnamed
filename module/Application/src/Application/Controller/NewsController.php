<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace Application\Controller;

use Zend\Escaper\Escaper;

/**
 * @method object getTable($tableName)
 * @method string translate($message = '')
 * @method int setErrorCode(int $code)
 * @method void initMetaTags(array $content)
 * @method mixed getParam($paramName = null, $default = null)
 * @method string|null systemSettings($option = 'general', $value = 'site_name')
 */
final class NewsController extends BaseController
{
    /**
     * Get the contents for all the news.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('application/news/index');

        $query = $this->getTable('Admin\\Model\\ContentTable');
        $news = $query->queryBuilder()->select(['c'])
                ->from('Admin\Entity\Content', 'c')
                ->where('c.type = 1 AND c.menu = 0 AND c.language = :language')
                ->setParameter(':language', (int) $this->language())
                ->orderBy('c.date', 'DESC');

        $paginator = $query->preparePagination($news, false);
        $paginator->setCurrentPageNumber((int) $this->getParam('page', 1));
        $paginator->setItemCountPerPage($this->systemSettings('posts', 'news'));
        $this->getView()->setVariable('news', $paginator);

        return $this->getView();
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function postAction()
    {
        $this->getView()->setTemplate('application/news/post');

        $escaper = new Escaper('utf-8');
        $post = (string) $escaper->escapeUrl($this->getParam('post'));
        $query = $this->getTable('Admin\\Model\\ContentTable');
        $new = $query->queryBuilder()->select(['c.title, c.text, c.date, c.preview'])
                ->from('Admin\Entity\Content', 'c')
                ->where('c.type = 1 AND c.menu = 0 AND c.language = :language AND c.titleLink = :titleLink')
                ->setParameter(':language', (int) $this->language())
                ->setParameter(':titleLink', (string) $post)
                ->orderBy('c.date', 'DESC')->getQuery()->getResult();

        if ($new) {
            $this->getView()->setVariable('new', $new[0]);
            $this->initMetaTags($new[0]);

            return $this->getView();
        }

        return $this->setErrorCode(404);
    }
}
