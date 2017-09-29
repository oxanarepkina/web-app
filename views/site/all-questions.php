<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Questions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <h3 class="message"></h3>

    <div class="row">
        <? if (!Yii::$app->user->isGuest): ?>
            <div class="question-form">
                <?php $form = ActiveForm::begin(['id' => 'question-form']); ?>

                <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'author_id')->hiddenInput(['value' => Yii::$app->user->getId()])->label(false); ?>
                <?= $form->field($model, 'date')->hiddenInput(['value' => date("Y-m-d H:i:s")])->label(false); ?>


                <?php AjaxSubmitButton::begin([
                    'label' => 'Send',
                    'useWithActiveForm' => 'question-form',
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => \yii\helpers\Url::to(['/site/save']),
                        'success' => new \yii\web\JsExpression('function(html){
                            if(html == "success") {
                                $("#successModal").modal({backdrop: "static", keyboard: false});
                                $("#question-form")[0].reset();
                            }
                            else {
                                console.log("error");
                            }   
                        }'),
                    ],
                    'options' => ['class' => 'btn btn-primary', 'type' => 'submit'],
                ]);
                AjaxSubmitButton::end();
                ?>

                <?php ActiveForm::end(); ?>
            </div>
        <? endif; ?>
        <hr>
        <div class="list-group">
            <? foreach ($questions as $question): ?>
                <div class="list-group-item">
                    <div class="media">
                        <div class="media-left">
                            <img class="media-object img-circle" style="width: 50px; height: 50px"
                                 src="<?= $question->author->getImage('photo'); ?>"
                                 alt="<?= $question->author->username; ?>">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading"><?= $question->author->username; ?></h4>
                            <?= $question->title ?>
                            <hr>
                            <p> <? echo Yii::$app->helper->time_elapsed_string($question->date); ?>
                                <span style="margin: 0 1em;">•</span>
                                <a href="<?= Url::toRoute(['site/question', 'id' => $question->id]) ?>">
                                    <? $answersCount = $question->getAnswersCount();

                                    switch ($answersCount) {
                                        case 0:
                                            $answer = "answer";
                                            break;
                                        case 1:
                                            $answer = "1 answer";
                                            break;
                                        default:
                                            $answer = $answersCount . " answers";
                                            break;
                                    }
                                    ?>
                                    <i class="fa fa-comment-o" aria-hidden="true"></i> <?= $answer ?>
                                </a>
                            </p>
                        </div>

                    </div>
                </div>
            <? endforeach; ?>
        </div>

        <?
        echo LinkPager::widget([
            'pagination' => $pagination,
        ]);
        ?>
    </div>
    <div class="modal fade" id="successModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <a href="/all-questions" class="close">&times;</a>
                    <h4 class="modal-title">Wow</h4>
                </div>
                <div class="modal-body">
                    <p>Thanks for your question.</p>
                    <p>Wait for the answers/</p>
                </div>
                <div class="modal-footer">
                    <a href="/all-questions" class="close">Ok</a>
                </div>
            </div>
        </div>
    </div>
</div>
