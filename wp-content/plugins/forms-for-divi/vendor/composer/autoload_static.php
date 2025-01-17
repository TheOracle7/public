<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitef01759362a52a3f86290ca6d076eaf6
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPTools\\Psr\\Container\\' => 22,
            'WPTools\\Pimple\\' => 15,
            'WPT_DiviForms_Divi_Modules\\' => 27,
            'WPT\\DiviForms\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPTools\\Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/wpt00ls/container/src',
        ),
        'WPTools\\Pimple\\' => 
        array (
            0 => __DIR__ . '/..' . '/wpt00ls/pimple/src/Pimple',
        ),
        'WPT_DiviForms_Divi_Modules\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes/modules',
        ),
        'WPT\\DiviForms\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes/classes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitef01759362a52a3f86290ca6d076eaf6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitef01759362a52a3f86290ca6d076eaf6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitef01759362a52a3f86290ca6d076eaf6::$classMap;

        }, null, ClassLoader::class);
    }
}
