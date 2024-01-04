<?php

namespace app\controllers;

use app\models\ProfilePage;
use app\models\ProfileSearch;
use app\models\VisitModel;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\UploadedFile;
use yii\httpclient\Client;
use yii\helpers\ArrayHelper;
use yii\db\Query;


/**
 * ProfileController implements the CRUD actions for ProfilePage model.
 */
class ProfileController extends Controller
{



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

    /**
     * Lists all ProfilePage models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ProfilePage::find()
                ->joinWith('user'),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'profile_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProfilePage model.
     * @param int $profile_id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($profile_id)
    {
        // Hier wird der Visit Record hinzugefügt
        $userId = Yii::$app->user->id;
        VisitModel::recordVisit($profile_id, $userId);
        // Zählt die Anzahl der Einträge für das Profil
        $visitCount = VisitModel::find()->where(['profile_id'=>$profile_id])->count();

        // Schickt die gesammelten Daten an das View von Profil
        return $this->render('view', [
            'model' => $this->findModel($profile_id),
            'visitCount'=> $visitCount,
        ]);
    }

    /**
     * Creates a new ProfilePage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ProfilePage();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'profile_id' => $model->profile_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProfilePage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $profile_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($profile_id)
    {
        $model = $this->findModel($profile_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if (isset($model->imageFile)) {
                if ($model->upload()) {
                    return $this->redirect(['view', 'profile_id' => $model->profile_id]);
                }
            } else {
                return $this->redirect(['view', 'profile_id' => $model->profile_id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpload($profile_id)
    {
        $model = $this->findModel($profile_id);

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                return $this->redirect(['view', 'profile_id' => $model->profile_id]);
            }
            else {
                Yii::$app->session->setFlash('error', 'Das Hochladen der Datei ist fehlgeschlagen.');
            }
        }

        return $this->render('upload', ['model' => $model]);
    }

    /**
     * Deletes an existing ProfilePage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $profile_id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($profile_id)
    {
        $this->findModel($profile_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProfilePage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $profile_id
     * @return ProfilePage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($profile_id)
    {
        if (($model = ProfilePage::findOne(['profile_id' => $profile_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSearch($profile_id)
    {

        $searchModel = new ProfileSearch();
        $dataProvider = $searchModel->search($profile_id);
        return $this->render('view', [
            'searchModel'=> $searchModel,
            'dataProvider'=> $dataProvider,
        ]);
    }

}
