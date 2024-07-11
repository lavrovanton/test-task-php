<?php

namespace app\controllers;

use Yii;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;

use app\models\Service;
use app\models\ServiceSearch;

class ServiceController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'create' => ['get', 'post'],
                    'update' => ['get', 'post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('/service/index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionCreate()
    {
        $model = new Service();

        if (Yii::$app->request->isGet) {
            return $this->render('/service/create', [
                'model' => $model,
            ]);
        }

        if (Yii::$app->request->isPost) {
            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $this->refresh();
            }
        }
    }

    public function actionUpdate($id)
    {
        $model = Service::findOne($id);

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isGet) {
            return $this->renderAjax('/service/update', [
                'model' => $model,
            ]);
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                if ($validation = ActiveForm::validate($model)) {
                    return $validation;
                }
                if ($model->save()) {
                    return $this->renderAjax('/service/update', [
                        'model' => $model,
                    ]);
                }
            }
        }
    }

    public function actionDelete($id)
    {
        Service::findOne($id)->delete();

        return $this->actionIndex();
    }
}
