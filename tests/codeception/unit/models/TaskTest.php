<?php

namespace tests\codeception\unit\models;

use app\models\Task;
use Plp\Task\UserException;
use Plp\Task\FatalException;
use Plp\Task\domain;
use Plp\Task\account;
use Plp\Task\integration;
use Plp\Task\message;
use yii\codeception\TestCase;

class TaskTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        // uncomment the following to load fixtures for user table
        //$this->loadFixtures(['user']);
    }

    public function testExecuteSuccessfull()
    {
        $task = Task::findOne(2971107);       
        $task->task = "Plp\\Task\\".$task->task; //domain::addzone('{"domain":"345"}');
        
        $task->execute();
        
        $this->assertEquals(Task::STATUS_SUCCESSFULL, $task->status);
        $this->assertEquals(["result" => true], $task->result);
        $this->assertEquals(1, $task->retries);
        $this->assertNotNull($task->finished);
    }
    
    public function testExecuteUserException()
    {
        $task = Task::findOne(2971187);
        $task->execute(); 
        
        $this->assertEquals(Task::STATUS_USER_EXCEPTION, $task->status);
        $this->assertFalse($task->result["result"]);
        $this->assertContains("message", $task->result);
        $this->assertContains("trace", $task->result);
        $this->assertEquals(1, $task->retries);
        $this->assertNotNull($task->finished);        
    }
    
    public function testExecuteFatalException()
    {
        $task = Task::findOne(87875454);
        $task->execute();
        
        $this->assertEquals(Task::STATUS_FATAL_EXCEPTION, $task->status);
        $this->assertFalse($task->result["result"]);
        $this->assertContains("message", $task->result);
        $this->assertContains("trace", $task->result);
        $this->assertEquals(1, $task->retries);
        $this->assertNotNull($task->finished);        
    }
    
    protected function expectException($exception, $callback)
    {
        $code = null;
        $msg = null;
        if (is_object($exception)) {
            /** @var $exception \Exception  **/
             $class = get_class($exception);
            $msg = $exception->getMessage();
            $code = $exception->getCode();
        } else {
            $class = $exception;
        }
        try {
            $callback();
        } catch (\Exception $e) {
            if (!$e instanceof $class) {
                $this->fail(sprintf("Exception of class $class expected to be thrown, but %s caught", get_class($e)));
            }
            if (null !== $msg and $e->getMessage() !== $msg) {
                $this->fail(sprintf(
                    "Exception of $class expected to be '$msg', but actual message was '%s'",
                    $e->getMessage()
                ));
            }
            if (null !== $code and $e->getCode() !== $code) {
                $this->fail(sprintf(
                    "Exception of $class expected to have code $code, but actual code was %s",
                    $e->getCode()
                ));
            }
            $this->assertTrue(true); // increment assertion counter
             return;
        }
        $this->fail("Expected exception to be thrown, but nothing was caught");
    }
}