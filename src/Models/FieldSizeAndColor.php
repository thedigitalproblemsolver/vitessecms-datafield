<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Models;

use VitesseCms\Datafield\Models\Datafield;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Datafield\AbstractField;
use VitesseCms\Datafield\Enums\FieldSizeAndColorEnum;
use VitesseCms\Datafield\Factories\FieldSizeAndColorFactory;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Interfaces\AbstractFormInterface;
use VitesseCms\Form\Models\Attributes;
use VitesseCms\Media\Enums\AssetsEnum;
use Phalcon\Http\Request;
use Phalcon\Tag;

/**
 * @deprecated move to shop
 */
class FieldSizeAndColor extends AbstractField
{
    public function buildItemFormElement(
        AbstractForm $form,
        Datafield $datafield,
        Attributes $attributes,
        AbstractCollection $data = null
    )
    {
        if ($data !== null) {
            $form->assets->load(AssetsEnum::COLORPICKER);
            $form->assets->load(AssetsEnum::FILEMANAGER);
            $fieldName = $datafield->getCallingName();

            $dataVariations = $data->_($fieldName);
            $images = $variations = $colorParsed = [];
            if (\is_array($dataVariations)) :
                ksort($dataVariations);
                foreach ($dataVariations as $key => $variation) :
                    $variations[] = FieldSizeAndColorFactory::createVariationForm($variation, $fieldName);
                    if (!isset($colorParsed[$variation['color']])) :
                        if (!\is_array($variation['image'])) :
                            $variation['image'] = [$variation['image']];
                        endif;
                        foreach ($variation['image'] as $imageKey => $image) :
                            $images[] = [
                                'id' => str_replace('#', '', strtolower($variation['color'])) . $imageKey,
                                'name' => $fieldName . '_images[' . strtolower($variation['color']) . '][]',
                                'color' => strtolower($variation['color']),
                                'image' => $image,
                            ];
                        endforeach;
                        $images[] = [
                            'id' => str_replace('#', '', strtolower($variation['color'])),
                            'name' => $fieldName . '_images[' . strtolower($variation['color']) . '][]',
                            'color' => strtolower($variation['color']),
                            'image' => '',
                        ];
                        $colorParsed[$variation['color']] = $variation['color'];
                    endif;
                endforeach;
            endif;

            $params = [
                'sizeAndColorLabel' => $datafield->getNameField(),
                'sizeAndColorId' => uniqid('', false),
                'sizeAndColorVariations' => $variations,
                'sizeAndColorImages' => $images,
                'skuBaseElement' => Tag::textField(['name' => $fieldName . '[__key__][sku]', 'id' => null]),
                //TODO : zonder @-teken komt er een foutmelding, waarschijnlijk is dit een bug in het Phalcon framework
                'sizeBaseElement' => @Tag::select(['name' => $fieldName . '[__key__][size]', 'id' => null],
                    FieldSizeAndColorEnum::sizes),
                'colorBaseElement' => Tag::textField(['name' => $fieldName . '[__key__][color]', 'id' => null]),
                'stockBaseElement' => Tag::numericField(['name' => $fieldName . '[__key__][stock]', 'id' => null]),
                'stockMinimalBaseElement' => Tag::numericField(['name' => $fieldName . '[__key__][stock]', 'id' => null]),
                'eanBaseElement' => Tag::numericField([
                    'name' => $fieldName . '[__key__][ean]',
                    'maxlength' => 13,
                    'id' => null,
                ]),
            ];

            $form->addHtml($form->view->renderTemplate(
                'adminItemFormSizeAndColor',
                $form->configuration->getRootDir() . 'datafirld/src/Resources/views/',
                $params
            ));
        }
    }

    public function renderFilter(
        AbstractFormInterface $filter,
        Datafield $datafield,
        AbstractCollection $data = null
    ): void
    {
        $options = [];
        foreach (FieldSizeAndColorEnum::sizes as $key => $label) :
            $options[] = [
                'value' => $key,
                'label' => $label,
                'selected' => false,
            ];
        endforeach;

        $filter->addDropdown(
            'select',
            '%SHOP_SIZE%',
            'filter[variations][]',
            (new Attributes())
                ->setMultiple()
                ->setNoEmptyText()
                ->setInputClass(AssetsEnum::SELECT2)
                ->setOptions($options)
        );
    }

    public function getSearchValue(AbstractCollection $item, string $languageShort, Datafield $datafield)
    {
        $searchValues = [];
        if (is_array($item->_($datafield->getCallingName()))) :
            foreach ($item->_($datafield->getCallingName()) as $variation) :
                if ($variation['stock'] > 0 && !\in_array($variation['size'], $searchValues, true)) :
                    $searchValues[] = $variation['size'];
                endif;
            endforeach;
        endif;

        return $searchValues;
    }
}
