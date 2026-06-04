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

namespace ElielWeb\RlWordpress\Block;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Page extends Template
{
    private ?string $stratesPrefix = null;

    public function __construct(
        Context $context,
        private readonly Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getPost(): ?\FishPig\WordPress\Model\Post
    {
        return $this->registry->registry('wordpress_post');
    }

    /**
     * Transforms a string into a URL-safe slug (PHP-native, no external dependency).
     */
    public function slugify(string $string): string
    {
        $string = mb_strtolower(trim($string), 'UTF-8');
        // Replace accented chars with ASCII equivalents
        $string = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $string) ?? $string;
        $string = (string)preg_replace('/[^a-z0-9\s-]/', '', $string);
        $string = (string)preg_replace('/[\s-]+/', '-', $string);
        return trim($string, '-');
    }

    public function setDesignPackage(string $package): static
    {
        $this->stratesPrefix = $package;
        return $this;
    }

    /**
     * Returns ACF flexible content strates for the current WP post.
     *
     * @return array<int, array<string, mixed>>|null
     */
public function getStrates(string $metaValue): ?array
{
    $post = $this->getPost();
    if (!$post) {
        return null;
    }

    $headBlock = $this->getLayout()->getBlock('head');
    if ($headBlock) {
        $headBlock->setTitle($post->getPostTitle());
    }

    $key     = $this->stratesPrefix ? $this->stratesPrefix . '_' . $metaValue : $metaValue;
    $strates = $post->getMetaValue($key);

    if (is_string($strates)) {
        $strates = @unserialize($strates);
    }

    return !empty($strates) && is_array($strates) ? $strates : null;
}

}
