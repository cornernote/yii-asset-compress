# Yii Asset Compress

Command to merge and minify assets for Yii.

## Features

- Merges and minifies lists of CSS or JS files into a single CSS or JS files.
- Replaces releative `url()` in CSS files
- Publishes required assets so that relative assets are available

## Documentation

Add to your CLI config:

```
$config = array(
    'commandMap' => array(
        'assetCompress' => array(
            'class' => 'vendor.cornernote.yii-asset-compress.commands.AssetCompressCommand',
            'assetsPath' => 'application.assets',
            'css' => array(
                'combine' => array(
                    'css/desktop.css' => array(
                        // format is: asset.path.alias|path/to/asset.css
                        'vendor.twbs.bootstrap.dist|css/bootstrap.css',             // -{ (alias!=application) = this asset path will be
                        'bootstrap.assets|css/yiistrap.css',                        // -{ published, and any url() in the CSS will be 
                        'vendor.fortawesome.font-awesome|css/font-awesome.min.css', // -{ replaced with this path.
                        'application.assets|css/desktop.css',                       // -{
                        'application|css/desktop.css',                              //  - (alias=application) = webroot, assets not published
                    ),
                    'css/mobile.css' => array(
                        'vendor.twbs.bootstrap.dist|css/bootstrap.css',
                        'bootstrap.assets|css/yiistrap.css',
                        'vendor.fortawesome.font-awesome|css/font-awesome.min.css',
                        'application.assets|css/mobile.css',
                        'application|css/mobile.css',
                    ),
                ),
                'minify' => true
            ),
            'js' => array(
                'combine' => array(
                    'js/desktop.js' => array(
                        // format is: asset.path.alias|path/to/asset.js
                        'system.web.js.source|jquery.min.js',            // -{ (alias!=application) = this asset path will be
                        'system.web.js.source|jquery.yiiactiveform.js',  // -{ published, and any url() in the CSS will be 
                        'vendor.twbs.bootstrap.dist|js/bootstrap.js',    // -{ replaced with this path.
                        'application.assets|js/desktop.js',              // -{ 
                        'application|js/desktop.js',                     // - webroot, assets not published
                    ),
                    'js/mobile.js' => array(
                        'system.web.js.source|jquery.min.js',
                        'system.web.js.source|jquery.yiiactiveform.js',
                        'vendor.twbs.bootstrap.dist|js/bootstrap.js',
                        'application.assets|js/mobile.js',
                        'application|js/mobile.js',
                    ),
                ),
                'minify' => true
            )
        ),
    ),
);
```

Then run from your YIIC command:

```
php yiic assetCompress
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

[![Latest Stable Version](https://poser.pugx.org/cornernote/yii-asset-compress/v/stable.png)](https://github.com/cornernote/yii-asset-compress/releases/latest) [![Total Downloads](https://poser.pugx.org/cornernote/yii-asset-compress/downloads.png)](https://packagist.org/packages/cornernote/yii-asset-compress) [![Monthly Downloads](https://poser.pugx.org/cornernote/yii-asset-compress/d/monthly.png)](https://packagist.org/packages/cornernote/yii-asset-compress) [![Latest Unstable Version](https://poser.pugx.org/cornernote/yii-asset-compress/v/unstable.png)](https://github.com/cornernote/yii-asset-compress) [![Build Status](https://travis-ci.org/cornernote/yii-asset-compress.png?branch=master)](https://travis-ci.org/cornernote/yii-asset-compress) [![License](https://poser.pugx.org/cornernote/yii-asset-compress/license.png)](https://raw.github.com/cornernote/yii-asset-compress/master/LICENSE)

