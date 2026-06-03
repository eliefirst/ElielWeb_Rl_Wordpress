<?php
/**
 * ElielWeb_RlWordpress
 *
 * @category    ElielWeb
 * @package     ElielWeb_RlWordpress
 * @author      Elie <elie@redline.paris>
 * @copyright   Copyright (c) 2026 RedLine Paris (https://redline-boutique.com)
 * @license     Proprietary - All rights reserved
 */
declare(strict_types=1);

namespace ElielWeb\RlWordpress\Block\Page;

use FishPig\WordPress\Block\Sidebar\Widget\Categories as WidgetCategories;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Menu extends Template
{
    /** WP default "uncategorized" slugs across locales */
    private const EXCLUDE_SLUGS = [
        'uncategorized',
        'sin-categorizar',
        '%e3%82%ab%e3%83%86%e3%82%b4%e3%83%aa%e3%83%bc%e3%81%aa%e3%81%97',
        '%d0%b1%d0%b5%d0%b7-%d0%ba%d0%b0%d1%82%d0%b5%d0%b3%d0%be%d1%80%d0%b8%d0%b8',
    ];

    public function __construct(
        Context $context,
        private readonly WidgetCategories $widget,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return array<int, \FishPig\WordPress\Model\Term\Taxonomy>
     */
    public function getItems(): array
    {
        $categories = $this->widget->getCategories();
        if (!$categories) {
            return [];
        }

        return array_values(array_filter(
            (array)$categories,
            fn($cat) => !in_array($cat->getSlug(), self::EXCLUDE_SLUGS, true)
        ));
    }
}
