<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;

$this->title = 'Index';
?>

<div class="site-index">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Balance</th>
            <th>Transfer <small>(login required)</small></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach($users as $user): ?>
        <tr>
            <td><?= $user->username ?></td>
            <td><?= $user->balance ?></td>
            <td>
                <?php if (Yii::$app->user->identity->username != $user->username && !Yii::$app->user->isGuest) : ?>
                    <a href="<?= Url::to(['/site/transfer', 'user' => $user->id]); ?>">Make transfer</a>
                <?php endIf; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
</div>
