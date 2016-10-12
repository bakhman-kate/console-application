<?php

use yii\db\Migration;

/**
 * Handles the creation for table `task`.
 */
class m161012_124647_create_task_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey()->unsigned(),
            'account_id' => $this->integer(10)->defaultValue(null)->unsigned(),
            'created' => $this->datetime()->defaultValue(null),
            'deffer' => $this->datetime()->defaultValue(null),
            'type' => 'tinyint(2) DEFAULT NULL',
            'task' => $this->string(45)->defaultValue(null),
            'action' => $this->string(45)->defaultValue(null),
            'data' => $this->text(),
            'status' => 'tinyint(2) DEFAULT NULL',
            'retries' => 'tinyint(2) DEFAULT NULL',
            'finished' => $this->datetime()->defaultValue(null),
            'result' => $this->text(),
        ]);
        
        $this->createIndex('status', 'task', 'status');        
        $this->createIndex('deffer', 'task', 'deffer');
    
        $this->insert('task', [
            'id' => 2971220,
            'account_id' => 70748,
            'created' => '2016­02­14 13:09:15',            
            'task' => 'integration',
            'action' => 'process',
            'data' => '{\"integration_id\":3312,\"lead_id\":\"2999670\"}',            
        ]);

        $this->insert('task', [
            'id' => 2971206,
            'account_id' => 80034,
            'created' => '2016­02­14 13:08:16',            
            'task' => 'message',
            'action' => 'sms',
            'data' => '{\"number\":\"89111111119\",\"message\":\"Заявка с ru.ru\\nвячеслав \\n\"}',            
        ]);
        
        $this->insert('task', [
            'id' => 2971187,
            'account_id' => 81259,
            'created' => '2016­02­14 13:06:42',            
            'task' => 'account',
            'action' => 'bill',
            'data' => '{\"bill_id\":\"82029\"}',            
        ]);
        
        $this->insert('task', [
            'id' => 2971123,
            'account_id' => 9608,
            'created' => '2016­02­14 13:01:58',            
            'task' => 'integration',
            'action' => 'process',
            'data' => '{\"integration_id\":2845,\"lead_id\":\"2999571\"}',            
        ]);

        $this->insert('task', [
            'id' => 2971122,
            'account_id' => 9608,
            'created' => '2016­02­14 13:01:53',            
            'task' => 'integration',
            'action' => 'process',
            'data' => '{\"integration_id\":2987,\"lead_id\":\"2999570\"}',            
        ]);

        $this->insert('task', [
            'id' => 2971107,
            'account_id' => 83992,
            'created' => '2016­02­14 13:01:03',            
            'task' => 'domain',
            'action' => 'addzone',
            'data' => '{\"domain\":\"mydomain.ru\"}',            
        ]);        
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('task', ['id' => 2971107]);
        $this->delete('task', ['id' => 2971122]);
        $this->delete('task', ['id' => 2971123]);
        $this->delete('task', ['id' => 2971187]);
        $this->delete('task', ['id' => 2971206]);
        $this->delete('task', ['id' => 2971220]);
        
        $this->dropIndex('status', 'task');        
        $this->dropIndex('deffer', 'task');       
        $this->dropTable('task');
    }
}
