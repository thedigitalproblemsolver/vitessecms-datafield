<?php

declare(strict_types=1);

namespace VitesseCms\Datafield\Forms;

use VitesseCms\Content\Models\Item;
use VitesseCms\Core\Utils\SystemUtil;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Datafield\Interfaces\AdminformInterface;
use VitesseCms\Datagroup\Models\Datagroup;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

final class AdminFieldModel implements AdminformInterface
{
    public static function buildForm(AbstractForm $form, AbstractCollection $item): void
    {
        $form->addDropdown(
            'Model',
            'model',
            (new Attributes())->setRequired()
                ->setInputClass('select2')
                ->setOptions(
                    ElementHelper::arrayToSelectOptions(
                        SystemUtil::getModels(true)
                    )
                )
        )
            ->addNumber('Display limit', 'displayLimit')
            ->addToggle('use Select2', 'useSelect2')
            ->addToggle('Select multiple', 'multiple');

        if ($item->_('model') === Item::class) {
            $form->addDropdown(
                'Items from datagroup',
                'datagroups',
                (new Attributes())->setMultiple()
                    ->setInputClass('select2')
                    ->setOptions(ElementHelper::arrayToSelectOptions(Datagroup::findAll()))
            );
        }
    }
}
