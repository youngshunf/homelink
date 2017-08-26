<?php

namespace backend\controllers;

use Yii;
use common\models\Card;
use common\models\SearchCard;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CardComment;
use yii\data\ActiveDataProvider;

/**
 * CardController implements the CRUD actions for Card model.
 */
class CardController extends Controller
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
     * Lists all Card models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchCard();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionExportCard(){
        if(empty($_POST['startTime'])||empty($_POST['endTime'])){
         
            $model=Card::find()->orderBy('created_at desc')->all();
        }else{
            $startTime=strtotime($_POST['startTime']);
            $endTime=strtotime($_POST['endTime']);
            if($endTime<$startTime){
                yii::$app->getSession()->setFlash('error','结束时间不能小于开始时间');
                return $this->redirect(yii::$app->getRequest()->referrer);
            }
            $model=Card::find()->andWhere(" created_at >$startTime and created_at <=$endTime")->orderBy("created_at desc")->all();
        }
        
        if(empty($model)){
            yii::$app->getSession()->setFlash('error','该时间段没有数据');
            return $this->redirect(yii::$app->getRequest()->referrer);
        }
        
        $resultExcel=new \PHPExcel();
        $resultExcel->getActiveSheet()->setCellValue('A1','序号');
        $resultExcel->getActiveSheet()->setCellValue('B1','姓名');
        $resultExcel->getActiveSheet()->setCellValue('C1','电话');
        $resultExcel->getActiveSheet()->setCellValue('D1','邮箱');
        $resultExcel->getActiveSheet()->setCellValue('E1','大区');
        $resultExcel->getActiveSheet()->setCellValue('F1','店面');
        $resultExcel->getActiveSheet()->setCellValue('G1','负责商圈');
        $resultExcel->getActiveSheet()->setCellValue('H1','负责楼盘');
        $resultExcel->getActiveSheet()->setCellValue('I1','地址');
        $resultExcel->getActiveSheet()->setCellValue('J1','个人简介');
        $resultExcel->getActiveSheet()->setCellValue('K1','星级');
        $resultExcel->getActiveSheet()->setCellValue('L1','评论');
        $i=2;
        foreach ($model as $k=>$v){
            $resultExcel->getActiveSheet()->setCellValue('A'.$i,$k+1);
            $resultExcel->getActiveSheet()->setCellValue('B'.$i,$v->name);
            $resultExcel->getActiveSheet()->setCellValue('C'.$i,$v->mobile);
            $resultExcel->getActiveSheet()->setCellValue('D'.$i,$v->email);
            $resultExcel->getActiveSheet()->setCellValue('E'.$i,$v->district);
            $resultExcel->getActiveSheet()->setCellValue('F'.$i,$v->shop);
            $resultExcel->getActiveSheet()->setCellValue('G'.$i,$v->business_circle);
            $resultExcel->getActiveSheet()->setCellValue('H'.$i,$v->building);
            $resultExcel->getActiveSheet()->setCellValue('I'.$i,$v->address);
            $resultExcel->getActiveSheet()->setCellValue('J'.$i,$v->sign);
            $resultExcel->getActiveSheet()->setCellValue('K'.$i,$v->score);
            
           /*   $score=CardComment::find()->andWhere(['card_id'=>$v->card_id])->average("score");
            $resultExcel->getActiveSheet()->setCellValue('K'.$i,$score);  */
            
            $cardComment=CardComment::findAll(['card_id'=>$v->card_id]);
            $cardStr="";
            foreach ($cardComment as $key=>$item){
                $cardStr .= ($key+1).','.$item->content.";\n";
            }           
            $resultExcel->getActiveSheet()->setCellValue('L'.$i,$cardStr);
            $i++;
        }
     
        //设置导出文件名
        $outputFileName ="名片导出".date('Y-m-d',time()).'.xls';
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
     * Displays a single Card model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $commentData=new ActiveDataProvider([
            'query'=>CardComment::find()->andWhere(['card_id'=>$id])->orderBy('created_at desc'),
            'pagination'=>[
                'pagesize'=>'10'
            ]
        ]);
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'commentData'=>$commentData
        ]);
    }

    /**
     * Creates a new Card model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Card();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->card_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Card model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->card_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Card model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionDeleteComment($id){
       $cardComment= CardComment::findOne($id);
        $cardId=$cardComment->card_id;
        $cardComment->delete();
        return $this->redirect(['view','id'=>$cardId]);
        
    }

    /**
     * Finds the Card model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Card the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Card::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
