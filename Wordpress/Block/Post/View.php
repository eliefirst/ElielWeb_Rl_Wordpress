<?php
/**
 * This file is part of Rl_Wordpress for Magento.
 *
 * @license   Tous droits réservés
 * @author    Renaud Gouffé (renaud@colorz.fr)
 * @category  Rl
 * @package   Rl_Wordpress
 * @copyright Copyright (c) 2019 Colorz (http://www.colorz.fr)
 */

namespace Rl\Wordpress\Block\Post;

/* Constructor */
use Magento\Framework\View\Element\Template\Context;
use FishPig\WordPress\Model\Context as WPContext;

class View extends \FishPig\WordPress\Block\Post\View
{
    protected $_relatedPostCollection;

    /*
  * Constructor
  *
  * @param Context $context
  * @param App
  * @param array $data
  */
    /**
     * View constructor.
     * @param Context $context
     * @param WPContext $wpContext
     * @param array $data
     */
    public function __construct(
        Context $context,
        WPContext $wpContext,
        array $data = []
    ) {
        parent::__construct($context, $wpContext, $data);
    }

    public function getRelatedPostCollection()
    {
        if ($this->_relatedPostCollection === null) {
            $this->_relatedPostCollection = $this->factory->create('FishPig\WordPress\Model\ResourceModel\Post\Collection');
            $categoryIds = $this->getPost()->getTermCollection('category')->getAllIds();
            if (!empty($categoryIds)) {
                // Add category filter
                $this->_relatedPostCollection->addCategoryAndPostIdFilter(null, $categoryIds);
            }
            // 3 posts max.
            $this->_relatedPostCollection
                ->addIsViewableFilter()
                ->setCurPage(1)
                ->setPageSize(3);
        }

        return $this->_relatedPostCollection;
    }
}
