<?php declare(strict_types=1);

namespace VitesseCms\Datafield\Models;

use VitesseCms\Database\AbstractCollection;
use VitesseCms\Core\Utils\DirectoryUtil;
use VitesseCms\Core\Utils\FileUtil;
use VitesseCms\Core\Utils\SystemUtil;
use VitesseCms\Form\Interfaces\AbstractFormInterface;

class Datafield extends AbstractCollection
{
    /**
     * @var string
     */
    public $calling_name;

    /**
     * @var string
     */
    public $datagroup;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $model;

    /**
     * @var string
     */
    public $inputType;

    public function getTemplates(): array
    {
        $templates = [];
        $dirs = DirectoryUtil::getChildren(
            $this->di->config->get('rootDir') . 'Template/core/Views/fields/'
        );
        foreach ($dirs as $name => $path) :
            $templates[$path] = strtolower($name);
        endforeach;

        return $templates;
    }

    public function renderFilter(AbstractFormInterface $filter): void
    {
        $object = $this->getType();
        /** @noinspection PhpUndefinedMethodInspection */
        (new $object())->renderFilter($filter, $this);
    }

    public function renderAdminlistFilter(AbstractFormInterface $filter): void
    {
        $object = $this->getType();
        /** @noinspection PhpUndefinedMethodInspection */
        (new $object())->renderAdminlistFilter($filter, $this);
    }

    public function getSlugPart(AbstractCollection $item, string $languageShort): string
    {
        $object = $this->getType();
        /** @noinspection PhpUndefinedMethodInspection */
        return (new $object())->renderSlugPart($item, $languageShort, $this);
    }

    public function getSearchValue(AbstractCollection $item, string $languageShort)
    {
        $object = $this->getType();
        /** @noinspection PhpUndefinedMethodInspection */
        return (new $object())->getSearchValue($item, $languageShort, $this);
    }

    public function getCallingName(): string
    {
        return $this->calling_name ?? '';
    }

    public function isMultilang(): bool
    {
        return (bool)$this->_('multilang');
    }

    public function getDatagroup(): ?string
    {
        return $this->datagroup;
    }

    public function getInputType(): string
    {
        return $this->inputType;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): Datafield
    {
        $this->type = $type;
        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): Datafield
    {
        $this->model = $model;

        return $this;
    }
}
