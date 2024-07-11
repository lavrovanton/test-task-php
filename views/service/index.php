<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use app\models\Client;
use app\models\Service;

$this->title = 'Services';

$services = ArrayHelper::map(Service::find()->asArray()->all(), 'type', 'type');
$clients = ArrayHelper::map(
    Client::find()->select(['client_id' => 'id', 'full_name' => "concat(first_name, ' ', last_name)"])->asArray()->all(),
    'client_id', 
    'full_name');

Modal::begin([
    'id' => 'activity-modal',
    'title' => 'Edit service',
]);
Modal::end();
?>

<?php Pjax::begin(['id' => 'services' ]); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'label' => 'Type',
            'attribute' => 'type',
            'value' => 'type',
            'filter' => Html::activeDropDownList(
                $searchModel, 
                'type', 
                $services, ['class' => 'form-control', 'prompt' => '--']),
        ],
        [
            'label' => 'User',
            'attribute' => 'full_name',
            'value' => function($model) { 
                return $model->client->first_name  . " " . $model->client->last_name; 
            },
            'filter' => Html::activeDropDownList(
                $searchModel, 
                'client_id', 
                $clients, ['class' => 'form-control', 'prompt' => '-- ']),
        ],
        [
            'label' => 'IP',
            'attribute' => 'ip',
        ],
        [
            'label' => 'Domain',
            'attribute' => 'domain',
        ],
        [
            'class' => ActionColumn::class,
            'header'=> 'Action',
            'template' => '{update} {delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a("Delete", $url, [
                        'title' => "Delete",
                        'data-method' => 'post',
                        'data-pjax' => '1',
                    ]);
                },
                'update' => function ($url, $model) {
                    return Html::a("Update", $url, [
                        'class' => 'service-update',
                        'title' =>'Update',
                        'data-toggle' => 'modal',
                        'data-target' => '#activity-modal',
                        'data-id' => $model->id,
                        'data-pjax' => '0',
                        'onclick' => "
                            event.preventDefault();
                            $.get($(this).attr('href'), function(data) {
                                $('.modal-body').html(data);
                                $('#activity-modal').modal('show');
                            });
                        ",
                    ]);
                },
            ],
        ],
    ],
    'pager' => ['class' => \yii\bootstrap5\LinkPager::class],
])
?>
<?php Pjax::end(); ?>

</div>
