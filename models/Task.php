<?php

namespace app\models;

use Yii;
use Plp\Task\FatalException;
use Plp\Task\UserException;

/**
 * This is the model class for table "task".
 *
 * @property string $id
 * @property string $account_id
 * @property string $created
 * @property string $deffer
 * @property integer $type
 * @property string $task
 * @property string $action
 * @property string $data
 * @property integer $status
 * @property integer $retries
 * @property string $finished
 * @property string $result
 */
class Task extends \yii\db\ActiveRecord
{
    const STATUS_CREATED = 0;
    const STATUS_USER_EXCEPTION = 1;
    const STATUS_FATAL_EXCEPTION = 2;
    const STATUS_SUCCESSFULL = 3;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'type', 'status', 'retries'], 'integer'],
            [['created', 'deffer', 'finished'], 'safe'],
            [['data', 'result'], 'string'],
            [['task', 'action'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'account_id' => Yii::t('app', 'Account ID'),
            'created' => Yii::t('app', 'Created'),
            'deffer' => Yii::t('app', 'Deffer'),
            'type' => Yii::t('app', 'Type'),
            'task' => Yii::t('app', 'Task'),
            'action' => Yii::t('app', 'Action'),
            'data' => Yii::t('app', 'Data'),
            'status' => Yii::t('app', 'Status'),
            'retries' => Yii::t('app', 'Retries'),
            'finished' => Yii::t('app', 'Finished'),
            'result' => Yii::t('app', 'Result'),
        ];
    }
    
    public function execute()
    {
        $taskClass = "\Plp\Task\\".$this->task;
        $taskMethod = $this->action;
        $taskData = json_decode($this->data, true);
        
        if(!empty($taskData)) {
            if(class_exists($taskClass)){
                if(method_exists($taskClass, $taskMethod)) {
                    try {
                        $this->updateAfterExecution(self::STATUS_SUCCESSFULL, call_user_func(array($taskClass, $taskMethod), $taskData));                    
                    } catch (FatalException $e) { 
                        $this->updateAfterExecution(self::STATUS_FATAL_EXCEPTION, ["result" => false, "message" => $e->getMessage(), "trace" => $e->getTraceAsString()]);                    
                    }catch (UserException $e) {
                        $this->updateAfterExecution(self::STATUS_USER_EXCEPTION, ["result" => false, "message" => $e->getMessage(), "trace" => $e->getTraceAsString()]);
                    }
                }
                else { 
                    $this->updateAfterExecution(self::STATUS_FATAL_EXCEPTION, ["result" => false, "message" => $taskMethod." doesn't exist in ".$taskClass]);                
                }                
            }
            else {
                $this->updateAfterExecution(self::STATUS_FATAL_EXCEPTION, ["result" => false, "message" => $taskClass." doesn't exist"]);
            }        
        } 
        else {
            $this->updateAfterExecution(self::STATUS_FATAL_EXCEPTION, ["result" => false, "message" => "Task data is empty."]);
        }
        
        echo "Task ".$this->id." (".$this->task."::".$this->action.") finished ".$this->finished."\n";
        echo "Result: ".$this->result."\n";   
    }
    
    private function updateAfterExecution($status, $result) {
        $this->updateAttributes([
            'status' => $status,
            'result' => json_encode($result),
            'retries' => $this->retries + 1,
            'finished' => date('Y-m-d H:i:s')
        ]);
    }
}
