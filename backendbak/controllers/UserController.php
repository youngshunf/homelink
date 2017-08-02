<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\models\ResetForm;
use common\models\CommonUtil;
use Faker;
use common\models\UserRelation;
use common\models\AwardTotal;
use common\models\AwardMoney;
use common\models\AwardCoin;
use common\models\AwardPoints;
use common\models\AwardCommonUtil;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\UserAccount;
use common\models\UserPosition;
use common\models\RecommendAward;
use backend\models\AdminUser;
use common\models\AuthUser;
use common\models\SearchUser;
use yii\web\UploadedFile;
use common\models\UserGroup;
use common\models\Template;
use common\models\TemplateData;
use common\models\WeChatTemplate;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
//             'verbs' => [
//                 'class' => VerbFilter::className(),
//                 'actions' => [
//                     'delete' => ['post'],
//                 ],
//             ],
        ];
    }

    public function beforeAction($action){        
        $this->layout='@app/views/layouts/user_layout.php';
        return parent::beforeAction($action);
    }
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
          $searchModel = new SearchUser();
          $searchModel->is_auth=1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->layout='@app/views/layouts/user_layout.php';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionManager()
    {
       
        $dataProvider =new ActiveDataProvider([
            'query'=>AdminUser::find()->orderBy('role_id desc')
        ]);
        return $this->render('manager', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
    public function actionSignManager($id)
    {
        $model=$this->findModel($id);
        $name=$model->real_name;
        if($model->is_sign_manager==0){
            $model->is_sign_manager=1;
            $model->save();
            yii::$app->getSession()->addFlash("success","'$name'已设置为签到管理员");
        }else{
            $model->is_sign_manager=0;
            $model->save();
            yii::$app->getSession()->addFlash("success","已取消'$name'的签到管理员权限");
        }
        
        return $this->redirect('index');
   
  
    }
    
    public function actionExportUser(){
        if(empty($_POST['startTime'])||empty($_POST['endTime'])){
             
            $model=User::find()->andWhere(['is_auth'=>1])->orderBy('created_at desc')->all();
        }else{
            $startTime=strtotime($_POST['startTime']);
            $endTime=strtotime($_POST['endTime']);
            if($endTime<$startTime){
                yii::$app->getSession()->setFlash('error','结束时间不能小于开始时间');
                return $this->redirect(yii::$app->getRequest()->referrer);
            }
            $model=User::find()->andWhere("is_auth=1 and created_at >$startTime and created_at <=$endTime")->orderBy("created_at desc")->all();
        }
    
        if(empty($model)){
            yii::$app->getSession()->setFlash('error','该时间段没有数据');
            return $this->redirect(yii::$app->getRequest()->referrer);
        }
    
        $resultExcel=new \PHPExcel();
        $resultExcel->getActiveSheet()->setCellValue('A1','序号');
        $resultExcel->getActiveSheet()->setCellValue('B1','姓名');
        $resultExcel->getActiveSheet()->setCellValue('C1','工号');
        $resultExcel->getActiveSheet()->setCellValue('D1','昵称');
        $resultExcel->getActiveSheet()->setCellValue('E1','性别');
        $resultExcel->getActiveSheet()->setCellValue('F1','省份');
        $resultExcel->getActiveSheet()->setCellValue('G1','城市');
        $resultExcel->getActiveSheet()->setCellValue('H1','电话');
        $resultExcel->getActiveSheet()->setCellValue('I1','邮箱');
        $resultExcel->getActiveSheet()->setCellValue('J1','运营大区');
        $resultExcel->getActiveSheet()->setCellValue('K1','业务区域');
        $resultExcel->getActiveSheet()->setCellValue('L1','负责店面');
        $i=2;
        foreach ($model as $k=>$v){
            $resultExcel->getActiveSheet()->setCellValue('A'.$i,$k+1);
            $resultExcel->getActiveSheet()->setCellValue('B'.$i,$v->real_name);
            $resultExcel->getActiveSheet()->setCellValue('C'.$i,$v->work_number);
            $resultExcel->getActiveSheet()->setCellValue('D'.$i,$v->nick);
            $resultExcel->getActiveSheet()->setCellValue('E'.$i,CommonUtil::getDescByValue('user', 'sex', $v->sex));
            $resultExcel->getActiveSheet()->setCellValue('F'.$i,$v->province);
            $resultExcel->getActiveSheet()->setCellValue('G'.$i,$v->city);
            $resultExcel->getActiveSheet()->setCellValue('H'.$i,$v->mobile);
            $resultExcel->getActiveSheet()->setCellValue('I'.$i,$v->email);
            $resultExcel->getActiveSheet()->setCellValue('J'.$i,$v->big_district);
            $resultExcel->getActiveSheet()->setCellValue('K'.$i,$v->business_district);
            $resultExcel->getActiveSheet()->setCellValue('L'.$i,$v->shop);
            $i++;
        }
        
        //设置导出文件名
        $outputFileName ="已验证用户".date('Y-m-d',time()).'.xls';
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
    
    public function  actionGroup(){
        $dataProvider=new ActiveDataProvider([
            'query'=>UserGroup::find()->orderBy('created_at desc'),
        ]);
        $this->layout='@app/views/layouts/user_layout.php';
        return $this->render('group',['dataProvider'=>$dataProvider]);
    }
    
    public function actionCreateGroup(){
        $model=new UserGroup();
        $model->user_guid=yii::$app->user->identity->user_guid;
        $model->group_name=$_POST['group-name'];
        $model->created_at=time();
        if($model->save()){
            yii::$app->getSession()->setFlash('success','分组'.$model->group_name.'创建成功');
        }else{
            yii::$app->getSession()->setFlash('error','分组创建失败!');
        }
        
        return $this->redirect('group');
    }
    
    public function actionViewGroup($id){
        $dataProvider=new ActiveDataProvider([
            'query'=>User::find()->andWhere(['group_id'=>$id]),           
        ]);
        
        $usersData=new ActiveDataProvider([
            'query'=>User::find()->andWhere(['group_id'=>0,'is_auth'=>1]),
            'pagination'=>[
                'pagesize'=>20
            ]
        ]);
        
        $model=UserGroup::findOne($id);
        $this->layout='@app/views/layouts/user_layout.php';
        return $this->render('view-group',[
            'model'=>$model,
            'dataProvider'=>$dataProvider,
            'userData'=>$usersData
            
        ]);
    }
    
    public function actionAddGroupUser(){
        $group_id=$_POST['groupid'];
        $users=$_POST['userid'];
        $i=0;
        foreach ($users as $k =>$v){
            $model=User::findOne(['id'=>$v]);
            $model->group_id=$group_id;
            $model->updated_at=time();
            if(!$model->save()){
                yii::$app->getSession()->setFlash('error','添加用户分组失败');               
            }
            $i++;
        }
        yii::$app->getSession()->setFlash('success','总共'.$i.'个用户添加分组成功!');
        return $this->redirect(['view-group','id'=>$group_id]);
    }
    
    public function actionDeleteGroupUser($id){
        $model=User::findOne($id);
        $group_id=$model->group_id;
        $model->group_id=0;
        if($model->save()){
            yii::$app->getSession()->setFlash('success','用户'.$model->real_name.'已从当前分组移除!');
        }else{
            yii::$app->getSession()->setFlash('error','用户'.$model->real_name.'从当前分组移除失败!');
        }
        
        return $this->redirect(['view-group','id'=>$group_id]);
    }
    
    public function actionNormal(){
   
        $searchModel = new SearchUser();
          $searchModel->is_auth=0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->layout='@app/views/layouts/user_layout.php';
        return $this->render('normal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        
    }
    
    public function  actionAuthUser(){
        $dataProvider=new ActiveDataProvider([
            'query' => AuthUser::find()->OrderBy("created_at desc"),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        
        $this->layout='@app/views/layouts/user_layout.php';
        return $this->render('auth-user', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionImportAuth(){
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
            $objPHPExcel = \PHPExcel_IOFactory::load($path.$fileName);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,true,true);
        
            $result = 0;
            $irecord = 0;
            //清空数据库
           $sql="TRUNCATE TABLE auth_user";
           yii::$app->db->createCommand($sql)->query();
            foreach ($sheetData as $record)
            {
                $irecord++;
              if($irecord<2){
                  continue;
              }
             
            $authUser=new AuthUser();
            $authUser->work_number=trim($record['A']);
            $authUser->name=trim($record['B']);
             $authUser->big_district=trim($record['C']);
             $authUser->business_district=trim($record['D']);
             $authUser->shop=trim($record['E']);
             $authUser->role_name=trim($record['F']);
             $authUser->up_work_number=trim($record['G']);
             $authUser->created_at=time();   	
            if($authUser->save()){
                $result++;
                
                $user=User::findOne(['work_number'=>$authUser->work_number,'is_auth'=>1]);
                if(!empty($user)){
                    $role=CommonUtil::getRoleId($authUser->role_name);
                    if($user->role_id!=$role){
                        $user->role_id=$role;
                        $user->updated_at=time();
                        $user->save();
                    }
                }                
            }       
            }
            
            yii::$app->getSession()->setFlash('success','导入成功,本次导入'.$result.'条数据');
            
            return $this->redirect('auth-user');                  
       
    }
    
    public function actionImportGroup(){
        $file=UploadedFile::getInstanceByName('group');
 
        if(!isset($file)){
            yii::$app->getSession()->setFlash('error','文件上传失败,请重试');
            return $this->redirect('group');
        }
        if ($file->extension!='xls'&&$file->extension!='xlsx'){
            yii::$app->getSession()->setFlash('error','导入失败,请上传excel文件');
            return $this->redirect('group');
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
        $objPHPExcel = \PHPExcel_IOFactory::load($path.$fileName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,false,true,true);
    
        $result = 0;
        $irecord = 0;

        foreach ($sheetData as $record)
        {
            $irecord++;
            if($irecord<2){
                continue;
            }
         $work_number=trim($record['A']);    
          $user=User::findOne(['work_number'=>$work_number,'is_auth'=>1]);
          if(!empty($user)){
              $user->group_id=trim($record['C']);
              $user->updated_at=time();
              if($user->save()){
                  $result++;
              }
          }
  
        }
    
        yii::$app->getSession()->setFlash('success','导入成功,本次成功对'.$result.'个用户进行分组');
    
        return $this->redirect('group');
         
    }
  
    
    
    
    //跳转至重置密码
    public function actionResetPassword($id){
    	$userArr = User::findOne(["id"=>$id]);
    	$reset = new ResetForm();
  		if ($reset->load(Yii::$app->request->post())) {
  			if ($reset->reset($userArr['user_guid'])) {
  				return $this->redirect('index');
  			}
    	} else {
    		return $this->render('reset', [
    			'model' => $reset,
    			'userArr' => $userArr,
    		]);
    	}
    }
    
    //导出excel
    
    /**
     * Displays a single User model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
 	
        return $this->render('view', [
            'model' => $this->findModel($id),

        ]);
    }
  
    public function actionViewAdmin($id)
    {
        $model=AdminUser::findOne($id);
        return $this->render('view-admin', [
            'model' => $model,
        ]);
    }
    
    public function actionUpdateAdmin($id)
    {
        $model=AdminUser::findOne($id);
        $model->setScenario('manager_update');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
            
        }elseif ($model->load(Yii::$app->request->post())){
            $model->user_guid=CommonUtil::createUuid();
            $model->password=md5($model->password);
            $model->created_at=time();
            if($model->save())
                return $this->redirect('manager');
        }
        return $this->render('update-admin', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminUser();
        $model->setScenario('manager_create');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
             
        }elseif ($model->load(Yii::$app->request->post())){
            $model->user_guid=CommonUtil::createUuid();
            $model->password=md5($model->password);
            $model->created_at=time();
            if($model->save())       
            return $this->redirect('manager');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    
    public function actionDeleteNormal($id)
    {
        $user= $this->findModel($id);
        $user->delete();
        return $this->redirect('normal');
        
    }

    public function actionTemplateMessage(){
        $dataProvider= new ActiveDataProvider([
            'query'=>Template::find()->orderBy('created_at desc'),
        ]);
        
        return $this->render('template-message',[
            'dataProvider'=>$dataProvider
        ]);
    }
    
    public  function actionSendTemplateMessage($id){
        $sendModel=new WeChatTemplate(yii::$app->params['appid'], yii::$app->params['appsecret']);
        $template=Template::findOne($id);
        $templateData=TemplateData::findAll(['tem_id'=>$id]);
        $data=array();
        foreach ($templateData as $v){
            $data[$v->key]=[
               "value"=>$v->value,
                "color"=>"#173177"
            ];
        }
        
        $users=User::findAll(['group_id'=>$template->group_id]);
        $result=0;
        foreach ($users as $user){
            $finalData=[
                "touser"=>$user->openid,
                "template_id"=>$template->template_id,
                "url"=>$template->url,
                "topcolor"=>"#FF0000",
                "data"=>$data
            ];
            $res=$sendModel->send_template_message($finalData);
         
            if($res['errmsg']=='ok'){
                $result++;
            }    
        }
        yii::$app->getSession()->setFlash('success','此次共成功发送'.$result."条模板消息");
        return $this->redirect(['view-template','id'=>$id]);
    }
    
    public function actionCreateTemplate(){
        $model=new Template();
        if($model->load(yii::$app->request->post())){
            $model->user_guid=yii::$app->user->identity->user_guid;
            $model->created_at=time();
            if ($model->save()){
            $data=$_POST['optArr'];  
             
            foreach ($data as $v){
                if(empty($v)){
                    continue;
                }
                $item=explode('.', $v);
                $templateData=new TemplateData();
                $templateData->tem_id=$model->id;
                $templateData->template_id=$model->template_id;
                $templateData->key=$item[0];
                $templateData->value=$item[1];
                $templateData->created_at=time();
                if(!$templateData->save()){
                    continue;
                }                
            }
            
            return $this->redirect(['view-template','id'=>$model->id]);
            }
        }else{
            $group=UserGroup::find()->orderBy('created_at desc')->all();
            return $this->render('create-template',[
                'model'=>$model,
                'group'=>$group
            ]);
        }
    }
    
    public function actionUpdateTemplate($id){
        $model=Template::findOne($id);
        if($model->load(yii::$app->request->post())){
            $model->user_guid=yii::$app->user->identity->user_guid;
            $model->created_at=time();
            if ($model->save()){
                $data=$_POST['optArr'];
          
                     TemplateData::deleteAll(['tem_id'=>$id]);
               
                foreach ($data as $v){
                    if(empty($v)){
                        continue;
                    }
                    $item=explode('.', $v);                
                    $templateData=new TemplateData();
                    $templateData->tem_id=$model->id;
                    $templateData->template_id=$model->template_id;
                    $templateData->key=$item[0];
                    $templateData->value=$item[1];
                    $templateData->created_at=time();
                    if(!$templateData->save()){
                        continue;
                    }
                }
    
                return $this->redirect(['view-template','id'=>$model->id]);
            }
        }else{
            $group=UserGroup::find()->all();
            $templateData=TemplateData::findAll(['tem_id'=>$id]);
            return $this->render('create-template',[
                'model'=>$model,
                'group'=>$group,
                'templateData'=>$templateData
            ]);
        }
    }
    
    
    
    public function actionViewTemplate($id){
        $model=Template::findOne($id);
        $templateData=TemplateData::findAll(['tem_id'=>$model->id]);
        return $this->render('view-template',[
            'model'=>$model,
            'templateData'=>$templateData
        ]);
    }
    
    public function actionDeleteTemplate($id){
        Template::findOne($id)->delete();
       return $this->redirect('template-message');
    }
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
