<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit60b12f9272db2265dd107c07481d4331
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'Danthero\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Danthero\\' => 
        array (
            0 => __DIR__ . '/..' . '/danthero/classes/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Slim' => 
            array (
                0 => __DIR__ . '/..' . '/slim/slim',
            ),
        ),
        'R' => 
        array (
            'Rain' => 
            array (
                0 => __DIR__ . '/..' . '/rain/raintpl/library',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit60b12f9272db2265dd107c07481d4331::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit60b12f9272db2265dd107c07481d4331::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit60b12f9272db2265dd107c07481d4331::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit60b12f9272db2265dd107c07481d4331::$classMap;

        }, null, ClassLoader::class);
    }
}