<?php

namespace app\commands;

use app\models\Task;
use yii\console\Controller;

class TaskController extends Controller
{
    const TASK_LIMIT = 10;
    
    public function actionIndex()
    {
        $tasks = Task::find()            
            ->where(['<', 'retries', 3])
            ->andWhere(['<', 'status', Task::STATUS_FATAL_EXCEPTION])
            ->limit(self::TASK_LIMIT)
            ->all();
        
        foreach($tasks as $task) {
            $task->execute();            
        }
    }
}
