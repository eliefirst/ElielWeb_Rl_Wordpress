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

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Strate extends Template
{
    /** @var array<string, mixed>|null */
    private ?array $strate = null;

    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @param array<string, mixed> $strate
     */
    public function setStrate(array $strate): static
    {
        $this->strate = $strate;
        return $this;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getStrate(): ?array
    {
        return $this->strate;
    }

    /**
     * Strips query/fragment from a URL, returning only the path.
     */
    public function cleanUrl(string $url): string
    {
        $parsed = parse_url($url);
        return !empty($parsed['path']) ? (string)$parsed['path'] : $url;
    }

    /**
     * Resolves the URL from an ACF image field (array with 'url' key) or returns empty string.
     *
     * @param array<string, mixed>|null $image
     */
    public function getWpImageUrl(array|null $image): string
    {
        if (empty($image) || !is_array($image)) {
            return '';
        }
        return (string)($image['url'] ?? '');
    }

    /**
     * Resolves the alt text from an ACF image field.
     *
     * @param array<string, mixed>|null $image
     */
    public function getWpImageAlt(array|null $image, string $fallback = ''): string
    {
        if (!is_array($image)) {
            return $fallback;
        }
        return (string)($image['alt'] ?? $image['title'] ?? $fallback);
    }
}
