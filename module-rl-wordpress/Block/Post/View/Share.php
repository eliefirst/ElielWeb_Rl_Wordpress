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

namespace ElielWeb\RlWordpress\Block\Post\View;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Share extends Template
{
    public function __construct(
        Context $context,
        private readonly Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getPost(): ?\FishPig\WordPress\Model\Post
    {
        return $this->_getData('post')
            ?? $this->registry->registry('wordpress_post');
    }

    /**
     * Returns share links for the given post URL.
     *
     * @return array<string, array{label: string, url: string, icon: string}>
     */
    public function getShareLinks(string $postUrl): array
    {
        $encoded = urlencode($postUrl);
        return [
            'facebook' => [
                'label' => __('Share on Facebook')->render(),
                'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded,
                'icon'  => 'facebook',
            ],
            'x' => [
                'label' => __('Share on X')->render(),
                'url'   => 'https://x.com/intent/tweet?url=' . $encoded,
                'icon'  => 'x',
            ],
            'pinterest' => [
                'label' => __('Share on Pinterest')->render(),
                'url'   => 'https://pinterest.com/pin/create/button/?url=' . $encoded,
                'icon'  => 'pinterest',
            ],
        ];
    }
}
