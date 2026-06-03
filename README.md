# ElielWeb_RlWordpress

Module Magento 2 — Intégration des landing pages WordPress (FishPig) pour RedLine Paris.  
Migration complète depuis `Rl_Wordpress` (Colorz, 2018–2019) vers PHP 8.4 + Hyvä (Alpine.js / Tailwind CSS).

## Prérequis

- Magento 2.4.8-p3+
- PHP 8.4
- [FishPig WordPress Integration](https://github.com/bentideswell/magento2-wordpress-integration)
- `elielweb/module-fishpig` (ACF flexible content renderer)
- Hyvä Theme 1.4+

## Installation via Composer

Ajouter le dépôt VCS dans `composer.json` du projet :

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/eliefirst/ElielWeb_Rl_Wordpress"
        }
    ]
}
```

Puis installer :

```bash
composer require elielweb/module-rl-wordpress
php bin/magento module:enable ElielWeb_RlWordpress
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush
```

## Structure

```
Block/
  Page.php                  ← block principal, dispatch strates ACF
  Page/
    Menu.php                ← navigation catégories WP (filtre uncategorized)
    Strate.php              ← block enfant strates, getWpImageUrl() / getWpImageAlt()
  Post/
    View.php                ← étend FishPig\Block\Post\View, getRelatedPostCollection()
    View/
      Share.php             ← partage social natif (Facebook, X, Pinterest)

Model/
  Search.php                ← surcharge FishPig\Model\Search (traduit le titre)

etc/
  module.xml                ← séquence FishPig_WordPress → ElielWeb_FishPig
  frontend/di.xml           ← preference Search → ElielWeb\RlWordpress\Model\Search

view/frontend/
  page_layout/
    wordpress_page_cmsmagento.xml
    wordpress_page_landingredline.xml
    wordpress_post_view_page_templates_home_magento.xml
  templates/
    page/
      cms.phtml             ← CMS page avec sidebar scroll-spy Alpine.js
      landing.phtml         ← dispatcher strates ACF flexible content
      homepage.phtml        ← dispatcher strates home_strates
      nav_terms.phtml       ← nav catégories WP Tailwind
      strates/
        hero.phtml          ← hero plein écran image/vidéo
        edito.phtml         ← 2 colonnes texte + images + CTA
        image_video_full.phtml
        push.phtml          ← grille cards
        quote.phtml         ← citation avec/sans fond image
        text.phtml          ← bloc texte centré
        seo.phtml           ← 2 blocs SEO footer
    post/
      share.phtml           ← partage social
```

## Système de strates ACF

Les landing pages sont construites avec des champs ACF "Flexible Content" (`redline_landing`).  
Chaque strate a un `acf_fc_layout` qui correspond au nom du child block dans le layout XML :

| `acf_fc_layout`    | Template                    | Description                        |
|--------------------|-----------------------------|------------------------------------|
| `hero`             | `strates/hero.phtml`        | Hero plein écran image/vidéo       |
| `edito`            | `strates/edito.phtml`       | 2 colonnes texte + images          |
| `image_video_full` | `strates/image_video_full.phtml` | Image ou vidéo plein largeur  |
| `push`             | `strates/push.phtml`        | Grille de cards avec overlay       |
| `quote`            | `strates/quote.phtml`       | Citation avec/sans fond image      |
| `text`             | `strates/text.phtml`        | Bloc texte centré                  |
| `seo`              | `strates/seo.phtml`         | 2 blocs SEO footer                 |

## Images ACF

Les champs image ACF retournent un tableau PHP :

```php
[
    'url'    => 'https://...',
    'title'  => '...',
    'alt'    => '...',
    'width'  => 1920,
    'height' => 1080,
]
```

Utiliser toujours dans les templates :

```php
$block->getWpImageUrl($image)  // string URL ou ''
$block->getWpImageAlt($image)  // string alt ou fallback
```

## Migration depuis Rl_Wordpress (Colorz)

| Avant                                    | Après                                        |
|------------------------------------------|----------------------------------------------|
| `Rl\Wordpress` / `Rl_Wordpress`          | `ElielWeb\RlWordpress` / `ElielWeb_RlWordpress` |
| `Clrz\Toolbox\Helper\Image`             | `$block->getWpImageUrl()` natif              |
| `Clrz\Toolbox\Helper\Data::formatUrlKey` | `Block\Page::slugify()` PHP natif            |
| `Clrz\Community\Block\Links`            | `Block\Post\View\Share` natif                |
| jQuery + gumshoe (scroll-spy)           | Alpine.js `x-intersect` natif                |
| Bootstrap grid (`col-*`, `row`)         | Tailwind CSS                                 |
| `lazyload` JS                           | `loading="lazy"` HTML natif                  |
| PHP 5.5–7.0                             | PHP ^8.4, `declare(strict_types=1)`          |

## Palette RedLine

- `#B4121C` — rouge principal
- `#FAF7F2` — blanc cassé
- `#B48A4D` — or

## Contraintes Hyvä

- Pas de jQuery, pas de RequireJS, pas de Knockout
- Alpine.js uniquement pour les interactions JS
- Tailwind CSS uniquement (pas de Bootstrap)
- `loading="lazy"` HTML natif
