<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Models;

use VitesseCms\Datafield\Models\Datafield;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Datafield\AbstractField;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

/**
 * @deprecated move to shop
 */
class FieldAmazonCatalogType extends AbstractField
{
    public function buildItemFormElement(
        AbstractForm $form,
        Datafield $datafield,
        Attributes $attributes,
        AbstractCollection $data = null
    )
    {
        $form->addDropdown(
            'Amazon gender',
            'AmazonCatalogType',
            (new Attributes())->setOptions(ElementHelper::arrayToSelectOptions([
                'sweater' => 'sweater',
                'shirt' => 'shirt',
            ]))
        );
    }
}
