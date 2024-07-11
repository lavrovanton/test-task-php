<?php

namespace app\commands;

use yii;
use yii\console\Controller;
use yii\console\ExitCode;

use app\models\User;
use app\models\Client;
use app\models\Service;

class DataController extends Controller
{
    /**  
     * This command creates admin user.
     * @param string $message the admin password.
     * @return int Exit code
     */
    public function actionCreateAdmin($password = 'admin')
    {

        $user = new User();
        $user->username = 'admin';
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($password);
        $user->auth_key = Yii::$app->security->generateRandomString(32);
        $user->save();

        return ExitCode::OK;
    }

    /**
     * This command generates fake data.
     * @return int Exit code
     */
    public function actionGenerateFake()
    {
        $clients = [
            [
                'first_name' => 'James', 
                'last_name' => 'Bourne',
                'service' => ['type' => 'Hosting', 'ip' => '8.8.8.8', 'domain' => 'kgb.com']
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Bond',
                'service' => ['type' => 'Proxy',   'ip' => '10.10.50.45', 'domain' => 'sis.gov.uk']
            ],
            [
                'first_name' => 'Ethan',
                'last_name' => 'Hunt',
                'service' => ['type' => 'Hosting', 'ip' => '12.41.56.32', 'domain' => 'cia.gov']
            ],
        ];

        foreach ($clients as $value) {
            $client = new Client();
            $client->first_name = $value['first_name'];
            $client->last_name  = $value['last_name'];
            $client->save();

            $service = new Service();
            $service->type = $value['service']['type'];
            $service->ip = $value['service']['ip'];
            $service->domain = $value['service']['domain'];
            $service->client_id = $client->id;
            $v = $service->save();
        }

        return ExitCode::OK;
    }
}
