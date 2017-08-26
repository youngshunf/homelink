<?php

namespace backend\Controllers;

use Yii;
use common\models\ResultsData;
use common\models\SearchResultsData;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ResultRelation;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * DataController implements the CRUD actions for ResultsData model.
 */
class DataController extends Controller
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
     * Lists all ResultsData models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchResultsData();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ResultsData model.
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
     * Creates a new ResultsData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ResultsData();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionImportData()
    {
     $file=UploadedFile::getInstanceByName('importfrom');
        if(!isset($file)){
            yii::$app->getSession()->setFlash('error','文件上传失败,请重试');            
            return $this->redirect('index');
        } 
        if ($file->extension!='xls'&&$file->extension!='xlsx'){
            yii::$app->getSession()->setFlash('error','导入失败,请上传excel文件');           
            return $this->redirect('index');
        } 
        $path="../../upload/file/".date("Ymd").'/';
        if(!is_dir($path)){
            mkdir($path);
        }
        $fileName=date("YmdHis").rand(1000, 9999).'.'.$file->extension;
        $file->saveAs($path.$fileName);
        
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        if(!$PHPReader->canRead($path.$fileName)){
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if(!$PHPReader->canRead($path.$fileName)){
                echo 'open excel fail';
                return ;
            }
        }
        
        $PHPExcel=$PHPReader->load($path.$fileName);        
         /**读取excel文件中的第一个工作表*/
         $currentSheet = $PHPExcel->getSheet(0);
         /**取得最大的列号*/
         $allColumn = $currentSheet->getHighestColumn();
         /**取得一共有多少行*/
         $allRow = $currentSheet->getHighestRow();
         /**从第二行开始输出，因为excel表中第一行为列名*/
         $i=2;
         
         $result = 0;
         $irecord = 0;
         $yearMonth=date('Ym',strtotime($_POST['yearMonth']));
         //删除旧的数据
         ResultsData::deleteAll(['year_month'=>$yearMonth]);
         
         for($currentRow = 2;$currentRow <=$allRow;$currentRow++){
             $irecord++;
             $resultData=new ResultsData();
             $resultData->work_number=trim( $currentSheet->getCellByColumnAndRow(ord('A') - 65,$currentRow)->getValue());
             $resultData->name=trim( $currentSheet->getCellByColumnAndRow(ord('B') - 65,$currentRow)->getValue());
             $resultData->big_district=trim( $currentSheet->getCellByColumnAndRow(ord('C') - 65,$currentRow)->getValue());
             $resultData->business_district=trim( $currentSheet->getCellByColumnAndRow(ord('D') - 65,$currentRow)->getValue());
             $resultData->shop=trim( $currentSheet->getCellByColumnAndRow(ord('E') - 65,$currentRow)->getValue());
             $resultData->rank=trim( $currentSheet->getCellByColumnAndRow(ord('F') - 65,$currentRow)->getValue());
             $resultData->total_score=trim( $currentSheet->getCellByColumnAndRow(ord('G') - 65,$currentRow)->getValue());
             $resultData->results=trim( $currentSheet->getCellByColumnAndRow(ord('H') - 65,$currentRow)->getValue());
             $resultData->teach_score=trim( $currentSheet->getCellByColumnAndRow(ord('I') - 65,$currentRow)->getValue());
             $resultData->co_index=trim( $currentSheet->getCellByColumnAndRow(ord('J') - 65,$currentRow)->getValue());
             $resultData->honor_score=trim( $currentSheet->getCellByColumnAndRow(ord('K') - 65,$currentRow)->getValue());
             $resultData->youmi=trim( $currentSheet->getCellByColumnAndRow(ord('L') - 65,$currentRow)->getValue());
             $resultData->remark=trim( $currentSheet->getCellByColumnAndRow(ord('M') - 65,$currentRow)->getValue());
             $resultData->line_pr=trim( $currentSheet->getCellByColumnAndRow(ord('N') - 65,$currentRow)->getValue());
             $resultData->year_month=$yearMonth;
             $resultData->created_at=time();
             if($resultData->save()){
                 $result++;
             }
         }            
            yii::$app->getSession()->setFlash('success','导入成功,本次导入'.$result.'条数据');
            
            return $this->redirect('index');                  
    
    }

    public function actionImportRelation()
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
  
            //清空数据库
            $sql="TRUNCATE TABLE result_relation";
            yii::$app->db->createCommand($sql)->query();
    
            foreach ($sheetData as $record)
            {
                $irecord++;
                if($irecord<2){
                    continue;
                }
                 
              $resultRelation=new ResultRelation();
              $resultRelation->work_number=trim($record['A']);
              $resultRelation->up_work_number=trim($record['B']);
                $resultRelation->created_at=time();
                if($resultRelation->save()){
                    $result++;
                }
            }
    
            yii::$app->getSession()->setFlash('success','导入成功,本次导入'.$result.'条数据');
    
            return $this->redirect('index');
  
    }
    /**
     * Updates an existing ResultsData model.
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
     * Deletes an existing ResultsData model.
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
     * Finds the ResultsData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ResultsData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ResultsData::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
