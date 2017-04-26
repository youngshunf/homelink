<?php

namespace backend\controllers;

use Yii;
use common\models\InterviewResult;
use common\models\SearchInterviewResult;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * InterviewResultController implements the CRUD actions for InterviewResult model.
 */
class InterviewResultController extends Controller
{
    public $enableCsrfValidation = false;
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
     * Lists all InterviewResult models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchInterviewResult();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    //导入面试结果
    public function actionImportData()
    {
        $file=UploadedFile::getInstanceByName('relation');
        if(!isset($file)){
            yii::$app->getSession()->setFlash('error','文件上传失败,请重试');
            return $this->redirect('index');
        }
        if ($file->extension!='xls'&&$file->extension!='xlsx'){
            yii::$app->getSession()->setFlash('error','导入失败,请上传excel文件');
            return $this->redirect('index');
        }
        $objPHPExcel = \PHPExcel_IOFactory::load($file->tempName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,true,true);
    
        $result = 0;
        $irecord = 0;
    
    
        foreach ($sheetData as $record)
        {
            $irecord++;
            if($irecord<2){
                continue;
            }
            $interviewResult=InterviewResult::findOne(['id_code'=>trim($record['B'])]);
            if(empty($interviewResult)){
                $interviewResult=new InterviewResult();
                $interviewResult->created_at=time();
            }
             $interviewResult->name=trim($record['A']);
             $interviewResult->id_code=trim($record['B']);
             $interviewResult->level=trim($record['C']);
             $interviewResult->rec_work_number=trim($record['D']);
             $interviewResult->rec_name=trim($record['E']);
             $interviewResult->interview_time=trim($record['F']);
             $interviewResult->interview_result=trim($record['G']);
             $interviewResult->train_result=trim($record['H']);
             $interviewResult->status=trim($record['I']);
             $interviewResult->remark=trim($record['J']);
            if($interviewResult->save()){
                $result++;
            }
        }
        yii::$app->getSession()->setFlash('success','导入成功,本次导入'.$result.'条数据');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionExportData(){
            $model=InterviewResult::find()->orderBy("created_at desc")->all();
        
    
        if(empty($model)){
            yii::$app->getSession()->setFlash('error','没有数据哦!');
            return $this->redirect(yii::$app->getRequest()->referrer);
        }
    
        $resultExcel=new \PHPExcel();
        $resultExcel->getActiveSheet()->setCellValue('A1','序号');
        $resultExcel->getActiveSheet()->setCellValue('B1','姓名');
        $resultExcel->getActiveSheet()->setCellValue('C1','身份证号');
        $resultExcel->getActiveSheet()->setCellValue('D1','入职定级');
        $resultExcel->getActiveSheet()->setCellValue('E1','推荐人系统号');
        $resultExcel->getActiveSheet()->setCellValue('F1','推荐人姓名');
        $resultExcel->getActiveSheet()->setCellValue('G1','面试时间');
        $resultExcel->getActiveSheet()->setCellValue('H1','面试结果');
        $resultExcel->getActiveSheet()->setCellValue('I1','培训结果');
        $resultExcel->getActiveSheet()->setCellValue('J1','入职状态');
        $resultExcel->getActiveSheet()->setCellValue('K1','备注');
        $i=2;
        foreach ($model as $k=>$v){
            $resultExcel->getActiveSheet()->setCellValue('A'.$i,$k+1);
            $resultExcel->getActiveSheet()->setCellValue('B'.$i,$v->name);
            $resultExcel->getActiveSheet()->setCellValue('C'.$i,$v->id_code);
            $resultExcel->getActiveSheet()->setCellValue('D'.$i,$v->level);
            $resultExcel->getActiveSheet()->setCellValue('E'.$i,$v->rec_work_number);
            $resultExcel->getActiveSheet()->setCellValue('F'.$i,$v->rec_name);
            $resultExcel->getActiveSheet()->setCellValue('G'.$i,$v->interview_time);
            $resultExcel->getActiveSheet()->setCellValue('H'.$i,$v->interview_result);
            $resultExcel->getActiveSheet()->setCellValue('I'.$i,$v->train_result);
            $resultExcel->getActiveSheet()->setCellValue('J'.$i,$v->status);
            $resultExcel->getActiveSheet()->setCellValue('K'.$i,$v->remark);
            $i++;
        }
         
        //设置导出文件名
        $outputFileName ="面试结果".date('Y-m-d',time()).'.xls';
        $xlsWriter = new \PHPExcel_Writer_Excel5($resultExcel);
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outputFileName.'"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
    
        $xlsWriter->save( "php://output" );
    
    }

    /**
     * Displays a single InterviewResult model.
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
     * Creates a new InterviewResult model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InterviewResult();

        if ($model->load(Yii::$app->request->post()) ) {
            $model->created_at=time();
            if($model->save())
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing InterviewResult model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing InterviewResult model.
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
     * Finds the InterviewResult model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InterviewResult the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InterviewResult::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
