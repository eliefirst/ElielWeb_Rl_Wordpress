<?php namespace Rl\Wordpress\Block\Page; use Magento\Framework\View\Element\Template; use 
Magento\Framework\View\Element\Template\Context; use FishPig\WordPress\Block\Sidebar\Widget\Categories as WidgetCategories; class Menu 
extends Template {
    protected $widget;
    public function __construct(
        Context $context,
        WidgetCategories $widget,
        array $data = []
    ) {
        $this->widget = $widget;
        parent::__construct($context, $data);
    }
    public function getItems()
    {
        $categories = $this->widget->getCategories();
        
        if (!$categories) {
            return $categories;
        }
        // Exclude Uncategorized categories
        $excludeSlugs = [
            'uncategorized',
            'sin-categorizar',
            '%e3%82%ab%e3%83%86%e3%82%b4%e3%83%aa%e3%83%bc%e3%81%aa%e3%81%97',
            '%d0%b1%d0%b5%d0%b7-%d0%ba%d0%b0%d1%82%d0%b5%d0%b3%d0%be%d1%80%d0%b8%d0%b8'
        ];
        $filtered = [];
        foreach ($categories as $category) {
            if (!in_array($category->getSlug(), $excludeSlugs)) {
                $filtered[] = $category;
            }
        }
        return $filtered;
    }
}
