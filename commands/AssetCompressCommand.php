<?php

/**
 * Class AssetCompressCommand
 * @see https://github.com/aarondfrancis/mantis-manager
 *
 * @usage:
 * ```
 * 'commandMap' => array(
 *   'assetCompress' => array(
 *     'class' => 'vendor.cornernote.yii-asset-compress.commands.AssetCompressCommand',
 *     'assetsPath' => 'application.assets',
 *     'css' => array(
 *       'combine' => array(
 *         'css/combined.css' => array(
 *           'vendor.twbs.bootstrap.dist|css/bootstrap.css',
 *           'bootstrap.assets|css/yiistrap.css',
 *           'vendor.fortawesome.font-awesome|css/font-awesome.min.css',
 *           'application.assets|css/app.css',
 *           'application|css/app.css',
 *         ),
 *       ),
 *       'minify' => true
 *     ),
 *     'js' => array(
 *       'combine' => array(
 *         'js/combined.js' => array(
 *           'system.web.js.source|jquery.min.js',
 *           'system.web.js.source|jquery.yiiactiveform.js',
 *           'vendor.twbs.bootstrap.dist|js/bootstrap.js',
 *           'application.assets|js/app.js',
 *           'application|js/app.js',
 *         ),
 *       ),
 *       'minify' => true
 *     ),
 *   ),
 * ),
 * ```
 */
class AssetCompressCommand extends CConsoleCommand
{

    /**
     * @var string
     */
    public $assetsPath = 'application.assets';
    /**
     * @var array
     */
    public $css = array();
    /**
     * @var array
     */
    public $js = array();

    /**
     * @var
     */
    private $_assetsPath;

    /**
     *
     */
    public function init()
    {
        $this->_assetsPath = Yii::getPathOfAlias($this->assetsPath);

        $this->css = CMap::mergeArray(array(
            'combine' => array(),
            'minify' => true
        ), $this->css);

        $this->js = CMap::mergeArray(array(
            'combine' => array(),
            'minify' => true
        ), $this->js);
    }

    /**
     *
     */
    public function actionIndex()
    {
        $this->consoleEcho("Asset Compress\r\n", "0;35");
        $combine = array_merge($this->css['combine'], $this->js['combine']);
        foreach ($combine as $filename => $files) {
            $this->consoleEcho("Combining ", "0;32");
            $this->consoleEcho("for " . $this->_assetsPath . '/' . $filename . "\r\n");
            $content = $this->combine($files);

            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            if ($ext == "css" && $this->css['minify']) {
                $this->consoleEcho("Minifying ", "0;32");
                $this->consoleEcho("$filename \r\n");
                $content = $this->minifyCSS($content);
            }
            if ($ext == "js" && $this->js['minify']) {
                $this->consoleEcho("Minifying ", "0;32");
                $this->consoleEcho("$filename \r\n");
                $content = $this->minifyJS($content);
            }

            $dir = dirname($this->_assetsPath . '/' . $filename);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $this->consoleEcho("Writing ", "0;32");
            $this->consoleEcho("$filename \r\n");
            file_put_contents($this->_assetsPath . '/' . $filename, $content);
        }
    }

    /**
     * @param array $files
     * @return string
     * @throws CException
     */
    private function combine($files)
    {
        $content = "";
        foreach ($files as $file) {
            $this->consoleEcho("Adding ", "0;32");
            $this->consoleEcho($file . "\r\n");

            list($_assetPath, $file) = explode('|', $file);
            if ($_assetPath == 'application') {
                $assetPath = Yii::getPathOfAlias('www');
                $assetUrl = Yii::app()->baseUrl;
            } else {
                $assetPath = Yii::getPathOfAlias($_assetPath);
                $assetUrl = Yii::app()->assetManager->publish($assetPath);
            }
            $_content = file_get_contents($assetPath . '/' . $file);

            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if ($ext == 'css') {
                $assetUrl = ltrim($assetUrl, '.');
                $relativePath = dirname($assetPath . '/' . $file);
                $relativeUrl = str_replace($assetPath, $assetUrl, $relativePath);
                $_content = preg_replace('%url\s*\(\s*[\\\'"]?(?!(((?:https?:)?\/\/)|(?:data:?:)))([^\\\'")]+)[\\\'"]?\s*\)%', 'url("' . $relativeUrl . '/$3")', $_content);
            }
            if ($ext == 'js') {
                $_content .= ';' . "\n";
            }
            $content .= $_content;
        }
        return $content;
    }

    /**
     * @param $contents
     * @return string
     */
    private function minifyCSS($contents)
    {
        if (!$contents) return "";
        return Minify_CSS_Compressor::process($contents);
    }

    /**
     * @param $contents
     * @return string
     */
    private function minifyJS($contents)
    {
        if (!$contents) return "";
        return JShrink\Minifier::minify($contents, array('flaggedComments' => false));
        //return Minify_JS_ClosureCompiler::minify($contents);
    }

    /**
     * @param $msg
     * @param null $color
     */
    public function consoleEcho($msg, $color = null)
    {
        if (Yii::app() instanceof CConsoleApplication) {
            if (!is_null($color)) {
                echo "\033[{$color}m" . $msg . "\033[0m";
            } else {
                echo $msg;
            }
        }
    }

}
