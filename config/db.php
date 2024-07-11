<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=pgsql;port=5432;dbname=company',
    'username' => 'user',
    'password' => 'secret',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
