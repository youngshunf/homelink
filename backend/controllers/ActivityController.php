<?php

namespace backend\Controllers;

use Yii;
use common\models\Activity;
use common\models\SearchActivity;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\Image;
use yii\data\ActiveDataProvider;
use common\models\ActivityRegister;
use common\models\CommonUtil;
use common\models\ImageUploader;
use yii\filters\AccessControl;
use common\models\ActivityStep;
use common\models\ActivityQuestion;

/**
 * ActivityController implements the CRUD actions for Activity model.
 */
class ActivityController extends Controller
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
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchActivity();
        $searchModel->typeFlag=2;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionNextStep($activity_id)
    {
        $model=Activity::findOne($activity_id);
        $maxSteps=ActivityStep::find()->andWhere(['activity_id'=>$activity_id])->count();
        if($model->current_step==1&&$model->current_status==0){
            $model->current_status=1;
            ActivityStep::updateAll(['status'=>1],['activity_id'=>$activity_id,'step'=>$model->current_step]);
            ActivityRegister::updateAll([
                'current_status'=>'1',
                'current_step'=>$model->current_step,
                'current_type'=>$model->current_type,
            ],['activity_id'=>$activity_id,'current_status'=>['0','1','2']]);
            $model->save();
        }elseif($maxSteps==$model->current_step && $model->current_status==1){
            $model->current_status=2;
            ActivityRegister::updateAll([
                'current_status'=>'2',
                'current_step'=>$model->current_step,
                'current_type'=>$model->current_type,
            ],['activity_id'=>$activity_id,'current_status'=>['0','1','2']]);
            ActivityStep::updateAll(['status'=>2],['activity_id'=>$activity_id,'step'=>$model->current_step]);
        }
        else{
            ActivityStep::updateAll(['status'=>2],['activity_id'=>$activity_id,'step'=>$model->current_step]);
            ActivityRegister::updateAll([
                'current_status'=>'2',
                'current_step'=>$model->current_step,
                'current_type'=>$model->current_type,
            ],['activity_id'=>$activity_id,'current_status'=>['0','1','2']]);
            $model->current_step +=1;
            ActivityStep::updateAll(['status'=>1],['activity_id'=>$activity_id,'step'=>$model->current_step]);
            ActivityRegister::updateAll([
                'current_status'=>'1',
                'current_step'=>$model->current_step,
                'current_type'=>$model->current_type,
            ],['activity_id'=>$activity_id,'current_status'=>['0','1','2']]);
            $model->current_status=1;
            $model->save();
        }
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionStepDeny()
    {
        $activity_id=@$_POST['activity_id'];
        $model=Activity::findOne($activity_id);
        $keys=@$_POST['keys'];
        
        foreach ($keys as $v){
           $register=ActivityRegister::findOne(['register_id'=>$v,'activity_id'=>$activity_id]);
           $register->current_step=$model->current_step;
           $register->current_type=$model->current_type;
           $register->current_status=99;
           $register->updated_at=time();
           $register->save();
        }
       
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionStepPass()
    {
        $activity_id=@$_POST['activity_id'];
        $model=ActivityStep::findOne($activity_id);
        $keys=@$_POST['keys'];
        foreach ($keys as $v){
            $register=ActivityRegister::findOne(['id'=>$v,'activity_id'=>$activity_id]);
            $register->current_step=$model->current_step;
            $register->current_type=$model->current_type;
            $register->current_status=2;
            $register->updated_at=time();
            $register->save();
        }
        
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionExportRegister($activity_id){
        $activity=Activity::findOne(['activity_id'=>$activity_id]);
        $model=ActivityRegister::findAll(['activity_id'=>$activity_id]);
        if(empty($model)){
            yii::$app->getSession()->setFlash('error','还没有人报名,没有数据哦!');
            return $this->redirect(yii::$app->getRequest()->referrer);
        }
    
        $resultExcel=new \PHPExcel();
        $resultExcel->getActiveSheet()->setCellValue('A1','序号');
        $resultExcel->getActiveSheet()->setCellValue('B1','工号');
        $resultExcel->getActiveSheet()->setCellValue('C1','姓名');
        $resultExcel->getActiveSheet()->setCellValue('D1','电话');
        $resultExcel->getActiveSheet()->setCellValue('E1','邮箱');
        $resultExcel->getActiveSheet()->setCellValue('F1','微信');
        $resultExcel->getActiveSheet()->setCellValue('G1','竞聘店面');
        $resultExcel->getActiveSheet()->setCellValue('H1','报名时间');
        $resultExcel->getActiveSheet()->setCellValue('I1','是否签到');
        $resultExcel->getActiveSheet()->setCellValue('J1','签到时间');
        $resultExcel->getActiveSheet()->setCellValue('K1','签到人');
        $question=$activity->question;
        if(!empty($question)){
            $question=json_decode($question,true);
            foreach ($question as $k=>$v){
                $resultExcel->getActiveSheet()->setCellValue(chr(76+$k).'1',$v['name']);
            }
        }
 
        $i=2;
        foreach ($model as $k=>$v){
            $resultExcel->getActiveSheet()->setCellValue('A'.$i,$k+1);
            $resultExcel->getActiveSheet()->setCellValue('B'.$i,$v->work_number);
            $resultExcel->getActiveSheet()->setCellValue('C'.$i,$v->name);
            $resultExcel->getActiveSheet()->setCellValue('D'.$i,$v->mobile);
            $resultExcel->getActiveSheet()->setCellValue('E'.$i,$v->email);
            $resultExcel->getActiveSheet()->setCellValue('F'.$i,$v->weixin);
            $resultExcel->getActiveSheet()->setCellValue('G'.$i,$v->sign_shop);
            $resultExcel->getActiveSheet()->setCellValue('H'.$i,CommonUtil::fomatTime($v->created_at));
            $resultExcel->getActiveSheet()->setCellValue('I'.$i,$v->is_sign==0?"否":"是");
            $resultExcel->getActiveSheet()->setCellValue('J'.$i,CommonUtil::fomatTime($v->sign_time));
            $resultExcel->getActiveSheet()->setCellValue('K'.$i,$v['manager']['real_name']);
            $answer=$v->answer;
            if(!empty($answer)){
                $answer=json_decode($answer,true);
                foreach ($answer as $m=>$n){
                    $resultExcel->getActiveSheet()->setCellValue(chr(76+$m).$i,@$n['value']);
                }
            }
    
            $i++;
        }
         
        //设置导出文件名
        $outputFileName =$activity->title."-报名结果".date('Y-m-d',time()).'.xls';
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
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $dataProvider=new ActiveDataProvider([
            'query'=>ActivityRegister::find()->andWhere(['activity_id'=>$id])->orderBy('created_at desc'),
            'pagination'=>[
                'pagesize'=>30
            ]
            
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionViewAnswer($id)
    {
        $model=ActivityRegister::findOne($id);
        return $this->render('view-answer', [
            'model' => $model,
        ]);
    }
    
    public function actionEditQuestion($activity_id){
        $model=Activity::findOne($activity_id);
        $question='[]';
        if(!empty($model->question)){
            $question=$model->question;
            
        }
        return $this->render('edit-question', [
            'model' => $model,
            'question'=>$question
        ]);
        
    }
    
    public function actionSubmitQuestion(){
        $activity_id=$_POST['activity_id'];
        $content=@$_POST['content'];
        $activity=Activity::findOne($activity_id);
        $activity->question=json_encode($content);
        $activity->updated_at=time();
        if(!$activity->save()){
            return 'fail';
        }
        
        return $this->redirect(['view','id'=>$activity_id]);
        
    }
    

    /**
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Activity();

        if ($model->load(Yii::$app->request->post())  ) {
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $model->sign_end_time=strtotime($model->sign_end_time);
            $model->sign_start_time=strtotime($model->sign_start_time);
            $model->year_month=date("Ym",$model->start_time);
            $user=yii::$app->user->identity;
            if($user->role_id==98){
                $model->pid=$user->id;
            }
            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
                ImageUploader::uploadPhotoThumb('photoThumb', $model->path, $model->photo);
            }
                   
            $model->content=@$_POST['activity-content'];
            $model->created_at=time();
            
            if(!$model->save()){
                yii::$app->getSession()->setFlash('success','活动发布失败,请重试!');
            }
            $stepTitle=@$_POST['steptitle'];
            $stepType=@$_POST['steptype'];
            $stepScore=@$_POST['stepscore'];
            $stepContent=@$_POST['stepcontent'];
            $denydesc=@$_POST['denydesc'];
            foreach ($stepTitle as $k=>$v){
                $step=new ActivityStep();
                $step->activity_id=$model->activity_id;
                $step->step=$k+1;
                $step->title=$v;
                $step->type=$stepType[$k];
                $step->score=$stepScore[$k];
                $step->content=$stepContent[$k];
                $step->deny_desc=$denydesc[$k];
                $step->created_at=time();
                if(!$step->save()){
                    yii::$app->getSession()->setFlash('success','活动发布失败,请重试!');
                }
            }
            return $this->redirect(['view', 'id' => $model->activity_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Activity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
       $model->start_time=CommonUtil::fomatTime($model->start_time);
       $model->end_time=CommonUtil::fomatTime($model->end_time);
       $model->sign_end_time=CommonUtil::fomatTime($model->sign_end_time);
       $model->sign_start_time=CommonUtil::fomatTime($model->sign_start_time);
       
        if ($model->load(Yii::$app->request->post()) ) {
            $model->start_time=strtotime($model->start_time);
            $model->end_time=strtotime($model->end_time);
            $model->sign_end_time=strtotime($model->sign_end_time);
            $model->sign_start_time=strtotime($model->sign_start_time);
            $model->year_month=date("Ym",$model->start_time);

            $photo=ImageUploader::uploadByName('photo');
            if($photo){
                $model->path=$photo['path'];
                $model->photo=$photo['photo'];
            }
            
            $model->content=@$_POST['activity-content'];
            $model->updated_at=time();
            
            if(!$model->save()){
                yii::$app->getSession()->setFlash('success','活动更新失败,请重试!');
            }
            $stepTitle=@$_POST['steptitle'];
            $stepType=@$_POST['steptype'];
            $stepScore=@$_POST['stepscore'];
            $stepContent=@$_POST['stepcontent'];
            $denydesc=@$_POST['denydesc'];
            ActivityStep::deleteAll(['activity_id'=>$model->activity_id]);
            foreach ($stepTitle as $k=>$v){
                if(empty($v)){
                    continue;
                }
                $step=new ActivityStep();
                $step->activity_id=$model->activity_id;
                $step->step=$k+1;
                $step->title=$v;
                $step->type=$stepType[$k];
                $step->score=$stepScore[$k];
                $step->content=$stepContent[$k];
                $step->deny_desc=$denydesc[$k];
                $step->created_at=time();
                if(!$step->save()){
                    yii::$app->getSession()->setFlash('success','活动发布失败,请重试!');
                }
            }
            return $this->redirect(['view', 'id' => $model->activity_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    
    public function actionImportStepstatus()
    {
        $file=UploadedFile::getInstanceByName('file');
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
        $activity_id=@$_POST['activity_id'];
            
        foreach ($sheetData as $k=>$record)
        {
            if($k<2){
                continue;
            }
            if(empty($record['A'])){
                continue;
            }
            $work_number=trim($record['A']);
            $status=trim($record['B']);
            $step=ActivityRegister::findOne(['work_number'=>$work_number,'activity_id'=>$activity_id]);
            if(!empty($step)){
                if($status=='通过'){
                    $step->current_status=2;
                }else{
                    $step->current_status=99;
                }
                if($step->save()){
                    $result++;
                }
            }
                
            }
            
            
        yii::$app->getSession()->setFlash('success','导入成功,本次导入'.$result.'条数据');
        return $this->redirect(yii::$app->request->referrer);
    }
    /**
     * Deletes an existing Activity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionTop($id)
    {
        $model=$this->findModel($id);
        if($model->is_top==0){
            $model->is_top=1;
        }else{
            $model->is_top=0;
        }
        if($model->save()){
            yii::$app->getSession()->setFlash('success','操作成功!');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Activity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Activity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
