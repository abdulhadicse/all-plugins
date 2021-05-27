<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit47e7928a89e332daa055399be52290ff
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WeDevs\\Academy\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WeDevs\\Academy\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit47e7928a89e332daa055399be52290ff::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit47e7928a89e332daa055399be52290ff::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit47e7928a89e332daa055399be52290ff::$classMap;

        }, null, ClassLoader::class);
    }
}
