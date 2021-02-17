<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Helpers;

use VitesseCms\Configuration\Services\ConfigService;
use VitesseCms\Core\Utils\DirectoryUtil;
use VitesseCms\Core\Utils\FileUtil;
use VitesseCms\Core\Utils\SystemUtil;

class DatafieldUtil
{
    public static function getTypes(ConfigService $configService): array
    {
        $files = [];
        $types = [];

        $directories = [
            'accountdir' => $configService->getAccountDir() . 'src/datafield/Models',
            'verdornamedir' => $configService->getVendorNameDir().'datafield/src/Models'
        ];

        foreach ($directories as $directory) :
            $files = array_merge($files, DirectoryUtil::getFilelist($directory));
        endforeach;
        ksort($files);

        foreach ($files as $path => $file) :
            $name = FileUtil::getName($file);
            $className = SystemUtil::createNamespaceFromPath($path);
            $types[$className] = substr($name, 5, strlen($name));
        endforeach;

        return $types;
    }
}