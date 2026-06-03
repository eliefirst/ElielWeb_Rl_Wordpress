<?php
/**
 * This file is part of Rl_Wordpress for Magento.
 *
 * @license   Tous droits réservés
 * @author    Renaud Gouffé <renaud@colorz.fr>
 * @copyright Copyright (c) 2018 Colorz (http://www.colorz.fr)
 */

namespace Rl\Wordpress\Block\Page;

use Clrz\Toolbox\Helper\Data as ClrzToolboxHelper;
use Clrz\Toolbox\Helper\Image as ClrzToolboxImageHelper;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Strate extends Template
{
    /** @var \Clrz\Toolbox\Helper\Data $clrzToolboxHelper */
    protected $clrzToolboxHelper;
    /** @var \Clrz\Toolbox\Helper\Image $clrzToolboxImageHelper */
    protected $clrzToolboxImageHelper;

    /**
     * CMS Page constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param ClrzToolboxHelper $clrzToolboxHelper
     * @param ClrzToolboxImageHelper $clrzToolboxImageHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        ClrzToolboxHelper $clrzToolboxHelper,
        ClrzToolboxImageHelper $clrzToolboxImageHelper,
        array $data = []
    )
    {
        $this->clrzToolboxHelper = $clrzToolboxHelper;
        $this->clrzToolboxImageHelper = $clrzToolboxImageHelper;

        parent::__construct($context, $data);
    }

    public function getToolboxHelper()
    {
        return $this->clrzToolboxHelper;
    }

    public function getToolboxImageHelper()
    {
        return $this->clrzToolboxImageHelper;
    }

    public function cleanUrl($url)
    {
        $parsedUrl = parse_url($url);
        if (!empty($parsedUrl['path'])) {
            return $parsedUrl['path'];
        }

        return $url;
    }
}
