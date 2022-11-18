<?php declare(strict_types=1);

namespace VitesseCms\Datafield;

use Phalcon\Exception;
use ReflectionClass;
use VitesseCms\Core\Helpers\InjectableHelper;
use VitesseCms\Database\AbstractCollection;
use VitesseCms\Content\Models\Item;
use VitesseCms\Core\AbstractInjectable;
use VitesseCms\Core\Interfaces\InjectableInterface;
use VitesseCms\Datafield\Forms\DataFieldForm;
use VitesseCms\Datafield\Interfaces\AdminformInterface;
use VitesseCms\Form\AbstractForm;
use VitesseCms\Datafield\Models\Datafield;
use VitesseCms\Form\Interfaces\AbstractFormInterface;
use Phalcon\Di\Di;
use Phalcon\Utils\Slug;
use VitesseCms\Form\Models\Attributes;

abstract class AbstractField extends AbstractInjectable
{
    /**
     * @var InjectableInterface
     */
    protected $di;

    public function __construct()
    {
        if (!is_object($this->di)) :
            $this->di = new InjectableHelper();
        endif;
    }

    /**
     * @deprecated should be moved to eventListner
     */
    public static function beforeMaincontent(Item $item, Datafield $datafield): void
    {
    }

    public function buildItemFormElement(
        AbstractForm $form,
        Datafield $datafield,
        Attributes $attributes,
        AbstractCollection $data = null
    )
    {
    }

    /**
     * @param DataFieldForm $form
     * @param AbstractCollection $item
     *
     * deprecated move to Listener
     */
    public function buildAdminForm(DataFieldForm $form, AbstractCollection $item)
    {
        $reflect = new ReflectionClass($this);
        /** @var AdminformInterface $class */
        $class = 'VitesseCms\\Datafield\\Forms\\AdminField' . $reflect->getShortName();
        if (class_exists($class)) :
            $class::buildForm($form, $item);
        endif;
    }

    /**
     * @param AbstractFormInterface $filter
     * @param Datafield $datafield
     *
     * @deprecated should be moved to eventListner
     */
    public function renderFilter(AbstractFormInterface $filter, Datafield $datafield): void
    {
    }

    /**
     * @param AbstractFormInterface $filter
     * @param Datafield $datafield
     *
     * @deprecated should be moved to eventListner
     */
    public function renderAdminlistFilter(AbstractFormInterface $filter, Datafield $datafield): void
    {
    }

    /**
     * @param Datafield $datafield
     *
     * @return string
     */
    public function getFieldname(Datafield $datafield): string
    {
        $fieldName = 'filter[' . $datafield->_('calling_name') . ']';
        if ($datafield->_('multilang')) :
            $fieldName = 'filter[' . $datafield->_('calling_name') . '.' . Di::getDefault()->get('configuration')->getLanguageShort() . ']';
        endif;

        return $fieldName;
    }

    /**
     * @param AbstractCollection $item
     * @param string $languageShort
     * @param Datafield $datafield
     *
     * @return string
     * @throws Exception
     */
    public function renderSlugPart(
        AbstractCollection $item,
        string $languageShort,
        Datafield $datafield
    ): string
    {
        $slug = $item->_($datafield->_('calling_name'), $languageShort);
        if (is_string($slug)) :
            return Slug::generate($slug);
        endif;

        return '';
    }

    /**
     * @param AbstractCollection $item
     * @param string $languageShort
     * @param Datafield $datafield
     *
     * @return mixed
     */
    public function getSearchValue(AbstractCollection $item, string $languageShort, Datafield $datafield)
    {
        return $item->_($datafield->_('calling_name'), $languageShort);
    }
}
