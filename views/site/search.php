<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\grid\GridView;
?>
<div class="container">
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'phrase')->label('Search By Service [ID, IP, Domain] or  User [ID, FirstName, LastName]') ?>
        <div class="form-group">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

    <?php if (!is_null($count)): ?>
        <?php if ($count > 0): ?>
            <div class="alert alert-primary" role="alert">
                Found: <?=$count ?>
            </div>
            <?php else: ?>
            <div class="alert alert-danger" role="alert">
                No results found
            </div>
        <?php endif; ?>
    <?php endif; ?>


    <?php if ($dataProvider && $count): ?>
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => false,
                'summary' => false,
                'columns' => [
                    [
                        'label' => 'Type',
                        'attribute' => 'type',
                    ],
                    [
                        'label' => 'ID',
                        'attribute' => 'id',
                    ],
                    [
                        'label' => 'Data',
                        'attribute' => 'data',
                    ],
                ],
            ]);
        ?>
    <?php endif; ?>
</div>