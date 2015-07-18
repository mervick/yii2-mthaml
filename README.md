Yii2 MtHaml
===========
[![Analytics](https://ga-beacon.appspot.com/UA-65295275-1/yii2-mthaml)](https://github.com/igrigorik/ga-beacon)

The MtHaml integration for the Yii2 framework.  
Extension provides a `ViewRenders` that would allow you to use Haml/Twig view template engines, using [Multi target HAML (MtHaml)](https://github.com/arnaud-lb/MtHaml) library.


## Requirements

* YII 2.0
* PHP 5.4+
* Composer


## Installation with Composer

Installation is recommended to be done via [composer](https://getcomposer.org) by running:
```bash
composer require mervick/yii2-mthaml "*"
```

Also for using twig:
```bash
composer require twig/twig "~1.11"
```


## Usage

Add this to your `config/main.php` file:
```php
return [
    //....
    'components' => [
        'view' => [
            'renderers' => [
                'haml' => [
                    'class' => 'mervick\mthaml\HamlViewRenderer',
                ],
                'twig' => [
                    'class' => 'mervick\mthaml\TwigViewRenderer',
                ],
            ],
        ],
    ],
];
```
  
Rendering in Controllers:
```php
class SiteController extends Controller
{
    //....
    public function actionIndex()
    {
        return $this->render('index.haml', $params);
        // or if your want to use twig
        // return $this->render('index.twig', $params);
    }
    //....
}
```


## MtHaml Options

This is default options:
```php
    //....
    'renderers' => [
        'haml' => [
            'class' => 'mervick\mthaml\HamlViewRenderer',
            'cachePath' => '@runtime/Haml/cache',
            'debug' => false,
            'options' => [
                'format' => 'html5',
                // MtHaml escapes everything by default
                'enable_escaper' => true,
                'escape_html' => true,
                'escape_attrs' => true,
                'cdata' => true,
                'autoclose' => array('meta', 'img', 'link', 'br', 'hr', 'input', 'area', 'param', 'col', 'base'),
                'charset' => 'UTF-8',
                'enable_dynamic_attrs' => true,
            ],
        ],
        'twig' => [
            'class' => 'mervick\mthaml\TwigViewRenderer',
            'cachePath' => '@runtime/Twig/cache',
            'debug' => false,
            'options' => [
                // Same as for haml, except "enable_escaper"
                // Twig extension already supports auto escaping, so it turned off for MtHaml
                'enable_escaper' => false,
            ],
        ],
    //....
```


## Filters

Filters take plain text input (with support for `#{...}` interpolations) and transform it, or wrap it. [Learn more](https://github.com/arnaud-lb/MtHaml#filters)

The following filters are available by default:

*   `css`: wraps with style tags
*   `cdata`: wraps with CDATA markup
*   `escaped`: html escapes
*   `javascript`: wraps with script tags
*   `php`: executes the input as php code
*   `plain`: renders as plain text
*   `preserve`: preserves preformatted text

Filters which not enabled by default:

*   `coffee`: compiles coffeescript to javascript
    - dependence `coffeescript/coffeescript "~1"` (CoffeeScript)
*   `less`: compiles as Lesscss
    - depends one of following:  
        `oyejorge/less.php "*"` (OyejorgeLess)  
        or `leafo/lessphp "*"` (LeafoLess)
*   `scss`: converts scss to css
    - dependence `leafo/scssphp "*"` (Scss)  
        additionally, to use [Compass](http://compass-style.org/) `leafo/scssphp-compass "dev-master"`
*   `markdown`: converts markdown to html
    - depends one of following:  
        `michelf/php-markdown "~1.3"` (MichelfMarkdown, MichelfMarkdownExtra)  
        or `cebe/markdown "~1.0.1"` (CebeMarkdown, CebeMarkdownExtra, CebeGithubMarkdown)   
        or `erusev/parsedown "*"` (Parsedown)  
        or `league/commonmark ">=0.5"` (CommonMark)  
        or `kzykhys/ciconia "~1"` (CiconiaMarkdown)  
        or `fluxbb/commonmark "~1@dev"` (FluxBBMarkdown)
*   `rest`: converts reStructuredText to html
    - dependence `gregwar/rst "~1"` (ReST)
    
To enable non default filters you must first install them via composer.  
Example, install coffee filter:
```bash
composer require coffeescript/coffeescript "~1"
```

After what include it in your config file:
```php
    //....
    'renderers' => [
        'haml' => [
            'class' => 'mervick\mthaml\HamlViewRenderer',
            'cachePath' => '@runtime/Haml/cache',
            'options' => [
                //....
            ],
            'filters' => [
                // shorten
                'coffee' => 'CoffeeScript',
                // also you can specify filter options
                'coffee' => [
                    'filter' => 'CoffeeScript',
                    'options' => [
                        'header' => false,
                        'trace' => '@runtime/Haml/coffee-trace.log',
                    ],
                ],
            ],
        ],
    //....

```

List of all the filters with default options:

```php
    'coffee' => [
        // Package: "coffeescript/coffeescript"
        'filter' => 'CoffeeScript',  
        'options' => [
            // add a "Generated by..." header
            'header' => true,
            // reference to token stream (debugging)
            'tokens' => null,
            // file to write parser trace to (debugging)
            'trace' => null,
        ],
    ],
    
    'less' => [
        // Package: "oyejorge/less.php"
        'filter' => 'OyejorgeLess',
        'options' => [
            // whether to compress
            'compress' => false,
            // whether units need to evaluate correctly
            'strictUnits' => false,
            // whether math has to be within parenthesis
            'strictMath' => false,
            // option - whether to adjust URL's to be relative
            'relativeUrls' => true,
            // whether to add args into url tokens
            'urlArgs' => [],
            'numPrecision' => 8,
            // import dirs
            'importDirs' => [],
            // cache dir
            'cacheDir' => null
        ],
    ],
    
    'less' => [
        // Package: "leafo/lessphp"
        'filter' => 'LeafoLess',
        'options' => [
            // import dirs
            'importDirs' => [],
        ],
    ],
    
    'scss' => [
        // Package: "leafo/scssphp"
        'filter' => 'Scss',
        'options' => [
            // import dirs
            'importDirs' => [],
            // enable Compass integration, depends on "leafo/scssphp-compass"
            'enableCompass' => false,
        ],
    ],
    
    'markdown' => [
        // Package: "michelf/php-markdown"
        'filter' => 'MichelfMarkdown',
        'options' => [
            'forceOptimization' => false,
            'empty_element_suffix' => " />",
            'tab_width' => 4,
            'no_markup' => false,
            'no_entities' => false,
            'predef_urls' => [],
            'predef_titles' => [],
        ],
    ],
    
    'markdown' => [
        // Package: "michelf/php-markdown"
        'filter' => 'MichelfMarkdownExtra',
        'options' => [
            // Same as for MichelfMarkdown
        ],
    ],
    
    'markdown' => [
        // Package: "cebe/markdown"
        'filter' => 'CebeMarkdown',
        'options' => [
            'forceOptimization' => false,
            'html5' => false,
        ],
    ],
    
    'markdown' => [
        // Package: "cebe/markdown"
        'filter' => 'CebeMarkdownExtra',
        'options' => [
            'forceOptimization' => false,
            'html5' => false,
        ],
    ],
    
    'markdown' => [
        // Package: "cebe/markdown"
        'filter' => 'CebeGithubMarkdown',
        'options' => [
            'forceOptimization' => false,
            'html5' => false,
            'enableNewlines' => false,
        ],
    ],
    
    'markdown' => [
        // Package: "kzykhys/ciconia"
        'filter' => 'CiconiaMarkdown',
        'options' => [
            'forceOptimization' => false,
            'tabWidth' => 4,
            'nestedTagLevel' => 3,
            'strict' => false,
        ],
    ],
    
    'markdown' => [
        // Package: "league/commonmark"
        'filter' => 'CommonMark',
        'options' => [
            'forceOptimization' => false,
        ],
    ],
    
    'markdown' => [
        // Package: "fluxbb/commonmark"
        'filter' => 'FluxBBMarkdown',
        'options' => [
            'forceOptimization' => false,
        ],
    ],
    
    'markdown' => [
        // Package: "erusev/parsedown"
        'filter' => 'Parsedown',
        'options' => [
            'forceOptimization' => false,
        ],
    ],
    
    'rest' => [
        // Package: "gregwar/rst"
        'filter' => 'ReST',
        // no options
    ],

```

## Twig Extensions

The [Twig Extensions](http://twig.sensiolabs.org/doc/extensions/index.html) is a library that provides several useful extensions for Twig.

Install via Composer:
```bash
composer require twig/extensions "~1.1.0"
```

Add the following lines to config file:
```php
    //....
    'renderers' => [
        'twig' => [
            'class' => 'mervick\mthaml\TwigViewRenderer',
            'options' => [
                //...
            ],
            'extensions' => [
                'Text',   # Provides useful filters for text manipulation;
                'I18n',   # Adds internationalization support via the gettext library;
                'Intl',   # Adds a filter for localization of DateTime objects;
                'Array',  # Provides useful filters for array manipulation;
                'Date',   # Adds a filter for rendering the difference between dates.
            ],
        ],
    //....
```

## Attention!
Inside the templates you must use `$view` instead of `$this`, example: 
```HAML
-use backend\assets\AppAsset
-use yii\helpers\Html

-AppAsset::register($view)
-$view->beginPage()
!!!
%html{:lang=>Yii::$app->language}
    %head
        %meta{:charset=>Yii::$app->charset}
        %meta(name="viewport" content="width=device-width, initial-scale=1")
        !=Html::csrfMetaTags()
        %title =Html::encode($view->title)
        -$view->head()
    %body
        -$view->beginBody()
        !=$content
        -$view->endBody()
-$view->endPage()
```


## License

MtHaml extension for Yii2 Framework is released under the MIT license.

