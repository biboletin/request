<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5cb45a1f2c9583047d95fc494bd9e0ea
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
        ),
        'B' => 
        array (
            'Biboletin\\Request\\Facades\\' => 26,
            'Biboletin\\Request\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Biboletin\\Request\\Facades\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Facades',
        ),
        'Biboletin\\Request\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5cb45a1f2c9583047d95fc494bd9e0ea::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5cb45a1f2c9583047d95fc494bd9e0ea::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5cb45a1f2c9583047d95fc494bd9e0ea::$classMap;

        }, null, ClassLoader::class);
    }
}
