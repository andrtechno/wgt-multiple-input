<?php

namespace panix\ext\multipleinput\renderers;


/**
 * Interface RendererInterface
 * @package panix\ext\multipleinput\renderers
 */
interface RendererInterface
{
    const POS_HEADER    = 'header';
    const POS_ROW       = 'row';
    const POS_ROW_BEGIN = 'row_begin';
    const POS_FOOTER    = 'footer';

    const THEME_DEFAULT = 'secondary';
    const THEME_BS      = 'bootstrap';

    /**
     * Renders the widget's content.
     *
     * @return mixed
     */
    public function render();

    /**
     * Set current context.
     * 
     * @param mixed $context
     * @return mixed
     */
    public function setContext($context);

    /**
     * Returns a placeholder.
     *
     * @return string
     */
    public function getIndexPlaceholder();
}