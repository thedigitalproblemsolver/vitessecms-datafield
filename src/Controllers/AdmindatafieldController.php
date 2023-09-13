<?php
declare(strict_types=1);

namespace VitesseCms\Datafield\Controllers;

use ArrayIterator;
use stdClass;
use VitesseCms\Admin\Interfaces\AdminModelAddableInterface;
use VitesseCms\Admin\Interfaces\AdminModelCopyableInterface;
use VitesseCms\Admin\Interfaces\AdminModelDeletableInterface;
use VitesseCms\Admin\Interfaces\AdminModelEditableInterface;
use VitesseCms\Admin\Interfaces\AdminModelFormInterface;
use VitesseCms\Admin\Interfaces\AdminModelListInterface;
use VitesseCms\Admin\Interfaces\AdminModelPublishableInterface;
use VitesseCms\Admin\Interfaces\AdminModelSaveInterface;
use VitesseCms\Admin\Traits\TraitAdminModelAddable;
use VitesseCms\Admin\Traits\TraitAdminModelCopyable;
use VitesseCms\Admin\Traits\TraitAdminModelDeletable;
use VitesseCms\Admin\Traits\TraitAdminModelEditable;
use VitesseCms\Admin\Traits\TraitAdminModelList;
use VitesseCms\Admin\Traits\TraitAdminModelPublishable;
use VitesseCms\Admin\Traits\TraitAdminModelSave;
use VitesseCms\Core\AbstractControllerAdmin;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Database\Models\FindOrder;
use VitesseCms\Database\Models\FindOrderIterator;
use VitesseCms\Database\Models\FindValueIterator;
use VitesseCms\Datafield\Enum\DatafieldEnum;
use VitesseCms\Datafield\Forms\DataFieldForm;
use VitesseCms\Datafield\Models\Datafield;
use VitesseCms\Datafield\Repositories\DatafieldRepository;

class AdmindatafieldController extends AbstractControllerAdmin implements
    AdminModelPublishableInterface,
    AdminModelListInterface,
    AdminModelEditableInterface,
    AdminModelSaveInterface,
    AdminModelDeletableInterface,
    AdminModelAddableInterface,
    AdminModelCopyableInterface
{
    use TraitAdminModelAddable;
    use TraitAdminModelCopyable;
    use TraitAdminModelDeletable;
    use TraitAdminModelEditable;
    use TraitAdminModelList;
    use TraitAdminModelPublishable;
    use TraitAdminModelSave;

    private readonly DatafieldRepository $datafieldRepository;

    public function OnConstruct()
    {
        parent::OnConstruct();

        $this->datafieldRepository = $this->eventsManager->fire(DatafieldEnum::GET_REPOSITORY->value, new stdClass());
    }

    public function getModel(string $id): ?AbstractCollection
    {
        return match ($id) {
            'new' => new Datafield(),
            default => $this->datafieldRepository->getById($id, false)
        };
    }

    public function getModelList(?FindValueIterator $findValueIterator): ArrayIterator
    {
        return $this->datafieldRepository->findAll(
            $findValueIterator,
            false,
            99999,
            new FindOrderIterator([new FindOrder('createdAt', -1)])
        );
    }

    public function getModelForm(): AdminModelFormInterface
    {
        return new DataFieldForm();
    }
}
