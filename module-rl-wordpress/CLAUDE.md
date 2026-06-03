# ElielWeb_RlWordpress — Brief Claude Code

## Contexte

Module Magento 2.4.8-p3 / PHP 8.4 / Hyvä Theme  
Migration complète depuis `Rl_Wordpress` (Colorz, 2018–2019)  
Boutique : RedLine Paris — joaillerie de luxe, bracelets personnalisables  
Serveur : Production3 (`/data/www/magento2-3/vendor/elielweb/module-rl-wordpress`)

## Rôle du module

Intégration des landing pages WordPress via FishPig dans Magento.  
Affiche des pages WP avec du contenu ACF flexible (strates).  
Se substitue totalement à l'ancien `Rl_Wordpress` de Colorz.

## Architecture

```
Block/
  Page.php                  ← block principal, lit wordpress_post via Registry, dispatch strates ACF
  Page/
    Menu.php                ← navigation catégories WP (filtre uncategorized)
    Strate.php              ← block enfant pour les strates landing, getWpImageUrl()/getWpImageAlt()
  Post/
    View.php                ← étend FishPig\Block\Post\View, ajoute getRelatedPostCollection()
    View/
      Share.php             ← partage social natif (Facebook, X, Pinterest), sans dépendance Clrz

Model/
  Search.php                ← surcharge FishPig\Model\Search pour traduire le titre

etc/
  module.xml                ← séquence FishPig_WordPress → ElielWeb_FishPig
  frontend/di.xml           ← preference Search → ElielWeb\RlWordpress\Model\Search

view/frontend/
  page_layout/
    wordpress_page_cmsmagento.xml           ← page CMS WP (ACF cms_page)
    wordpress_page_landingredline.xml       ← landing ACF (acf_fc_layout dispatcher)
    wordpress_post_view_page_templates_home_magento.xml  ← homepage WP
  templates/
    page/
      cms.phtml             ← CMS page avec sidebar scroll-spy Alpine.js
      landing.phtml         ← dispatcher strates → child blocks
      homepage.phtml        ← dispatcher strates home_strates
      nav_terms.phtml       ← nav catégories WP Tailwind
      strates/
        hero.phtml          ← hero plein écran image/vidéo + Alpine.js canplay
        edito.phtml         ← 2 colonnes texte + images + CTA
        image_video_full.phtml  ← image ou vidéo plein largeur + Alpine.js player
        push.phtml          ← grille cards image + overlay
        quote.phtml         ← citation italique avec/sans fond image
        text.phtml          ← bloc texte centré
        seo.phtml           ← 2 blocs SEO footer
    post/
      share.phtml           ← partage social (Facebook, X, Pinterest)
```

## Changements vs Rl_Wordpress original

| Avant (Colorz)                          | Après (ElielWeb)                              |
|------------------------------------------|-----------------------------------------------|
| `Rl\Wordpress`                           | `ElielWeb\RlWordpress`                        |
| `Rl_Wordpress`                           | `ElielWeb_RlWordpress`                        |
| `Clrz\Toolbox\Helper\Image`             | `$block->getWpImageUrl($image)` natif         |
| `Clrz\Toolbox\Helper\Data::formatUrlKey` | `Block\Page::slugify()` PHP natif             |
| `Clrz\Community\Block\Links` (Share)    | `Block\Post\View\Share` natif + `getShareLinks()` |
| `Clrz\Homepage\Helper\Data`             | Supprimé — URLs ACF utilisées directement     |
| jQuery `require(['gumshoe'])`           | Alpine.js `x-intersect` + `IntersectionObserver` |
| Bootstrap grid (`container-fluid`, `row`, `col-*`) | Tailwind CSS                      |
| Classes `lazyload`, `js-image-cover`    | `loading="lazy"` natif HTML                   |
| `setup_version` dans module.xml         | Supprimé (déprécié Magento 2.4)              |
| PHP 5.5/5.6/7.0                         | PHP ^8.4, `declare(strict_types=1)` partout  |
| `Registry` + `ClrzToolboxImageHelper`  | Constructor promotion + `readonly`            |
| `$this->factory->create()` FishPig     | `ObjectManager::get(CollectionFactory::class)` (compatibilité FishPig v3) |

## Dépendances modules

- `FishPig_WordPress` (bentideswell/magento2-wordpress-integration)
- `ElielWeb_FishPig` (elielweb/module-fishpig — ACF landing renderer)

## Images ACF

Les champs image ACF retournent un tableau :
```php
['url' => 'https://...', 'title' => '...', 'alt' => '...', 'width' => 1920, 'height' => 1080]
```
Utiliser toujours `$block->getWpImageUrl($image)` et `$block->getWpImageAlt($image)` dans les strates.

## Commandes utiles (Production3)

```bash
cd /data/www/magento2-3

# Installation via Composer (VCS repository)
composer require elielweb/module-rl-wordpress

# Activation après install
php bin/magento module:enable ElielWeb_RlWordpress
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush

# En cas de modification PHP uniquement
rm -rf generated/code/ElielWeb/RlWordpress/
php bin/magento setup:di:compile
php bin/magento cache:flush
```

## Palette RedLine

- `#B4121C` rouge principal
- `#FAF7F2` blanc cassé
- `#B48A4D` or

## Contraintes Hyvä

- Pas de jQuery, pas de RequireJS/Knockout
- Alpine.js pour toute interaction JS (`x-data`, `x-intersect`, `@event`)
- Tailwind CSS uniquement pour les styles (pas de classes Bootstrap)
- `loading="lazy"` natif HTML au lieu de `lazyload` + JS
