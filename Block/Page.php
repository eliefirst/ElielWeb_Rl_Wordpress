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

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Page extends Template
{
    private ?string $stratesPrefix = null;

    public function __construct(
        Context $context,
        private readonly Registry $registry,
        private readonly ResourceConnection $resourceConnection,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getPost(): ?\FishPig\WordPress\Model\Post
    {
        return $this->registry->registry('wordpress_post');
    }

    public function slugify(string $string): string
    {
        $string = mb_strtolower(trim($string), 'UTF-8');
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

        $key = $this->stratesPrefix ? $this->stratesPrefix . '_' . $metaValue : $metaValue;

        $rawValue = $post->getMetaValue($key);
        if (empty($rawValue)) {
            return null;
        }

        $layouts = @unserialize($rawValue);
        if (!is_array($layouts) || empty($layouts)) {
            return null;
        }

        $postId     = (int)$post->getId();
        $connection = $this->resourceConnection->getConnection();
        $strates    = [];

        foreach ($layouts as $index => $layoutName) {
            $prefix = $key . '_' . $index . '_';

            $rows = $connection->fetchAll(
                $connection->select()
                    ->from('wp_postmeta', ['meta_key', 'meta_value'])
                    ->where('post_id = ?', $postId)
                    ->where('meta_key LIKE ?', $prefix . '%')
            );

            $strate = ['acf_fc_layout' => (string)$layoutName];

            foreach ($rows as $row) {
                $fieldName = substr((string)$row['meta_key'], strlen($prefix));

                if ($fieldName === '' || $fieldName === 'acf_fc_layout') {
                    continue;
                }

                $value = $row['meta_value'];

                if (is_string($value) && str_starts_with($value, 'a:')) {
                    $decoded = @unserialize($value);
                    if (is_array($decoded)) {
                        $value = $decoded;
                    }
                }

                $strate[$fieldName] = $value;
            }

            $strates[] = $strate;
        }

        return !empty($strates) ? $strates : null;
    }
}
