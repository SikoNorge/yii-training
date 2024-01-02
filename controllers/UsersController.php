<?php

namespace app\controllers;

use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * UserController implements the CRUD actions for User model.
 */
class UsersController extends Controller
{

    public $layout = 'admin';

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }



    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->user_type !== 'admin')
        {
            throw new ForbiddenHttpException('Du hast keine Berechtigung diese seite zu sehen');
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $user = new User();

        if ($this->request->isPost) {
            if ($user->load($this->request->post())) {
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
                if($user->save()){
                    return $this->redirect(['view', 'id' => $user->id]);

                }
            }
        } else {
            $user->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $user,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $request = $this->findModel($id);
        if ($this->request->isPost && $model->load($this->request->post())){
            if(Yii::$app->getSecurity()->validatePassword($model->password,$request->password) || $model->password == $request->password) {
                $model->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
            }
             if ($model->save()) {
                 return $this->redirect(['view', 'id' => $model->id]);
             }
    }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($user = User::findOne(['id' => $id])) !== null) {
            return $user;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Lists all User models.
     *
     * @return string
     */




}
