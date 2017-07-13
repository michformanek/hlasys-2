<?php
/**
 * Created by PhpStorm.
 * User: mformanek
 * Date: 2.4.17
 * Time: 12:53
 */

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