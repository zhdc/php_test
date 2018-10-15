<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4b0af687f85158dd21aa80dc1c2033da
{
    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'Zend\\' => 5,
            'ZendXml\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Zend\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zendframework/library/Zend',
        ),
        'ZendXml\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zendxml/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4b0af687f85158dd21aa80dc1c2033da::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4b0af687f85158dd21aa80dc1c2033da::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}