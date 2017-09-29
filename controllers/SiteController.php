<?php

namespace app\controllers;

use app\models\Answer;
use app\models\Question;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string|Response
     */
    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays all-questions page.
     *
     * @return Response|string
     */
    public function actionAllQuestions()
    {
        $question = new Question();

        $data = Question::getAllQuestions(10);

        return $this->render('all-questions', [
            'model' => $question,
            'questions' => $data['questions'],
            'pagination' => $data['pagination'],
        ]);
    }

    /**
     * Displays question detail page.
     *
     * @return Response|string
     */
    public function actionQuestion($id)
    {
        $question = Question::findOne($id);
        $answerForm = new Answer();
        $answersData = $question->getQuestionAnswers(5);

        return $this->render('question', [
            'question' => $question,
            'answerForm' => $answerForm,
            'answers' => $answersData['answers'],
            'pagination' => $answersData['pagination'],
            'answersCount' => $answersData['count'],
        ]);
    }

    /**
     * Allows to save new question using ajax
     *
     * @return string
     */
    public function actionSave()
    {
        $model = new Question();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $result = 'success';
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return $result;
        } else {
            $error = 'error';
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return $error;
        }
    }

    /**
     * Allows to save new answer using ajax
     *
     * @return string
     */
    public function actionAnswer()
    {
        $model = new Answer();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $result = 'success';
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return $result;
        } else {
            $error = 'error';
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return $error;
        }
    }
}