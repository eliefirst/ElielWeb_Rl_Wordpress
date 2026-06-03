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

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'ElielWeb_RlWordpress',
    __DIR__
);
