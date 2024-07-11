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
        'full_name'
);
?>

<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'type')->dropDownList($types) ?>
    <?= $form->field($model, 'client_id')->dropDownList($clients)->label('User') ?>
    <?= $form->field($model, 'ip')->label('IP') ?>
    <?= $form->field($model, 'domain', ['enableAjaxValidation' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
