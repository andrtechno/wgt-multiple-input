<?php

namespace panix\ext\multipleinput\renderers;

use panix\engine\CMS;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use panix\ext\multipleinput\components\BaseColumn;

/**
 * Class TableLanguageRenderer
 * @package panix\ext\multipleinput\renderers
 */
class TableLanguageRenderer extends TableRenderer
{


    /**
     * @inheritdoc
     */
    protected function renderBody()
    {
        $rows = [];
        $languages = Yii::$app->languageManager->languages;
        if ($this->data) {

            foreach ($this->data as $index => $item) {
                $rows[] = $this->renderRowContent($index, $item);
            }
            foreach ($languages as $index => $item) {
                if (!isset($this->data[$index])) {
                    $rows[] = $this->renderRowContent($index, $item);
                }
            }
        } elseif ($this->min > 0) {
            foreach ($languages as $index => $item) {
                if (!isset($this->data[$index])) {
                    $rows[] = $this->renderRowContent($index);
                }
            }

        }
        return Html::tag('tbody', implode("\n", $rows));
    }

    /**
     * @inheritdoc
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

        if (substr($id, -4) === 'drag') {
            $options = ArrayHelper::merge($options, ['class' => $this->iconMap['drag-handle']]);
        }

        $options['style'] = 'background-image:url(/uploads/language/' . $index . '.png);';

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

        $hasError = false;
        $error = '';

        if ($index !== null) {
            $error = $column->getFirstError($index);
            $hasError = !empty($error);
        }

        if ($column->enableError) {
            $input .= "\n" . $column->renderError($error);
        }

        $wrapperOptions = ['class' => 'field-' . $id];
        if ($this->isBootstrapTheme()) {
            Html::addCssClass($wrapperOptions, 'text-center');
        }

        if ($hasError) {
            Html::addCssClass($wrapperOptions, 'has-error');
        }

        if (is_callable($column->columnOptions)) {
            $columnOptions = call_user_func($column->columnOptions, $column->getModel(), $index, $this->context);
        } else {
            $columnOptions = $column->columnOptions;
        }

        Html::addCssClass($columnOptions, 'list-cell__' . $column->name);

        $input = Html::tag('div', $input, $wrapperOptions);

        return Html::tag('td', $input, $columnOptions);
    }
}
