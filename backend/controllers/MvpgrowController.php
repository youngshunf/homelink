<?php

namespace backend\Controllers;

use Yii;
use common\models\Task;
use common\models\SearchTask;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\ImageUploader;
use yii\data\ActiveDataProvider;
use common\models\TaskResult;
use common\models\GrowthRec;
use yii\web\UploadedFile;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class MvpgrowController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex(){
        $user=yii::$app->user->identity;
        if($user->role_id==98){
            $dataProvider=new ActiveDataProvider([
                'query'=>GrowthRec::find()->andWhere(['pid'=>$user->id])->orderBy('created_at desc'),
            ]);
        }else{
            $dataProvider=new ActiveDataProvider([
                'query'=>GrowthRec::find()->orderBy('created_at desc'),
            ]);
        }
       
        return $this->render('index',[
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public function actionDeleteGrow($id){
        GrowthRec::findOne($id)->delete();
        yii::$app->getSession()->setFlash('success','删除成功');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    //导入成长记录
    public function actionImportGrowdata()
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
        $user=yii::$app->user->identity;
        $role_id=$user->role_id;
        $userid=$user->id;
    
        foreach ($sheetData as $record)
        {
            $irecord++;
            if($irecord<2){
                continue;
            }
            
            if(empty(trim($record['A']))){
                continue;
            }
            
            $growRec=new GrowthRec();
            if($role_id==98){
                $growRec->pid=$userid;
            }
            $growRec->work_number=trim($record['A']);
            
            $growRec->item_time=trim($record['B']);
            $growRec->items=trim($record['C']);
            $growRec->score=trim($record['D']);
            $growRec->classname=trim($record['E']);
            $growRec->created_at=time();
            if($growRec->save()){
                $result++;
            }
        }
        yii::$app->getSession()->setFlash('success','导入成功,本次导入'.$result.'条数据');
        return $this->redirect(yii::$app->request->referrer);
    }



}
