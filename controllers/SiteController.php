<?php

namespace app\controllers;

use app\models\TransferForm;
use app\models\User;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{
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

    public function actionIndex()
    {
        $query = User::find();
        $pages = new Pagination(['totalCount' => $query->count()]);
        $users = $query->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', ['users' => $users, 'pages' => $pages]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->username = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTransfer($user)
    {
        $error = '';
        if (Yii::$app->user->isGuest) {
            $this->redirect('/site/login');
        }

        if ($user == Yii::$app->user->getId()) {
            $this->redirect('/site/index');
        }

        $model = new TransferForm();

        if ($model->load(Yii::$app->request->post())) {
            $transfer = $model->save($user);

            if ($transfer['error']) {
                $error = $transfer['error'];
            } else {
                return $this->goHome();
            }
        }

        return $this->render('transfer', ['model' => $model, 'error' => $error]);
    }
}
