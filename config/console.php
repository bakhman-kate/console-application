<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests/codeception');
Yii::$classMap = [
    'Plp\Task\account' => '@app/src/account.php',
    'Plp\Task\domain' => '@app/src/domain.php',
    'Plp\Task\integration' => '@app/src/integration.php',
    'Plp\Task\message' => '@app/src/message.php',
    'Plp\Task\FatalException' => '@app/src/FatalException.php',
    'Plp\Task\UserException' => '@app/src/UserException.php',
];

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
