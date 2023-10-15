<?php
declare(strict_types=1);

namespace VitesseCms\Datafield\Enum;

use Exception;
use ScssPhp\ScssPhp\Node\Number;
use VitesseCms\Form\Interfaces\SelectOptionEnumInterface;

enum AdminFieldTextInputTypesEnum: string implements SelectOptionEnumInterface
{
    case DATE = 'date';
    case EMAIL = 'email';
    case HIDDEN = 'hidden';
    case NUMBER = 'number';
    case PASSWORD = 'password';
    case PHONE = 'tel';
    case TEXT = 'text';
    case URL = 'url';

    public static function getLabel($label): string
    {
        return match ($label) {
            self::TEXT => '%DATAFIELD_INPUTTYPE_TEXT%',
            self::EMAIL => '%DATAFIELD_INPUTTYPE_EMAIL%',
            self::HIDDEN => '%DATAFIELD_INPUTTYPE_HIDDEN%',
            self::NUMBER => '%DATAFIELD_INPUTTYPE_NUMBER%',
            self::PHONE => '%DATAFIELD_INPUTTYPE_PHONE%',
            self::URL => '%DATAFIELD_INPUTTYPE_URL%',
            self::PASSWORD => '%DATAFIELD_INPUTTYPE_PASSWORD%',
            self::DATE => '%DATAFIELD_INPUTTYPE_DATE%',
            default => throw new Exception('Unexpected match value')
        };
    }
}