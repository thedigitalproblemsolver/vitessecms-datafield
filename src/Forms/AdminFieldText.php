<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Forms;

use VitesseCms\Database\AbstractCollection;
use VitesseCms\Core\Factories\ObjectFactory;
use VitesseCms\Datafield\Enum\AdminFieldTextInputTypesEnum;
use VitesseCms\Datafield\Interfaces\AdminformInterface;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class AdminFieldText implements AdminformInterface
{
    public static function buildForm(AbstractForm $form, AbstractCollection $item): void
    {
        $form->addDropdown(
            '%DATAFIELD_INPUTTYPE%',
            'inputType',
            (new Attributes())
                ->setRequired()
                ->setOptions(ElementHelper::enumToSelectOptions(AdminFieldTextInputTypesEnum::cases()))
        );

        switch($item->getInputType()) {
            case AdminFieldTextInputTypesEnum::HIDDEN->value :
                self::addHidden($form, $item);
                break;
            case AdminFieldTextInputTypesEnum::DATE->value:
                $form->addText('%DATAFIELD_DATE_FORMAT%', 'date_format', (new Attributes())->setPlaceholder('%DATAFIELD_DATE_FORMAT_PLACEHOLDER%'));
                break;
        }
    }

    protected static function addHidden(AbstractForm $form, AbstractCollection $item): void
    {
        $form->AddText('Default value', 'defaultValue', (new Attributes())->setMultilang($item->isMultilang()));
    }
}
