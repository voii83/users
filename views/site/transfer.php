<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = $this->title = 'Transfer';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if( $error): ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?= $error ?>
    </div>
<?php endif;?>

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'transfer') ?>
<div class="form-group">
    <?= Html::submitButton('Make', ['class' => 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>
