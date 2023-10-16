<?php

declare(strict_types=1);

namespace VitesseCms\Datafield\Forms;

use VitesseCms\Admin\Interfaces\AdminModelFormInterface;
use VitesseCms\Core\Utils\SystemUtil;
use VitesseCms\Datafield\Helpers\DatafieldUtil;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Helpers\ElementHelper;
use VitesseCms\Form\Models\Attributes;

final class DataFieldForm extends AbstractForm implements AdminModelFormInterface
{
    public function buildForm(): void
    {
        $this->addText('%CORE_NAME%', 'name', (new Attributes())->setRequired()->setMultilang())
            ->addText('%ADMIN_CALLING_NAME%', 'calling_name', (new Attributes())->setRequired())
            ->addDropdown(
                '%ADMIN_DATAFIELD_TYPE%',
                'type',
                (new Attributes())
                    ->setOptions(
                        ElementHelper::arrayToSelectOptions(
                            DatafieldUtil::getTypes(
                                SystemUtil::getModules($this->configuration),
                                '/Fields/'
                            )
                        )
                    )
                    ->setRequired()
            );

        if ($this->entity !== null && $this->entity->getType() !== null) {
            $object = $this->entity->getType();
            if (class_exists($object)) {
                (new $object())->buildAdminForm($this, $this->entity);
            }
        }

        $this->addToggle('%ADMIN_MULTILINGUAL%', 'multilang')
            ->addSubmitButton('%CORE_SAVE%');
    }
}
