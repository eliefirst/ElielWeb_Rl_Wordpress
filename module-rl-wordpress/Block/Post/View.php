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

namespace ElielWeb\RlWordpress\Block\Post;

use FishPig\WordPress\Block\Post\View as BaseView;
use FishPig\WordPress\Model\ResourceModel\Post\CollectionFactory;
use Magento\Framework\App\ObjectManager;

class View extends BaseView
{
    private mixed $relatedPosts = null;

    /**
     * Returns up to 3 posts sharing at least one category with the current post.
     *
     * Uses ObjectManager to avoid constructor signature conflicts across FishPig versions.
     */
    public function getRelatedPostCollection(): mixed
    {
        if ($this->relatedPosts !== null) {
            return $this->relatedPosts;
        }

        $factory = ObjectManager::getInstance()->get(CollectionFactory::class);
        $this->relatedPosts = $factory->create();

        $post = $this->getPost();
        if ($post) {
            $categoryIds = $post->getTermCollection('category')->getAllIds();
            if (!empty($categoryIds)) {
                $this->relatedPosts->addCategoryAndPostIdFilter(null, $categoryIds);
            }
        }

        $this->relatedPosts
            ->addIsViewableFilter()
            ->setCurPage(1)
            ->setPageSize(3);

        return $this->relatedPosts;
    }
}
