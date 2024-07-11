<?php
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
?>

<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'first_name') ?>
    <?= $form->field($model, 'last_name') ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>