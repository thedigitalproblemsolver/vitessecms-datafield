<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Models;

use VitesseCms\Datafield\Models\Datafield;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Datafield\AbstractField;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;
use VitesseCms\Media\Enums\AssetsEnum;

class FieldModel extends AbstractField
{
    public function buildItemFormElement(
        AbstractForm $form,
        Datafield $datafield,
        Attributes $attributes,
        AbstractCollection $data = null
    ){
        $model = $datafield->getModel();
        $model::addFindOrder('name');
        if ($datafield->_('displayLimit') && $datafield->_('displayLimit') > 0):
            $model::setFindLimit($datafield->_('displayLimit'));
        endif;

        if (!empty($datafield->getDatagroup())):
            $model::setFindValue('datagroup', ['$in' => [$datafield->getDatagroup()]]);
        endif;

        $attributes->setOptions(ElementHelper::arrayToSelectOptions($model::findAll()));

        if ($datafield->_('useSelect2')) :
            $attributes->setInputClass(AssetsEnum::SELECT2);
        endif;

        if ($datafield->_('multiple')) :
            $attributes->setMultiple();
        endif;

        $form->addDropdown($datafield->getNameField(), $datafield->getCallingName(), $attributes);
    }

    public static function beforeSave(AbstractCollection $item, Datafield $datafield)
    {
        $value = $item->_($datafield->getCallingName());
        if ($value) :
            if (!is_array($value)) :
                $object = $datafield->_('model');
                /** @var AbstractCollection $datafieldItem */
                $datafieldItem = $object::findById($value);
                $item->set(
                    $datafield->getCallingName() . 'Name',
                    $datafieldItem->getNameField()
                );
            endif;
        else :
            $item->set($datafield->getCallingName() . 'Name', '');
        endif;
    }

    public function renderSlugPart(AbstractCollection $item, string $languageShort, Datafield $datafield): string
    {
        return 'page:' . $item->_($datafield->getCallingName());
    }
}
