<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit68b041b51dd8a7bf71609f82caab03a8
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Iprbooks\\Ebs\\Sdk\\Models\\' => 24,
            'Iprbooks\\Ebs\\Sdk\\Managers\\' => 26,
            'Iprbooks\\Ebs\\Sdk\\Core\\' => 22,
            'Iprbooks\\Ebs\\Sdk\\Collections\\' => 29,
            'Iprbooks\\Ebs\\Sdk\\' => 17,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Iprbooks\\Ebs\\Sdk\\Models\\' => 
        array (
            0 => __DIR__ . '/..' . '/iprbooks/iprbooks-ebs-sdk/src/models',
        ),
        'Iprbooks\\Ebs\\Sdk\\Managers\\' => 
        array (
            0 => __DIR__ . '/..' . '/iprbooks/iprbooks-ebs-sdk/src/managers',
        ),
        'Iprbooks\\Ebs\\Sdk\\Core\\' => 
        array (
            0 => __DIR__ . '/..' . '/iprbooks/iprbooks-ebs-sdk/src/core',
        ),
        'Iprbooks\\Ebs\\Sdk\\Collections\\' => 
        array (
            0 => __DIR__ . '/..' . '/iprbooks/iprbooks-ebs-sdk/src/collections',
        ),
        'Iprbooks\\Ebs\\Sdk\\' => 
        array (
            0 => __DIR__ . '/..' . '/iprbooks/iprbooks-ebs-sdk/src',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit68b041b51dd8a7bf71609f82caab03a8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit68b041b51dd8a7bf71609f82caab03a8::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
