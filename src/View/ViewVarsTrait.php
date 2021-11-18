<?php

namespace Shim\View;

use Cake\View\ViewBuilder;

/**
 * Provides own ViewBuilder with 5.x way of setting and handling helpers.
 */
trait ViewVarsTrait {

    /**
     * Get the view builder being used.
     *
     * @return \Cake\View\ViewBuilder
     */
    public function viewBuilder(): ViewBuilder {
        if (!isset($this->_viewBuilder)) {
            $this->_viewBuilder = new ShimViewBuilder();
        }

        return $this->_viewBuilder;
    }

}
