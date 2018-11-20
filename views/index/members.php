<?php
/* @var $this \yii\web\View */
/* @var $model \humhub\modules\geolocation\models\SearchForm */
/* @var $group humhub\modules\user\models\Group */
/* @var $users humhub\modules\user\models\User[] */

/* @var $pagination yii\data\Pagination */

use humhub\libs\Html;
use humhub\modules\directory\widgets\MemberActionsButton;
use humhub\modules\directory\widgets\UserGroupList;
use humhub\modules\directory\widgets\UserTagList;
use humhub\modules\user\widgets\Image;
use humhub\modules\ui\form\widgets\ActiveForm;



?>
<div class="panel panel-default">

    <div class="panel-heading">
        <?php if ($group === null) : ?>
            <?= Yii::t('DirectoryModule.base', '<strong>Member</strong> directory'); ?>
        <?php else: ?>
            <?= Yii::t('DirectoryModule.base', '<strong>Group</strong> members - {group}', ['{group}' => Html::encode($group->name)]); ?>
        <?php endif; ?>
    </div>

    <div class="panel-body">
        <?php $form = ActiveForm::begin(['action' => ['index/members'],
            'options' => ['method' => 'post', 'id' => 'group-search-form']]); ?>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'distance')->dropDownList([25=>"25", 50=>"50", 75=>"75", 100=>"100", 250=>"250", 500=>"500", 1000=>"1000"],
                    ["id" => "distanceDropDown", "options" => ['prompt' => "Select Distance"]]) ?>

            </div>
            <div class="col-md-6">
                <div class="form-group form-group-search">
                    <?= $form->field($model, 'longitude')->hiddenInput([true])->label(false); ?>

                    <?= $form->field($model, "keyword")->textInput(['style' => 'margin-top: 22px'])->label(false); ?>

                    <?= Html::submitButton(Yii::t('DirectoryModule.base', 'Search'), ['class' => 'btn btn-default btn-sm form-button-search']); ?>
                </div>
            </div>
            <div class="col-md-3">

                <?= $form->field($model, 'page')->hiddenInput([true])->label(false); ?>
                <?= $form->field($model, 'latitude')->hiddenInput([true])->label(false); ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

        <?php if (count($users) == 0): ?>
            <p id="userCount", count="<?= count($users)?>"><?= Yii::t('DirectoryModule.base', 'No members found!'); ?></p>
        <?php endif; ?>
        <?php if (count($users) > 0): ?>
            <p id="userCount", count="<?= count($users)?>"></p>
        <?php endif; ?>
    </div>

    <hr>

    <ul class="media-list">
        <?php foreach ($users as $user) : ?>
            <li>
                <div class="media">
                    <div class="pull-right memberActions">
                        <?= MemberActionsButton::widget(['user' => $user]); ?>
                    </div>

                    <?= Image::widget([
                        'user' => $user,
                        'htmlOptions' => ['class' => 'pull-left'],
                        'linkOptions' => ['data-contentcontainer-id' => $user->contentcontainer_id]
                    ]); ?>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <?= Html::containerLink($user); ?>
                            <div id="distance"><?php if (is_numeric($user->distance)){
                                echo round($user->distance) . " miles.</div>";
                                }else {
                                echo $user->distance ."</div>";
                                }  ?>
                            <?= UserGroupList::widget(['user' => $user]); ?>
                        </h4>
                        <h5><?= Html::encode($user->profile->title); ?></h5>
                        <?= UserTagList::widget(['user' => $user]); ?>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

</div>

<div class="pagination-container">
    <?= \humhub\widgets\LinkPager::widget(['pagination' => $pagination]); ?>
</div>
