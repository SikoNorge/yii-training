<?php

namespace app\controllers;

use app\models\Post;
use app\models\ProfilePage;
use app\models\User;
use app\models\VisitModel;
use Yii;
use yii\base\Action;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use app\models\RegisterForm;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;





class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $userData = VisitModel::getUserVisitCount();
        $userDataJson = json_encode($userData);

        $this->getView()->registerJs("var userData = {$userDataJson};", \yii\web\View::POS_HEAD);

        $postModel = new Post();
        $postModel->user_id = Yii::$app->user->id;
        // Abrufen des Inputs
        if ($postModel->load(Yii::$app->request->post()) && $postModel->validate()) {
            // Speichern des Inputs
            if ($postModel->save()) {
                // Weiterleitung zum Post
                Yii::$app->session->setFlash('postSuccess', true);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Fehler beim Speichern des Posts.');
            }
        }
        return $this->render('index',[
            'postModel' => $postModel,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */


    public function actionRegister()
    {
        $user = new RegisterForm();
        //when data invalid or site is opened first time then display 'register'
        return $this->render('register', ['model'=>$user]);
    }

    public function actionSignUp()
    {
        $request = Yii::$app->request->post();

        $user = new RegisterForm();
        $user->attributes = $request;
        $user->password_confirm = $request['password_confirm'];
        $session = Yii::$app->session;
        if($user->validate())
        {
            unset($user['password_confirm']);
            //Hashes the password before storing it to DB
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
            if ($user->save(false))
            {
                $session->setFlash('successMessage', 'Registration successful');
                return $this->redirect(['site/login']);
            }
        }

        $session->setFlash('errorMessages', $user->getErrors());
        return $this->redirect(['site/register']);
    }


    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest)
        {
            return $this->redirect(['site/dashboard']);
        }

        $request = Yii::$app->request->post();
        $user = new User();
        if ($request)
        {
            if ($user->load($request) && $user->login())
            {
                if (Yii::$app->user->identity->user_type === 'admin')
                {
                    return $this->redirect(['admin/index']);
                }else
                {
                    return $this->redirect(['site/dashboard']);
                }
            }

            $session = Yii::$app->session;
            $session->setFlash('errorMessages', $user->getErrors());
        }

        $user->password = '';
        return $this->render('login', [
            'user' => $user,
        ]);
    }

    public function actionDashboard()
    {
        return $this->render('dashboard');
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
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $user = new ContactForm();
        if ($user->load(Yii::$app->request->post()) && $user->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $user,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function getUsersCreatedAt()
    {
        $users = User::find()
            ->select(['name', 'id'])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->asArray()
            ->all();

        return $users;
    }

    public function getAllUser()
    {
        $allUser = User::find()
            ->where(['id'=>ProfilePage::find()->select('id')])
            ->orderBy(new Expression('RAND()'))
            ->with('profilePage')
            //->asArray()
            ->all();
        return $allUser;
    }

    public function actionUserNames()
    {
        $userName = User::find()
            ->select(['name', 'id'])
            ->asArray()
            ->all();

        return $userName;
    }

}
