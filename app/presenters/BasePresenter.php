<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;

/**
 * @author Michal Oktábec <info@michaloktabec.cz>
 */
abstract class BasePresenter extends Presenter
{
    protected function startup()
    {
        parent::startup();

        // odstraneni prebytecneho `_fid` parametru z URL
        if (!$this->hasFlashSession() && !empty($this->params[self::FLASH_KEY])) {
            unset($this->params[self::FLASH_KEY]);
            $this->redirect(301, 'this');
        }
    }

}
