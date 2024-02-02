<?php

declare(strict_types=1);

namespace VitesseCms\Datafield;

use Phalcon\Di\Di;
use Phalcon\Events\Manager;
use VitesseCms\Configuration\Services\ConfigService;
use VitesseCms\Content\Models\Item;
use VitesseCms\Core\AbstractInjectable;
use VitesseCms\Core\Helpers\InjectableHelper;
use VitesseCms\Core\Interfaces\InjectableInterface;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Datafield\Forms\DataFieldForm;
use VitesseCms\Datafield\Interfaces\AdminformInterface;
use VitesseCms\Datafield\Models\Datafield;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Form\Interfaces\AbstractFormInterface;
use VitesseCms\Form\Models\Attributes;
use VitesseCms\Sef\Utils\SefUtil;

abstract class AbstractField extends AbstractInjectable
{
    protected InjectableInterface $di;
    protected ?array $filter;
    protected Manager $eventsManager;
    protected ConfigService $configService;

    public function __construct()
    {
        $this->di = new InjectableHelper();
        $this->eventsManager = Di::getDefault()->get('eventsManager');
        $this->configService = Di::getDefault()->get('configuration');
    }

    /**
     * @deprecated should be moved to eventListener
     */
    public static function beforeMaincontent(Item $item, Datafield $datafield): void
    {
    }

    public function buildItemFormElement(
        AbstractForm $form,
        Datafield $datafield,
        Attributes $attributes,
        AbstractCollection $data = null
    ) {
    }

    /**
     * deprecated move to Listener
     */
    public function buildAdminForm(DataFieldForm $form, AbstractCollection $item)
    {
        $reflect = new \ReflectionClass($this);
        $class = 'VitesseCms\\Datafield\\Forms\\AdminField'.$reflect->getShortName();
        if (class_exists($class)) {
            /** @var AdminformInterface $class */
            $class::buildForm($form, $item);
        }
    }

    /**
     * @deprecated should be moved to eventListner
     */
    public function renderFilter(AbstractFormInterface $filter, Datafield $datafield): void
    {
    }

    /**
     * @deprecated should be moved to eventListner
     */
    public function renderAdminlistFilter(AbstractFormInterface $filter, Datafield $datafield): void
    {
    }

    public function getFieldname(Datafield $datafield): string
    {
        $fieldName = 'filter['.$datafield->_('calling_name').']';
        if ($datafield->_('multilang')) {
            $fieldName = 'filter['.$datafield->_('calling_name').'.'.$this->configService->getLanguageShort().']';
        }

        return $fieldName;
    }

    public function renderSlugPart(
        AbstractCollection $item,
        string $languageShort,
        Datafield $datafield
    ): string {
        $slug = $item->_($datafield->_('calling_name'), $languageShort);
        if (is_string($slug)) {
            return SefUtil::generateSlugFromString($slug);
        }

        return '';
    }

    public function getSearchValue(AbstractCollection $item, string $languageShort, Datafield $datafield)
    {
        return $item->_($datafield->_('calling_name'), $languageShort);
    }
}
