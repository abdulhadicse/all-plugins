<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf01445efeac82b52707fe10717038a09
{
    public static $files = array (
        '3ab65163a19208aff43dc519357bb64e' => __DIR__ . '/../..' . '/includes/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'AddressBook\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'AddressBook\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitf01445efeac82b52707fe10717038a09::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf01445efeac82b52707fe10717038a09::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf01445efeac82b52707fe10717038a09::$classMap;

        }, null, ClassLoader::class);
    }
}
