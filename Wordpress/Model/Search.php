<?php
/**
 * This file is part of Rl_Wordpress for Magento.
 *
 * @license   Tous droits réservés
 * @author    Renaud Gouffé (renaud@colorz.fr)
 * @category  Rl
 * @package   Rl_Wordpress
 * @copyright Copyright (c) 2019 Colorz (http://www.colorz.fr)
 */

namespace Rl\Wordpress\Model;


class Search extends \FishPig\WordPress\Model\Search
{
    /*
     * Get the name of the search
     *
     * @return  string
     */
    public function getName()
    {
        return __('Search results for ') . $this->getSearchTerm();
    }
}
