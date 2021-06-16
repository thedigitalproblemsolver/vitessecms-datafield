<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Helpers;

use VitesseCms\Core\Utils\DirectoryUtil;
use VitesseCms\Core\Utils\FileUtil;
use VitesseCms\Core\Utils\SystemUtil;

class DatafieldUtil
{
    //TODO merge with blocksUtil?
    public static function getTypes(array $modules, string $postPath): array
    {
        $files = [];
        $types = [];

        foreach ($modules as $directory) :
            $directory .= $postPath;
            $files = array_merge($files, DirectoryUtil::getFilelist($directory));
        endforeach;

        foreach ($files as $path => $file) :
            $name = FileUtil::getName($file);
            $className = SystemUtil::createNamespaceFromPath($path);
            $types[$className] = $name;
        endforeach;

        $types = array_flip($types);
        ksort($types);

        return array_flip($types);
    }
}