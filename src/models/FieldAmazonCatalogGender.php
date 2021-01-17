<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Models;

use VitesseCms\Core\Models\Datafield;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Datafield\AbstractField;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

/**
 * @deprecated move to shop
 */
class FieldAmazonCatalogGender extends AbstractField
{
    public function buildItemFormElement(
        AbstractForm $form,
        Datafield $datafield,
        AbstractCollection $data = null
    ) {
        $form->addDropdown(
            'Amazon gender',
            'AmazonCatalogGender',
            (new Attributes())->setOptions(ElementHelper::arrayToSelectOptions([
                'Women' => 'Women',
                'Men'   => 'Men',
            ]))
        );
    }
}
