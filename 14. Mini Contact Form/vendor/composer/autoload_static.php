<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite19c51791ae8264973c94e062237b0fb
{
    public static $files = array (
        '72f96802294b9d123bf44cb6cdd8f083' => __DIR__ . '/../..' . '/includes/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'ContactForm\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ContactForm\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInite19c51791ae8264973c94e062237b0fb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite19c51791ae8264973c94e062237b0fb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite19c51791ae8264973c94e062237b0fb::$classMap;

        }, null, ClassLoader::class);
    }
}
