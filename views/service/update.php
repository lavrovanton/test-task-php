<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Client;
use app\models\ServiceType;

$types = ArrayHelper::map(ServiceType::cases(), 'name', 'name');
$clients = ArrayHelper::map(
    Client::find()->select(['client_id' => 'id', 'full_name' => "concat(first_name, ' ', last_name)"])->asArray()->all(),
    'client_id', 
    'full_name');
    
    $this->registerJs(
        '$("document").ready(function(){ 
                $("#update-service").on("pjax:end", function() {
                        $.pjax.reload({container:"#services"}); 
                        $("#activity-modal").modal("hide");
                });
        });'
    );
?>

<?php yii\widgets\Pjax::begin(['id' => 'update-service', 'enablePushState' => false]) ?>

<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true ]]); ?>
    <?= $form->field($model, 'type')->dropDownList($types) ?>
    <?= $form->field($model, 'client_id')->dropDownList($clients) ?>
    <?= $form->field($model, 'ip') ?>
    <?= $form->field($model, 'domain', ['enableAjaxValidation' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('Update', ['id' => 'update', 'class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>

<?php \yii\widgets\Pjax::end(); ?>