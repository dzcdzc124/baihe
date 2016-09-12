<?php

namespace App\Tasks;

use App\Helpers\System as SystemHelper;
use App\Helpers\User as UserHelper;


class MainTask extends TaskBase
{
    public function mainAction($params = [])
    {
        $taskName = 'task.main';

        $operation = trim(array_shift($params));
        if ($operation == 'reload') {
            $this->mutex->unlock($taskName);
            return true;
        }

        if ($this->mutex->lock($taskName, 60)) {
            $prevTime = $cron1Time = 0;

            while (true) {
                $thisTime = time();

                if ( ! $this->mutex->isLock($taskName))
                    break;
                elseif ($thisTime - $prevTime >= 45) {
                    $this->mutex->update($taskName, 60);
                    $prevTime = $thisTime;
                }

                if ($thisTime - $cron1Time >= 300) {
                    $cron1Time = $thisTime;
                    SystemHelper::runTask('department/sync');
                }

                sleep(3);
            }

            $this->mutex->unlock($taskName);
        }
    }

    public function testAction()
    {
        UserHelper::syncDeleted();
    }
}