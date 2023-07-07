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
            'Input Type',
            'inputType',
            (new Attributes())
                ->setRequired()
                ->setOptions(ElementHelper::enumToSelectOptions(AdminFieldTextInputTypesEnum::cases()))
        );

        if ($item->getInputType() === 'hidden') :
            self::addHidden($form, $item);
        endif;
    }

    protected static function addHidden(AbstractForm $form, AbstractCollection $item): void
    {
        $form->AddText('Default value', 'defaultValue', (new Attributes())->setMultilang($item->isMultilang()));
    }
}
