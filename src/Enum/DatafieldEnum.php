<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Enum;

enum DatafieldEnum: string {
    case LISTENER = 'DatafieldListener';
    case GET_REPOSITORY = 'DatafieldListener:getRepository';
}