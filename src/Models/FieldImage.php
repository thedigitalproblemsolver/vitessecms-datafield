<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Models;

use VitesseCms\Content\Models\Item;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Admin\Utils\AdminUtil;
use VitesseCms\Core\Utils\FileUtil;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Datafield\Models\Datafield;
use VitesseCms\Datafield\AbstractField;
use VitesseCms\Form\Models\Attributes;

class FieldImage extends AbstractField
{
    public function buildItemFormElement(
        AbstractForm $form,
        Datafield $datafield,
        Attributes $attributes,
        AbstractCollection $data = null
    ){
        $allowedTypes = [];
        if ($datafield->_('allowedFiletypeGroups')) :
            $allowedTypes = [];
            foreach ((array)$datafield->_('allowedFiletypeGroups') as $filetypeGroup) :
                $allowedTypes = array_merge($allowedTypes, FileUtil::getFiletypesByGroup($filetypeGroup));
            endforeach;
        endif;

        $attributes->setAllowedTypes($allowedTypes);
        if (AdminUtil::isAdminPage()) :
            $form->addFilemanager(
                $datafield->getNameField(),
                $datafield->getCallingName(),
                $attributes
            );
        else :
            $form->addFile(
                $datafield->getNameField(),
                $datafield->getCallingName(),
                $attributes
            );
        endif;
    }
}
