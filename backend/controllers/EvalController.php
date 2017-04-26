<?php

namespace backend\Controllers;

use Yii;
use common\models\Question;
use common\models\SearchQuestion;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Option;
use yii\data\ActiveDataProvider;
use common\models\Evaluation;
use common\models\EvalRelation;
use yii\web\UploadedFile;
use common\models\Answer;
use common\models\CommonUtil;

/**
 * EvalController implements the CRUD actions for Question model.
 */
class EvalController extends Controller
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
     * Lists all Question models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchQuestion();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);    
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Question model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $optData=new ActiveDataProvider([
            'query'=>Option::find()->andWhere(['qid'=>$id]),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'optData'=>$optData
        ]);
    }
    
    //查看评价结果
    public function actionViewResult($id)
    {
        $data=new ActiveDataProvider([
            'query'=>Evaluation::find()->andWhere(['qid'=>$id]),
            'pagination'=>[
                'pagesize'=>10
            ]
        ]);
        
        return $this->render('view-result', [
            'data' => $data,
           'model'=>$this->findModel($id)
        ]);
    }
    
    public function actionEvalDelete($id){
        $evaluation=Evaluation::findOne($id);
        Answer::deleteAll(['work_number'=>$evaluation->work_number,'eval_work_number'=>$evaluation->eval_work_number]);
        $qid=$evaluation->qid;
       $evaluation->delete();
       yii::$app->getSession()->addFlash('success','删除成功!');
        return $this->redirect(['view-result','id'=>$qid]);
    }
    
    public function actionEvalView($id){
        $evaluation=Evaluation::findOne($id);
        $option=Option::findAll(['qid'=>$evaluation->qid]);
        $question=Question::findOne($evaluation->qid);
        return $this->render('eval-view',[
            'option'=>$option,
            'question'=>$question,
            'evaluation'=>$evaluation
        ]);
        
    }
    
    public function actionExportResult($qid){
        $question=Question::findOne(['qid'=>$qid]);
        $model=Evaluation::findAll(['qid'=>$qid]);
        if(empty($model)){
            yii::$app->getSession()->setFlash('error','没有数据哦!');
            return $this->redirect(yii::$app->getRequest()->referrer);
        }
    
        $resultExcel=new \PHPExcel();
        $resultExcel->getActiveSheet()->setCellValue('A1','序号');
        $resultExcel->getActiveSheet()->setCellValue('B1','评价人');
        $resultExcel->getActiveSheet()->setCellValue('C1','评价人工号');
        $resultExcel->getActiveSheet()->setCellValue('D1','被评价人');
        $resultExcel->getActiveSheet()->setCellValue('E1','被评价人工号');
        $resultExcel->getActiveSheet()->setCellValue('F1','评价内容');
        $resultExcel->getActiveSheet()->setCellValue('G1','评价时间');
       $options=Option::findAll(['qid'=>$qid]);
       $count=count($options);
       $col='G';
      foreach ($options as $k=>$v){
          $col++;
          $result =($k+1).'.【'.CommonUtil::getDescByValue('option', 'type', $v->type).'】'.$v->title;
          $resultExcel->getActiveSheet()->setCellValue($col.'1',$result);
      }
       /*   $resultExcel->getActiveSheet()->setCellValue('G1','竞聘店面');
        $resultExcel->getActiveSheet()->setCellValue('H1','报名时间');
        $resultExcel->getActiveSheet()->setCellValue('I1','是否签到');
        $resultExcel->getActiveSheet()->setCellValue('J1','签到时间');
        $resultExcel->getActiveSheet()->setCellValue('K1','签到人'); */
    
        $i=2;
        foreach ($model as $key=>$item){
            $resultExcel->getActiveSheet()->setCellValue('A'.$i,$key+1);
            $resultExcel->getActiveSheet()->setCellValue('B'.$i,empty($item['user'])?'':$item['user']['name']);
            $resultExcel->getActiveSheet()->setCellValue('C'.$i,empty($item['user'])?'':$item['user']['work_number']);
            $resultExcel->getActiveSheet()->setCellValue('D'.$i,empty($item['evalUser'])?'':$item['evalUser']['name']);
            $resultExcel->getActiveSheet()->setCellValue('E'.$i,empty($item['evalUser'])?'':$item['evalUser']['name']);
            $resultExcel->getActiveSheet()->setCellValue('F'.$i,$item['question']['title']);
            $resultExcel->getActiveSheet()->setCellValue('G'.$i,CommonUtil::fomatTime($item->created_at));
            $col='G';
            foreach ($options as $k=>$v){
                $col++;
                $answer=Answer::find()->select('answer')->andWhere(['work_number'=>$item->work_number,'eval_work_number'=>$item->eval_work_number,'qid'=>$v->qid,'oid'=>$v->oid])->one();
            
                if($v->type==3){
                    $result =$answer->answer;
                    $resultExcel->getActiveSheet()->setCellValue($col.$i,$result);
                }elseif ($v->type==0){
                      $optArr=json_decode($v->content,true); 
                      $result ='选项'.($answer['answer']+1) .'、'.$optArr[intval($answer['answer'])] ;
                      $resultExcel->getActiveSheet()->setCellValue($col.$i,$result);
                }elseif ($v->type==1){
                    $optArr=json_decode($v->content,true);
                    $answers=Answer::find()->select('answer')->andWhere(['work_number'=>$item->work_number,'eval_work_number'=>$item->eval_work_number,'qid'=>$v->qid,'oid'=>$v->oid])->all();
                       $result="";
                       foreach ($answers as $a){  
                     $result .= '选项'.($a['answer']+1) .'、'.$optArr[intval($a['answer'])].";";
                          }
                          $resultExcel->getActiveSheet()->setCellValue($col.$i,$result);
                 }else{
                      $result =$answer->answer==1?'对':'错';
                      $resultExcel->getActiveSheet()->setCellValue($col.$i,$result);
        }
                
            }
            
           $i++;
        }
         
        //设置导出文件名
        $outputFileName =$question->title."-评价结果".date('Y-m-d',time()).'.xls';
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
    

    //导入评价关系
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
            $sql="TRUNCATE TABLE eval_relation";
            yii::$app->db->createCommand($sql)->query();
    
            foreach ($sheetData as $record)
            {
                $irecord++;
                if($irecord<2){
                    continue;
                }
                 
                $evalRelation=new EvalRelation();
                $evalRelation->work_number=trim($record['A']);
                $evalRelation->up_work_number=trim($record['B']);
                $evalRelation->created_at=time();
                if($evalRelation->save()){
                    $result++;
                }
            }   
            yii::$app->getSession()->setFlash('success','导入成功,本次导入'.$result.'条数据');   
            return $this->redirect('index');      
    }
    /**
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Question();

        if ($model->load(Yii::$app->request->post()) ) {
            $model->created_at=time();
            if($model->save())
            return $this->redirect(['view', 'id' => $model->qid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->qid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionAddOption()
    {
        $qid=$_POST['qid'];
       $title=$_POST['title'];
       $type=intval($_POST['type']);
       $option=new Option();
       $option->qid=$qid;
       $option->title=$title;
       $option->type=$type;
       $option->created_at=time();
       if($option->type==0||$option->type==1){
            $optArr=$_POST['optArr'];
            $option->content=json_encode($optArr);
       }
       if($option->save()){
          return $this->redirect(['view','id'=>$qid]);
       }
    
   
        
    }
    
    public function actionOptionDelete($id){
        $opt=Option::findOne($id);
        $qid=$opt->qid;
        $opt->delete();
        return $this->redirect(['view','id'=>$qid]);
    }
    

    /**
     * Deletes an existing Question model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Option::deleteAll(['qid'=>$id]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
