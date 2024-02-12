<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\models\User;
use Yii;


class AdminController extends Controller
{

    //override default layout and use admin.php instead
    public $layout = 'admin';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'dashboard'],
                'rules' => [
                    [
                        'actions' => ['dashboard'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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


    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException('Du hast keine Berechtigung diese seite zu sehen');
        }

        if (Yii::$app->user->identity->user_type === 'admin' || Yii::$app->user->identity->user_type === 'admin_guest') {
            return parent::beforeAction($action);
        }

        throw new ForbiddenHttpException('Du hast keine Berechtigung diese seite zu sehen');
    }

    /**
     * {@inheritdoc}
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDashboard()
    {

        return $this->render('dashboard');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest)
        {
            return $this->redirect(["admin/dashboard"]);
        }

        $request = Yii::$app->request->post();
        $admin = new User();
        if($request)
        {
            if ($admin->load($request) && $admin->adminLogin())
            {
                return $this->redirect(["admin/dashboard"]);
            }

            $session = Yii::$app->session;
            $session->setFlash('errorMessages', $admin->getErrors());
        }


        $admin->password = '';
        return $this->render('login', [
            'user' => $admin,
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

        return $this->redirect(["admin/index"]);
    }

}
