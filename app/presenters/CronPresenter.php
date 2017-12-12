<?php

namespace App\Presenters;

use \stekycz\Cronner\Cronner;
class CronPresenter extends \Nette\Application\UI\Presenter
{
    private $cronner;

    /**
     * CronPresenter constructor.
     * @param $cronner
     */
    public function __construct(Cronner $cronner)
    {
        $this->cronner = $cronner;
    }

    public function actionCron()
    {
        $this->cronner->addTasksCallback(function () {
            return new CronTaskService();
        });
        $this->cronner->run();
        $this->terminate();
    }

}