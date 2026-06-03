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

namespace Rl\Wordpress\Block\Post\View;

use Clrz\Community\Helper\Data as ClrzCommunityHelper;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;

class Share extends \Clrz\Community\Block\Links
{
    /** @var \Magento\Framework\Registry $registry */
    protected $registry;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * SocialLinks constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Clrz\Community\Helper\Data $communityHelper
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        ClrzCommunityHelper $communityHelper,
        Registry $registry,
        UrlInterface $urlBuilder,
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->registry   = $registry;

        parent::__construct($context, $communityHelper, $data);
    }

    /**
     * Retrieve the current post object
     *
     * @return null|\FishPig\WordPress\Model\Post
     */
    public function getPost()
    {
        return $this->_getData('post') ? $this->_getData('post') : $this->registry->registry('wordpress_post');
    }
}
