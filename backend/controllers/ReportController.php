<?php

namespace backend\controllers;

use Yii;
use common\models\Report;
use common\models\SearchReport;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ReportController implements the CRUD actions for Report model.
 */
class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Report models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchReport();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Report model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Report model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Report();

        if ($model->load(Yii::$app->request->post()) ) {
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $model->report_time=strtotime($model->report_time);
            $model->desc=@$_POST['desc'];
            $model->created_at=time();
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                yii::$app->getSession()->setFlash('error','新增失败!');
            }
            
        } 
        
            return $this->render('create', [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing Report model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $model->report_time=strtotime($model->report_time);
            $model->created_at=time();
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                yii::$app->getSession()->setFlash('error','新增失败!');
            }
        } 
            return $this->render('update', [
                'model' => $model,
            ]);
        
    }

    /**
     * Deletes an existing Report model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Report model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Report the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Report::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
