<?php

class ApiClassLoader {

    private static $packages = array();

    /**
     * @packages
     */
    public static function register($packages) {
        self::$packages=$packages;
        return spl_autoload_register(array('ApiClassLoader', 'load'));
    }

    public static function load($classname) {
        if ((class_exists($classname))) {
            return false;
        }
        $filePhp = $classname . '.php';
        foreach (self::$packages as $package) {
            self::mapperClass($package, $filePhp);
        }
    }

    private static function mapperClass($dir, $file) {
        $pObjectFilePath = $dir . DIRECTORY_SEPARATOR . $file;
        if ((file_exists($pObjectFilePath) === false) || (is_readable($pObjectFilePath) === false)) {
            $itemHandler = opendir($dir);           
            while (($item = readdir($itemHandler)) !== false) {
                if (trim($item) != "." && trim($item) != "..") {
                    $dirTmp = $dir . DIRECTORY_SEPARATOR . $item;
                    if (is_dir($dirTmp) && substr($item, 0, 1) != ".") {
                        if (self::mapperClass($dirTmp, $file)) {
                            return true;
                        }
                    }
                }
            }
            return false;
        } else {
            require($pObjectFilePath);
            return true;
        }
    }

}
