<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Forms;

use VitesseCms\Database\AbstractCollection;
use VitesseCms\Core\Utils\FileUtil;
use VitesseCms\Datafield\Interfaces\AdminformInterface;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

class AdminFieldImage implements AdminformInterface
{
    public static function buildForm(AbstractForm $form, AbstractCollection $item): void
    {
        $form->addText('Width', 'width')
            ->addText('Height', 'height')
            ->addDropdown(
                'Toegestaande bestandstype',
                'allowedFiletypeGroups',
                (new Attributes())->setMultiple()
                    ->setOptions(ElementHelper::arrayToSelectOptions(FileUtil::getFiletypeGroups()))
            );
    }
}
