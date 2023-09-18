<?php

declare(strict_types=1);

namespace VitesseCms\Datafield\Migrations;

use stdClass;
use VitesseCms\Database\AbstractMigration;
use VitesseCms\Datafield\Enum\DatafieldEnum;

class Migration_20210522 extends AbstractMigration
{
    public function up(): bool
    {
        $result = true;
        if (!self::parseDatafieldType()) :
            $result = false;
        endif;

        return $result;
    }

    private function parseDatafieldType(): bool
    {
        $result = true;
        $datafieldRepository = $this->eventsManager->fire(DatafieldEnum::GET_REPOSITORY->value, new stdClass());

        $datafields = $datafieldRepository->findAll(null, false);
        $search = [
            'VitesseCms\Datafield\Models\FieldAddtocart',
            'VitesseCms\Datafield\Models\FieldAmazonBrowseNode',
            'VitesseCms\Datafield\Models\FieldAmazonCatalogGender',
            'VitesseCms\Datafield\Models\FieldAmazonCatalogType',
            'VitesseCms\Datafield\Models\FieldCheckbox',
            'VitesseCms\Datafield\Models\FieldDatagroup',
            'VitesseCms\Datafield\Models\FieldEtsyCategory',
            'VitesseCms\Datafield\Models\FieldEtsyListing',
            'VitesseCms\Datafield\Models\FieldFacebookCatalogGender',
            'VitesseCms\Datafield\Models\FieldImage',
            'VitesseCms\Datafield\Models\FieldModel',
            'VitesseCms\Datafield\Models\FieldPrice',
            'VitesseCms\Datafield\Models\FieldSizeAndColor',
            'VitesseCms\Datafield\Models\FieldText',
            'VitesseCms\Datafield\Models\text',
            'VitesseCms\Datafield\Models\FieldTextarea',
            'VitesseCms\Datafield\Models\FieldTexteditor',
            'VitesseCms\Content\Fields\Textarea',
            'VitesseCms\Content\Fields\Texteditor',
        ];
        $replace = [
            'VitesseCms\Shop\Fields\ShopAddToCart',
            'VitesseCms\Shop\Fields\AmazonBrowseNode',
            'VitesseCms\Shop\Fields\AmazonCatalogGender',
            'VitesseCms\Shop\Fields\AmazonCatalogType',
            'VitesseCms\Content\Fields\Toggle',
            'VitesseCms\Datagroup\Fields\Datagroup',
            'VitesseCms\Etsy\Fields\EtsyCategory',
            'VitesseCms\Etsy\Fields\EtsyListing',
            'VitesseCms\Shop\Fields\FacebookCatalogGender',
            'VitesseCms\Media\Fields\Image',
            'VitesseCms\Content\Fields\Model',
            'VitesseCms\Shop\Fields\ShopPrice',
            'VitesseCms\Shop\Fields\ShopSizeAndColor',
            'VitesseCms\Content\Fields\Text',
            'VitesseCms\Content\Fields\Text',
            'VitesseCms\Content\Fields\TextArea',
            'VitesseCms\Content\Fields\TextEditor',
            'VitesseCms\Content\Fields\TextArea',
            'VitesseCms\Content\Fields\TextEditor',
        ];
        while ($datafields->valid()):
            $datafield = $datafields->current();
            $type = str_replace($search, $replace, $datafield->getType());
            if (substr_count($type, 'VitesseCms\Datafield\Models') === 1):
                $result = false;
                $this->terminalService->printError(
                    'Wrong type "' . str_replace(
                        $search,
                        $replace,
                        $datafield->getType()
                    ) . '" for datafield "' . $datafield->getNameField() . '"'
                );
            else :
                $datafield->setType($type)->save();
            endif;
            $datafields->next();
        endwhile;

        $this->terminalService->printMessage('datafields type repaired');

        return $result;
    }
}