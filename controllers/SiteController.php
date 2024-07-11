<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\base\DynamicModel;

use app\models\LoginForm;
use app\models\Service;
use app\models\Client;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'index', 'search'],
                'rules' => [
                    [
                        'actions' => ['logout', 'index', 'search'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->redirect('/service');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSearch()
    {
        $model = new DynamicModel(['phrase']);
        $model->addRule(['phrase'], 'string', ['max' => 255])
            ->addRule(['phrase'], 'required');
    
        if (!$model->load(Yii::$app->request->post())) {
            return $this->render('search', [
                'model' => $model,
                'dataProvider' => null,
                'count' => null
            ]);
        }
       
        $all = [];
        if (Yii::$app->request->isPost) {
            $intPhrase = intval($model->phrase);
            
            $query = Service::find()
                ->orWhere(['like', 'ip', "$model->phrase"])
                ->orWhere(['like', 'domain', "$model->phrase"]);
            if ($intPhrase) {
                $query->orWhere(['=', 'id', $intPhrase]);
            }
            $services = $query->all();

            foreach ($services as $service) {
                $data = "Type: $service->type, IP: $service->ip, Domain: $service->domain";
                $all[] = ['type' => 'service', 'id' => $service->id, 'data' => $data];
            }

            $query = Client::find()
                ->orWhere(['like', 'first_name', "$model->phrase"])
                ->orWhere(['like', 'last_name', "$model->phrase"]);
            if ($intPhrase) {
                $query->orWhere(['=', 'id', $intPhrase]);
            }
            $clients = $query->all();
    
            
            foreach ($clients as $client) {
                $data = "First Name: $client->first_name, Last Name: $client->last_name";
                $all[] = ['type' => 'client', 'id' => $client->id, 'data' => $data];
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $all,
        ]);

        return $this->render('search', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'count' => count($all)
        ]);
    }
}
