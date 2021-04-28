<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Forms;

use VitesseCms\Content\Models\Item;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Datagroup\Models\Datagroup;
use VitesseCms\Core\Utils\SystemUtil;
use VitesseCms\Datafield\Interfaces\AdminformInterface;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;
use VitesseCms\Media\Enums\AssetsEnum;

class AdminFieldModel implements AdminformInterface
{
    public static function buildForm(AbstractForm $form, AbstractCollection $item): void
    {
        $form->addDropdown(
            'Model',
            'model',
            (new Attributes())->setRequired()
                ->setInputClass(AssetsEnum::SELECT2)
                ->setOptions(ElementHelper::arrayToSelectOptions(
                    SystemUtil::getModels(true)
                ))
        )
            ->addNumber('Display limit', 'displayLimit')
            ->addToggle('use Select2', 'useSelect2')
            ->addToggle('Select multiple', 'multiple');

        switch ($item->_('model')) :
            case Item::class:
                $form->addDropdown(
                    'Items from datagroup',
                    'datagroup',
                    (new Attributes())->setMultiple()
                        ->setInputClass(AssetsEnum::SELECT2)
                        ->setOptions(ElementHelper::arrayToSelectOptions(Datagroup::findAll()))
                );
                break;
        endswitch;
    }
}
