# Yii Asset Compress

Command to merge and minify assets for Yii.

## Features

- Merges and minifies lists of CSS or JS files into a single CSS or JS files.
- Replaces releative `url()` in CSS files
- Publishes required assets so that relative assets are available

## Installation

Please download using ONE of the following methods:

### Composer Installation

All requirements are automatically downloaded into the correct location when using composer. There is no need to download additional files or set paths to third party files.

Get composer:

```
curl http://getcomposer.org/installer | php
```

Install latest release OR development version:

```
php composer.phar require cornernote/yii-asset-compress:*            // latest release
php composer.phar require cornernote/yii-asset-compress:dev-master    // development version
```

Add the `vendor` folder to the `aliases` in your yii configuration:

```
return array(
    'aliases' => array(
        'vendor' => '/path/to/vendor',
    ),
);
```

### Manual Installation

Download the [latest release](https://github.com/cornernote/yii-asset-compress/releases/latest) or [development version](https://github.com/cornernote/yii-asset-compress/archive/master.zip) and move the `commands/AssetCompressCommand.php` file into your `protected/commands` folder.

In addition the following are required:
- [tedious/JShrink](https://github.com/tedious/JShrink)
- [mrclay/minify](https://github.com/mrclay/minify)


## Configuration

Add to your yii console config:

```
return array(
    'commandMap' => array(
        'assetCompress' => array(
            'class' => 'vendor.cornernote.yii-asset-compress.commands.AssetCompressCommand',
            'assetsPath' => 'application.assets',
            'css' => array(
                'combine' => array(
                    'css/combined.css' => array(                                     // output to application.assets|css/desktop.css
                        // format is: asset.path.alias|path/to/asset.css
                        'vendor.twbs.bootstrap.dist|css/bootstrap.css',             // -{ (alias!=application) = this asset path will be
                        'bootstrap.assets|css/yiistrap.css',                        // -{ published, and any url() in the CSS will be 
                        'vendor.fortawesome.font-awesome|css/font-awesome.min.css', // -{ replaced with this path.
                        'application.assets|css/app.css',                           // -{
                        'application|css/app.css',                                  //  - (alias=application) = uses webroot, assets not published
                    ),
                ),
                'minify' => true
            ),
            'js' => array(
                'combine' => array(
                    'js/combined.js' => array(                            // output to application.assets|js/desktop.js
                        // format is: asset.path.alias|path/to/asset.js
                        'system.web.js.source|jquery.min.js',            // -{ (alias!=application) = this asset path will be
                        'system.web.js.source|jquery.yiiactiveform.js',  // -{ published, and any url() in the CSS will be 
                        'vendor.twbs.bootstrap.dist|js/bootstrap.js',    // -{ replaced with this path.
                        'application.assets|js/app.js',                  // -{ 
                        'application|js/app.js',                         // - (alias=application) = uses webroot, assets not published
                    ),
                ),
                'minify' => true
            )
        ),
    ),
);
```

## Compressing Assets

Run using your `yiic` command:

```
php yiic assetCompress
```

## Using Assets

To display your combined assets on your page you can use the following in your layout file:

```
$baseUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.assets'));
Yii::app()->clientScript->registerCssFile($baseUrl . '/css/combined.css');
Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/combined.js');
```

## Supressing Merged Assets

Now that you have jQuery and Bootstrap (and others) merged, you don't want them to output.  One method is to overwrite CClientScript:

```
<?php
class ClientScript extends CClientScript
{
    public $ignoreCoreScript = array();
    public $ignoreScriptFile = array();
    public $ignoreCssFile = array();

    public function registerCoreScript($name, $options = array())
    {
        if (in_array($name, $this->ignoreCoreScript))
            return $this;
        return parent::registerCoreScript($name);
    }
    public function registerScriptFile($url, $position = null, array $htmlOptions = array())
    {
        foreach ($this->ignoreScriptFile as $ignore)
            if ($this->endsWith($url, $ignore))
                return $this;
        return parent::registerScriptFile($url, $position, $htmlOptions);
    }
    public function registerCssFile($url, $media = '')
    {
        foreach ($this->ignoreCssFile as $ignore)
            if ($this->endsWith($url, $ignore))
                return $this;
        return parent::registerCssFile($url, $media);
    }

    private function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0)
            return true;
        return (substr($haystack, -$length) === $needle);
    }
}
```

Set this up in your config as follows:

```
return array(
    'components' => array(
        'clientScript' => array(
            'class' => 'application.components.ClientScript',
            'ignoreCssFile' => array(
                'bootstrap.css',
                'yiistrap.css',
                'font-awesome.min.css',
            ),
            'ignoreScriptFile'=>array(
                'bootstrap.js',
            ),
            'ignoreCoreScript' => array(
                'jquery',
                'yiiactiveform',
            ),
        ),
    ),
);
```


## Resources

- **[GitHub Project](https://github.com/cornernote/yii-asset-compress)**
- **[Yii Extension](http://www.yiiframework.com/extension/yii-asset-compress)**


## Support

- Does this README need improvement?  Go ahead and [suggest a change](https://github.com/cornernote/yii-asset-compress/edit/master/README.md).
- Found a bug, or need help using this project?  Check the [open issues](https://github.com/cornernote/yii-asset-compress/issues) or [create an issue](https://github.com/cornernote/yii-asset-compress/issues/new).


## License

[BSD-3-Clause](https://raw.github.com/cornernote/yii-asset-compress/master/LICENSE), Copyright © 2017 [Mr PHP](mailto:info@mrphp.com.au)


[![Mr PHP](https://raw.github.com/cornernote/mrphp-assets/master/img/code-banner.png)](http://mrphp.com.au)

[![Latest Stable Version](https://poser.pugx.org/cornernote/yii-asset-compress/v/stable.png)](https://github.com/cornernote/yii-asset-compress/releases/latest) [![Total Downloads](https://poser.pugx.org/cornernote/yii-asset-compress/downloads.png)](https://packagist.org/packages/cornernote/yii-asset-compress) [![Monthly Downloads](https://poser.pugx.org/cornernote/yii-asset-compress/d/monthly.png)](https://packagist.org/packages/cornernote/yii-asset-compress) [![Latest Unstable Version](https://poser.pugx.org/cornernote/yii-asset-compress/v/unstable.png)](https://github.com/cornernote/yii-asset-compress) [![License](https://poser.pugx.org/cornernote/yii-asset-compress/license.png)](https://raw.github.com/cornernote/yii-asset-compress/master/LICENSE)

