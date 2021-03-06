<?php

namespace panix\ext\multipleinput\renderers;

use unclead\multipleinput\renderers\BaseRenderer;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use unclead\multipleinput\renderers\DivRenderer as BaseDivRenderer;
use yii\helpers\UnsetArrayValue;

/**
 * Class DivRenderer is a list renderer who use divs
 */
class DivRenderer extends BaseDivRenderer
{


    /**
     * Renders the header.
     *
     * @return string
     */
    public function renderHeader()
    {
        if (!$this->isAddButtonPositionHeader()) {
            return '';
        }

        $options = ['class' => 'list-cell__button text-center'];
        $layoutConfig = array_merge([
            'buttonAddClass' => $this->isBootstrapTheme() ? 'col-sm-offset-9 col-sm-3' : '',
        ], $this->layoutConfig);
        Html::addCssClass($options, $layoutConfig['buttonAddClass']);

        return Html::tag('div', $this->renderAddButton(), $options);
    }

    /**
     * Renders the footer.
     *
     * @return string
     */
    public function renderFooter()
    {
        if (!$this->isAddButtonPositionFooter()) {
            return '';
        }

        $options = ['class' => 'list-cell__button text-center'];
        $layoutConfig = array_merge([
            'buttonAddClass' => $this->isBootstrapTheme() ? 'col-sm-offset-9 col-sm-3' : '',
        ], $this->layoutConfig);
        Html::addCssClass($options, $layoutConfig['buttonAddClass']);

        return Html::tag('div', $this->renderAddButton(), $options);
    }


    /**
     * Renders the cell content.
     *
     * @param BaseColumn $column
     * @param int|null $index
     * @param int|null $columnIndex
     * @return string
     * @throws \Exception
     */
    public function renderCellContent($column, $index, $columnIndex = null)
    {
        $id = $column->getElementId($index);
        $name = $column->getElementName($index);

        /**
         * This class inherits iconMap from BaseRenderer
         * If the input to be rendered is a drag column, we give it the appropriate icon class
         * via the $options array
         */
        $options = ['id' => $id];
        if ($column->type === BaseColumn::TYPE_DRAGCOLUMN) {
            $options = ArrayHelper::merge($options, ['class' => $this->iconMap['drag-handle']]);
        }
        $input = $column->renderInput($name, $options, [
            'id' => $id,
            'name' => $name,
            'indexPlaceholder' => $this->getIndexPlaceholder(),
            'index' => $index,
            'columnIndex' => $columnIndex,
            'context' => $this->context,
        ]);

        if ($column->isHiddenInput()) {
            return $input;
        }

        $layoutConfig = array_merge([
            'offsetClass' => $this->isBootstrapTheme() ? 'col-sm-offset-3' : '',
            'labelClass' => $this->isBootstrapTheme() ? 'col-sm-3' : '',
            'wrapperClass' => $this->isBootstrapTheme() ? 'col-sm-6' : '',
            'errorClass' => $this->isBootstrapTheme() ? 'col-sm-offset-3 col-sm-6' : '',
        ], $this->layoutConfig);

        Html::addCssClass($column->errorOptions, $layoutConfig['errorClass']);

        $hasError = false;
        $error = '';

        if ($index !== null) {
            $error = $column->getFirstError($index);
            $hasError = !empty($error);
        }

        $wrapperOptions = [];

        if ($hasError) {
            Html::addCssClass($wrapperOptions, 'has-error');
        }

        Html::addCssClass($wrapperOptions, $layoutConfig['wrapperClass']);

        $options = [
            'class' => "field-$id list-cell__$column->name" . ($hasError ? ' has-error' : '')
        ];

        if ($this->isBootstrapTheme()) {
            Html::addCssClass($options, 'form-group');
        }

        if (is_callable($column->columnOptions)) {
            $columnOptions = call_user_func($column->columnOptions, $column->getModel(), $index, $this->context);
        } else {
            $columnOptions = $column->columnOptions;
        }

        $options = array_merge_recursive($options, $columnOptions);

        $content = Html::beginTag('div', $options);

        if (empty($column->title)) {
            Html::addCssClass($wrapperOptions, $layoutConfig['offsetClass']);
        } else {
            $labelOptions = ['class' => $layoutConfig['labelClass']];
            if ($this->isBootstrapTheme()) {
                Html::addCssClass($labelOptions, 'control-label');
            }

            $content .= Html::label($column->title, $id, $labelOptions);
        }

        $content .= Html::tag('div', $input, $wrapperOptions);

        // first line
        if ($columnIndex == 0) {
            if ($this->max !== $this->min) {
                $content .= $this->renderActionColumn($index);
            }
            if ($this->cloneButton) {
                $content .= $this->renderCloneColumn();
            }
        }

        if ($column->enableError) {
            $content .= "\n" . $column->renderError($error);
        }

        $content .= Html::endTag('div');

        return $content;
    }

