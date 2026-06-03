<?php
/**
 * This file is part of Rl_Wordpress for Magento.
 *
 * @license   Tous droits réservés
 * @author    Renaud Gouffé <renaud@colorz.fr>
 * @copyright Copyright (c) 2018 Colorz (http://www.colorz.fr)
 */

namespace Rl\Wordpress\Block;

use Clrz\Toolbox\Helper\Data as ClrzToolboxHelper;
use Clrz\Toolbox\Helper\Image as ClrzToolboxImageHelper;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Page extends Template
{
    /** @var \Magento\Framework\Registry $registry */
    protected $registry;
    /** @var \Clrz\Toolbox\Helper\Data $clrzToolboxHelper */
    protected $clrzToolboxHelper;
    /** @var \Clrz\Toolbox\Helper\Image $clrzToolboxImageHelper */
    protected $clrzToolboxImageHelper;
    /** @var string|null $stratesPrefix Strates prefix to manage multiple packages */
    protected $stratesPrefix;

    /**
     * CMS Page constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ClrzToolboxHelper $clrzToolboxHelper
     * @param ClrzToolboxImageHelper $clrzToolboxImageHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ClrzToolboxHelper $clrzToolboxHelper,
        ClrzToolboxImageHelper $clrzToolboxImageHelper,
        array $data = []
    )
    {
        $this->registry          = $registry;
        $this->clrzToolboxHelper = $clrzToolboxHelper;
        $this->clrzToolboxImageHelper = $clrzToolboxImageHelper;

        parent::__construct($context, $data);
    }

    /**
     * Get current Wordpress post
     *
     * @return \FishPig\WordPress\Model\Post
     */
    public function getPost()
    {
        return $this->registry->registry('wordpress_post');
    }

    /**
     * Transform a string into a slug.
     *
     * @param string $string String to slugify.
     *
     * @return string
     */
    public function slugify($string)
    {
        return $this->clrzToolboxHelper->formatUrlKey($string);
    }

    /**
     * Set design package to distinguish home vs mobile home
     *
     * @param string $package Package name
     */
    public function setDesignPackage($package)
    {
        $this->stratesPrefix = $package;
    }

    public function getToolboxImageHelper()
    {
        return $this->clrzToolboxImageHelper;
    }

    /**
     * Get strates
     *
     * @param string $metaValue
     * @return null|array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getStrates($metaValue)
    {
        $wordpressPost = $this->getPost();
        if ($wordpressPost) {
            $stratesPrefix = $this->stratesPrefix;
            $headBlock     = $this->getLayout()->getBlock('head');

            if ($headBlock) {
                $headBlock->setTitle($wordpressPost->getPostTitle());
            }

            if (!$stratesPrefix) {
                $strates = $wordpressPost->getMetaValue($metaValue);
            }
            else {
                $strates = $wordpressPost->getMetaValue($stratesPrefix . '_' . $metaValue);
            }

            if (!empty($strates)) {
                return $strates;
            }
        }

        return null;
    }
}
