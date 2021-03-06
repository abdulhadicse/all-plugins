<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdcc3b21534dfbfdcef6f222d4f073e8f
{
    public static $prefixLengthsPsr4 = array (
        'G' => 
        array (
            'Github\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Github\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitdcc3b21534dfbfdcef6f222d4f073e8f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdcc3b21534dfbfdcef6f222d4f073e8f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdcc3b21534dfbfdcef6f222d4f073e8f::$classMap;

        }, null, ClassLoader::class);
    }
}