    /**
     * Renders the action column.
     *
     * @param null|int $index
     * @param null|ActiveRecordInterface|array $item
     * @return string
     */
    private function renderActionColumn($index = null, $item = null)
    {
        $content = $this->getActionButton($index) . $this->getExtraButtons($index, $item);

        $options = ['class' => 'list-cell__button text-center'];
        $layoutConfig = array_merge([
            'buttonActionClass' => $this->isBootstrapTheme() ? 'col-sm-offset-0 col-sm-2' : '',
        ], $this->layoutConfig);
        Html::addCssClass($options, $layoutConfig['buttonActionClass']);

        return Html::tag('div', $content, $options);
    }

    /**
     * Renders the clone column.
     *
     * @return string
     */
    private function renderCloneColumn()
    {

        $options = ['class' => 'list-cell__button text-center'];
        $layoutConfig = array_merge([
            'buttonCloneClass' => $this->isBootstrapTheme() ? 'col-sm-offset-0 col-sm-1' : '',
        ], $this->layoutConfig);
        Html::addCssClass($options, $layoutConfig['buttonCloneClass']);

        return Html::tag('div', $this->renderCloneButton(), $options);
    }

    private function getActionButton($index)
    {
        if ($index === null || $this->min === 0) {
            return $this->renderRemoveButton();
        }

        $index++;
        if ($index < $this->min) {
            return '';
        }

        if ($index === $this->min) {
            return $this->isAddButtonPositionRow() ? $this->renderAddButton() : '';
        }

        return $this->renderRemoveButton();
    }

    private function renderAddButton()
    {
        $options = [
            'class' => 'multiple-input-list__btn js-input-plus',
        ];
        Html::addCssClass($options, $this->addButtonOptions['class']);

        return Html::tag('div', $this->addButtonOptions['label'], $options);
    }

    /**
     * Renders remove button.
     *
     * @return string
     */
    private function renderRemoveButton()
    {
        $options = [
            'class' => 'multiple-input-list__btn js-input-remove',
        ];
        Html::addCssClass($options, $this->removeButtonOptions['class']);

        return Html::tag('div', $this->removeButtonOptions['label'], $options);
    }

    /**
     * Renders clone button.
     *
     * @return string
     */
    private function renderCloneButton()
    {
        $options = [
            'class' => 'multiple-input-list__btn js-input-clone',
        ];
        Html::addCssClass($options, $this->cloneButtonOptions['class']);

        return Html::tag('div', $this->cloneButtonOptions['label'], $options);
    }

    /**
     * Returns template for using in js.
     *
     * @return string
     *
     * @throws \yii\base\InvalidConfigException
     */
    protected function prepareTemplate()
    {
        return $this->renderRowContent();
    }

    /**
     * Returns an array of JQuery sortable plugin options for DivRenderer
     * @return array
     */
    protected function getJsSortableOptions()
    {
        return ArrayHelper::merge(parent::getJsSortableOptions(),
            [
                'containerSelector' => '.list-renderer',
                'itemPath' => new UnsetArrayValue,
                'itemSelector' => '.multiple-input-list__item',
            ]);
    }
}
