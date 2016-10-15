<?php

namespace app\commands;

use app\models\Task;
use Plp\Task\FatalException;
use Plp\Task\UserException;
use yii\console\Controller;

class TaskController extends Controller
{
    const TASK_LIMIT = 10;    
    const STATUS_CREATED = 0;
    const STATUS_IN_PROGRESS = 1;   
    const STATUS_USER_EXCEPTION = 2;
    const STATUS_FATAL_EXCEPTION = 3;
    const STATUS_SUCCESSFULL = 4;
    
    public function actionIndex()
    {
        $tasks = Task::find()            
            ->where(['<', 'retries', 3])
            ->andWhere(['<', 'status', self::STATUS_FATAL_EXCEPTION])
            ->limit(self::TASK_LIMIT)
            ->all();
        
        foreach($tasks as $task) {
            $taskClass = 'Plp\\Task\\'.$task->task;
            $taskMethod = $task->action;
            $taskData = json_decode($task->data, true);
            $task->updateAttributes(['status' => self::STATUS_IN_PROGRESS]);
            
            if(class_exists($taskClass)){
                if(method_exists($taskClass, $taskMethod)) {
                    try {
                        $result = call_user_func(array($taskClass, $taskMethod), $taskData);
                        $this->updateFields($task, $result, self::STATUS_SUCCESSFULL);                           
                    } catch (FatalException $e) {
                        $this->updateFields($task, ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()], self::STATUS_FATAL_EXCEPTION);                                              
                    }catch (UserException $e) {
                        $this->updateFields($task, ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()], self::STATUS_USER_EXCEPTION);                
                    }
                }
                else {
                    $this->updateFields($task, ['message' => $taskMethod." doesn't exist in ".$taskClass], self::STATUS_FATAL_EXCEPTION);
                }                
            }
            else {
                $this->updateFields($task, ['message' => $taskClass." doesn't exist"], self::STATUS_FATAL_EXCEPTION);
            }
                        
            echo "Task ".$task->id." (".$task->task."::".$task->action.") finished ".$task->finished."\n";
            echo "Result: ".$task->result."\n";
        }
    }
    
    protected function updateFields($task, $result, $status) {
        $task->result = json_encode($result);
        $task->status = $status;
        $task->retries += 1;
        $task->finished = date('Y-m-d H:i:s');
        $task->save();
    }
}
