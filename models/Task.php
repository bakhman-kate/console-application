<?php

namespace app\models;

use Yii;

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
}
