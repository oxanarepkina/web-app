<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Question #' . $question->id;
$this->params['breadcrumbs'][] = ['label' => 'Questions', 'url' => ['all-questions']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <h3 class="message"></h3>

    <div class="row">

        <div class="list-group">
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
                        <?
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
                        <p> <? echo Yii::$app->helper->time_elapsed_string($question->date); ?> / <i
                                    class="fa fa-comment-o" aria-hidden="true"></i> <?= $answer ?></a></p>
                    </div>
                </div>
            </div>
        </div>

        <? if (!Yii::$app->user->isGuest): ?>
            <hr>
            <div class="answer-form">
                <?php $form = ActiveForm::begin(['id' => 'answer-form']); ?>

                <?= $form->field($answerForm, 'title')->textarea(['rows' => 6]) ?>

                <?= $form->field($answerForm, 'author_id')->hiddenInput(['value' => Yii::$app->user->getId()])->label(false); ?>
                <?= $form->field($answerForm, 'question_id')->hiddenInput(['value' => $question->id])->label(false); ?>
                <?= $form->field($answerForm, 'date')->hiddenInput(['value' => date("Y-m-d H:i:s")])->label(false); ?>


                <?php AjaxSubmitButton::begin([
                    'label' => 'Answer',
                    'useWithActiveForm' => 'answer-form',
                    'ajaxOptions' => [
                        'type' => 'POST',
                        'url' => \yii\helpers\Url::to(['/site/answer']),
                        'success' => new \yii\web\JsExpression('function(html){
                            if(html == "success") {
                                $("#successModal").modal({backdrop: "static", keyboard: false});
                                $("#answer-form")[0].reset();
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
            <hr>
        <? endif; ?>


        <?
        switch ($answersCount) {
            case 0:
                $answer = "";
                break;
            case 1:
                $answer = "1 answer";
                break;
            default:
                $answer = $answersCount . " answers";
                break;
        }
        ?>
        <h3><?= $answer ?> </h3>
        <div class="list-group">
            <? foreach ($answers as $answer): ?>
                <div class="list-group-item">
                    <div class="media">
                        <div class="media-left">
                            <img class="media-object img-circle" style="width: 50px; height: 50px"
                                 src="<?= $answer->author->getImage('photo'); ?>"
                                 alt="<?= $answer->author->username; ?>">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <?= $answer->author->username; ?>
                            </h4>
                            <?= $answer->title ?>
                            <hr>
                            <p> <? echo Yii::$app->helper->time_elapsed_string($answer->date); ?> </p>
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
                    <a href="<?= Url::toRoute(['site/question', 'id' => $question->id]) ?>" class="close">&times;</a>
                    <h4 class="modal-title">Wow</h4>
                </div>
                <div class="modal-body">
                    <p>Thanks for your answer.</p>
                </div>
                <div class="modal-footer">
                    <a href="<?= Url::toRoute(['site/question', 'id' => $question->id]) ?>" class="close">Ok</a>
                </div>
            </div>
        </div>
    </div>
</div>
