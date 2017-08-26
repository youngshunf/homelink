<?php

namespace backend\controllers;

use Yii;
use common\models\InterviewDistrict;
use common\models\SearchInterviewDistrict;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\User;
use backend\models\AdminUser;
use common\models\CommonUtil;
use yii\data\ActiveDataProvider;
use common\models\InterviewRegister;
use yii\filters\AccessControl;
use common\models\InterviewAddress;
use common\models\WeChatTemplate;
use common\models\InterviewData;

/**
 * InterviewController implements the CRUD actions for InterviewDistrict model.
 */
class InterviewSupervisorController extends Controller
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
        $this->layout='@app/views/layouts/isp_layout.php';
        return parent::beforeAction($action);
    }

    /**
     * Lists all InterviewDistrict models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user=yii::$app->user->identity;
        $yearMonth=date('Ym');
        $dataProvider=new ActiveDataProvider([
            'query'=>InterviewRegister::find()->andWhere(['district_code'=>$user->district_code,'year_month'=>$yearMonth])->orderBy('created_at desc')
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'yearMonth'=>$yearMonth
        ]);
    }
    
    public function actionSendSignupMessage(){
        $sendModel=new WeChatTemplate(yii::$app->params['appid'], yii::$app->params['appsecret']);
        $user=yii::$app->user->identity;
        $year_month=date('Ym');
        $interviewAdd=InterviewAddress::findOne(['district_code'=>$user->district_code,'year_month'=>$year_month]);
        if(empty($interviewAdd)){
            yii::$app->getSession()->setFlash('error','没有设置面试地址,请先设置面试地址再发送通知.');
            return $this->redirect(yii::$app->request->referrer);
        }
        $passUser=InterviewRegister::findAll(['district_code'=>$user->district_code,'year_month'=>$year_month,'signup_result'=>'1']);
        $result=0;
        foreach ($passUser as $u){
            $data=array();
            $data['first']=[
                "value"=>$u->name,
                "color"=>"#173177"
            ];
            $data['class']=[
                "value"=>'HM面试报名结果',
                "color"=>"#173177"
            ];
            $data['time']=[
                "value"=>CommonUtil::fomatHours($interviewAdd->time),
                "color"=>"#173177"
            ];
            $data['add']=[
                "value"=>$interviewAdd->address,
                "color"=>"#173177"
            ];
            $data['remark']=[
                "value"=>"经审核，您符合本次HM储备店经理竞聘的面试资格。请于".CommonUtil::fomatHours($interviewAdd->time)."到".($interviewAdd->address)."参加面试",
                "color"=>"#173177"
            ];
            $oUser=User::findOne(['work_number'=>$u->work_number]);
             if(!empty($oUser)){
            $finalData=[
                "touser"=>$oUser->openid,
                "template_id"=>'2vGCBVcba80h5jRKIo8anXyPJJNW6EVFRk5ikRx1n0w',
                "url"=>'#',
                "topcolor"=>"#FF0000",
                "data"=>$data
            ];
            $res=$sendModel->send_template_message($finalData);
             
            if($res['errmsg']=='ok'){
                $result++;
               
            }
            $u->is_send_signup=1;
            $u->save();
             }
             
        }
    
        $denyUser=InterviewRegister::findAll(['district_code'=>$user->district_code,'year_month'=>$year_month,'signup_result'=>'2']);
        foreach ($denyUser as $u){
            $data=array();
            $data['first']=[
                "value"=>$u->name,
                "color"=>"#173177"
            ];
            $data['class']=[
                "value"=>'HM面试报名结果',
                "color"=>"#173177"
            ];
            $data['time']=[
                "value"=>'无',
                "color"=>"#173177"
            ];
            $data['add']=[
                "value"=>'无',
                "color"=>"#173177"
            ];
            $data['remark']=[
                "value"=>"很抱歉，您不符合本次HM储备店经理竞聘的面试资格",
                "color"=>"#173177"
            ];
            $oUser=User::findOne(['work_number'=>$u->work_number]);
             if(!empty($oUser)){
            $finalData=[
                "touser"=>$oUser->openid,
                "template_id"=>'2vGCBVcba80h5jRKIo8anXyPJJNW6EVFRk5ikRx1n0w',
                "url"=>'#',
                "topcolor"=>"#FF0000",
                "data"=>$data
            ];
            $res=$sendModel->send_template_message($finalData);
             
            if($res['errmsg']=='ok'){
                $result++;
                
            }
            $u->is_send_signup=1;
            $u->save();
             }
        }
    
        yii::$app->getSession()->setFlash('success','此次共成功发送'.$result."条通知");
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionSendInterviewMessage(){
        $sendModel=new WeChatTemplate(yii::$app->params['appid'], yii::$app->params['appsecret']);
        $user=yii::$app->user->identity;
        $year_month=date('Ym');
        
        $passUser=InterviewRegister::findAll(['district_code'=>$user->district_code,'year_month'=>$year_month,'interview_result'=>'1']);
        $result=0;
        foreach ($passUser as $u){
            $data=array();
            $data['first']=[
                "value"=>'恭喜您通过了本次HM储备店经理的竞聘.',
                "color"=>"#173177"
            ];
            $data['keyword1']=[
                "value"=>'北京链家',
                "color"=>"#173177"
            ];
            $data['keyword2']=[
                "value"=>$u->name,
                "color"=>"#173177"
            ];
            $data['keyword3']=[
                "value"=>$u->district_name.'大区储备店经理',
                "color"=>"#173177"
            ];
            $data['keyword4']=[
                "value"=>'通过',
                "color"=>"#173177"
            ];
            $data['remark']=[
                "value"=>"",
                "color"=>"#173177"
            ];
            $oUser=User::findOne(['work_number'=>$u->work_number]);
             
            $finalData=[
                "touser"=>$oUser->openid,
                "template_id"=>'Gb8JnwOHvImLBZ1vOrVzqlrosTbIjFP-xHjMhnzkaNYm',
                "url"=>'#',
                "topcolor"=>"#FF0000",
                "data"=>$data
            ];
            $res=$sendModel->send_template_message($finalData);
             
            if($res['errmsg']=='ok'){
                $result++;
            }
            $u->is_send_result=1;
            $u->save();
        }
    
        $denyUser=InterviewRegister::findAll(['district_code'=>$user->district_code,'year_month'=>$year_month,'interview_result'=>'2']);
        foreach ($denyUser as $u){
            $data=array();
            $data['first']=[
                "value"=>'很抱歉，您未通过本次HM储备店经理的竞聘，希望您可以再接再厉',
                "color"=>"#173177"
            ];
            $data['keyword1']=[
                "value"=>'北京链家',
                "color"=>"#173177"
            ];
            $data['keyword2']=[
                "value"=>$u->name,
                "color"=>"#173177"
            ];
             $data['keyword3']=[
                "value"=>$u->district_name.'大区储备店经理',
                "color"=>"#173177"
            ];
            $data['keyword4']=[
                "value"=>'未通过',
                "color"=>"#173177"
            ];
            $data['remark']=[
                "value"=>"纵有疾风起，人生不言弃",
                "color"=>"#173177"
            ];
            $oUser=User::findOne(['work_number'=>$u->work_number]);
             
            $finalData=[
                "touser"=>$oUser->openid,
                "template_id"=>'Gb8JnwOHvImLBZ1vOrVzqlrosTbIjFP-xHjMhnzkaNYm',
                "url"=>'#',
                "topcolor"=>"#FF0000",
                "data"=>$data
            ];
            $res=$sendModel->send_template_message($finalData);
             
            if($res['errmsg']=='ok'){
                $result++;
                
            }
            $u->is_send_result=1;
            $u->save();
        }
    
        yii::$app->getSession()->setFlash('success','此次共成功发送'.$result."条通知消息");
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionInterviewAddress()
    {
        $user=yii::$app->user->identity;
        $yearMonth=date('Ym');
        $dataProvider=new ActiveDataProvider([
            'query'=>InterviewAddress::find()->andWhere(['district_code'=>$user->district_code])->orderBy('created_at desc')
        ]);
    
        return $this->render('interview-address', [
            'dataProvider' => $dataProvider,
            'yearMonth'=>$yearMonth
        ]);
    }

    /**
     * Displays a single InterviewDistrict model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model=$this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }
    

    public function actionSignupDeny(){
        $keys=$_POST['keys'];
        InterviewRegister::updateAll(['signup_result'=>'2'],['id'=>$keys]);
        return CommonUtil::success('success');
    }
    public function actionSignupPass(){
        $keys=$_POST['keys'];
        InterviewRegister::updateAll(['signup_result'=>'1'],['id'=>$keys]);
        return CommonUtil::success('success');
    }
    
    public function actionPass($id){
        InterviewRegister::updateAll(['interview_result'=>'1'],['id'=>$id]);
        yii::$app->getSession()->setFlash('success','操作成功!');
        return $this->redirect(yii::$app->request->referrer);
    }
    public function actionDeny(){
        $id=$_POST['id'];
        $remark=$_POST['remark'];
        $status=$_POST['status'];
        InterviewRegister::updateAll(['interview_result'=>$status,'remark'=>$remark],['id'=>$id]);
        yii::$app->getSession()->setFlash('success','操作成功!');
        return $this->redirect(yii::$app->request->referrer);
    }
    public function actionAppeal($id){
        InterviewRegister::updateAll(['is_appeal'=>'1'],['id'=>$id]);
        yii::$app->getSession()->setFlash('success','操作成功!');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    public function actionChangePassword(){
        return $this->render('change-password');
    }
    
    public function actionChangePasswordDo(){
        $user=yii::$app->user->identity;
        $oldPwd=$_POST['oldPwd'];
        if(md5($oldPwd)!=$user->password){
            yii::$app->getSession()->setFlash('eorror','操作失败,旧密码错误!');
            return $this->redirect(yii::$app->request->referrer);
        }
        $pwd1=$_POST['pwd1'];
        $pwd2=$_POST['pwd2'];
        if($pwd1!=$pwd2){
            yii::$app->getSession()->setFlash('eorror','操作失败,两次密码不一致!');
            return $this->redirect(yii::$app->request->referrer);
        }
        $user1=AdminUser::findOne(['id'=>yii::$app->user->identity->id]);
        $user1->password=md5($pwd1);
        if($user1->save()){
            yii::$app->getSession()->setFlash('success','密码修改成功!');
            return $this->redirect(yii::$app->request->referrer);
        }
        yii::$app->getSession()->setFlash('error','密码修改失败!');
        return $this->redirect(yii::$app->request->referrer);
    }
    
    
    public function actionCreate()
    {
        $user=yii::$app->user->identity;
        $model = new InterviewAddress();

        if ($model->load(Yii::$app->request->post())) {
            $model->district_code=$user->district_code;
            $model->district_name=$user->district_name;
            $model->year_month=date('Ym',strtotime($model->time));
            $address=InterviewAddress::findOne(['year_month'=>$model->year_month,'district_code'=>$model->district_code]);
            if(!empty($address)){
                yii::$app->getSession()->setFlash('error',$model->year_month.'已有面试地址,不能新增只能修改!');
                 return $this->redirect(yii::$app->request->referrer);
            }
            $model->time=strtotime($model->time);
            $model->created_at=time();
            if($model->save()){
                return $this->redirect(['interview-address', 'id' => $model->id]);
            }else{
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
           
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
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
    
    
            foreach ($sheetData as $k=>$record)
            {
                if($k<2){
                    continue;
                }
                if(empty($record['A'])){
                    continue;
                }
                $interviewDistrict=InterviewDistrict::findOne(['district_code'=>$record['A']]);
                if(empty($interviewDistrict)){
                    $interviewDistrict=new InterviewDistrict();
                    $interviewDistrict->created_at=time();
                }
                $interviewDistrict->district_code=$record['A'];
                $interviewDistrict->district_name=$record['B'];
                $interviewDistrict->assistant_number=$record['C'];
                $interviewDistrict->assistant_name=@$record['D'];
                $interviewDistrict->supervisor_number=$record['E'];
                $interviewDistrict->supervisor_name=@$record['F'];
                if($interviewDistrict->save()){
                    $user=User::findOne(['work_number'=>$interviewDistrict->assistant_number]);
                    if(!empty($user)){
                        $user->role_id=88;
                        $user->save();
                    }
                    $user=User::findOne(['work_number'=>$interviewDistrict->supervisor_number]);
                    if(!empty($user)){
                        $user->role_id=89;
                        $user->save();
                    }
                    $adminUser=AdminUser::findOne(['work_number'=>$interviewDistrict->assistant_number]);
                    if(empty($adminUser)){
                        $adminUser=new AdminUser();
                        $adminUser->user_guid=CommonUtil::createUuid();
                        $adminUser->username=$interviewDistrict->assistant_number;
                        $adminUser->password=md5('123456');
                        $adminUser->work_number=$interviewDistrict->assistant_number;
                    }
                    $adminUser->real_name='大区助理';
                    $adminUser->nick='大区助理';
                    $adminUser->district_code=$interviewDistrict->district_code;
                    $adminUser->role_id=88;
                    $adminUser->save();
                    
                    $adminUser=AdminUser::findOne(['work_number'=>$interviewDistrict->supervisor_number]);
                    if(empty($adminUser)){
                        $adminUser=new AdminUser();
                        $adminUser->user_guid=CommonUtil::createUuid();
                        $adminUser->username=$interviewDistrict->supervisor_number;
                        $adminUser->password=md5('123456');
                        $adminUser->work_number=$interviewDistrict->supervisor_number;
                    }
                    $adminUser->real_name='大区总监';
                    $adminUser->nick='大区总监';
                    $adminUser->district_code=$interviewDistrict->district_code;
                    $adminUser->role_id=89;
                    $adminUser->save();
                }
                $result++;
                
            }   
            yii::$app->getSession()->setFlash('success','导入成功,本次导入'.$result.'条数据');   
            return $this->redirect('index');      
    }

    public function actionExportRegister(){
        $yearMonth=date('Ym');
        $user=yii::$app->user->identity;
        $model=InterviewRegister::findAll(['district_code'=>$user->district_code,'year_month'=>$yearMonth]);
        if(empty($model)){
            yii::$app->getSession()->setFlash('error','还没有人报名,没有数据哦!');
            return $this->redirect(yii::$app->getRequest()->referrer);
        }
    
        $resultExcel=new \PHPExcel();
        $resultExcel->getActiveSheet()->setCellValue('A1','序号');
        $resultExcel->getActiveSheet()->setCellValue('B1','工号');
        $resultExcel->getActiveSheet()->setCellValue('C1','姓名');
        $resultExcel->getActiveSheet()->setCellValue('D1','级别');
        $resultExcel->getActiveSheet()->setCellValue('E1','面试大区');
        $resultExcel->getActiveSheet()->setCellValue('F1','面试结果');
        $resultExcel->getActiveSheet()->setCellValue('G1','面试评价');
        $resultExcel->getActiveSheet()->setCellValue('H1','联系方式');
        $resultExcel->getActiveSheet()->setCellValue('I1','营销区域');
        $resultExcel->getActiveSheet()->setCellValue('J1','业务区域');
        $resultExcel->getActiveSheet()->setCellValue('K1','门店');
        $resultExcel->getActiveSheet()->setCellValue('L1','年龄');
        $resultExcel->getActiveSheet()->setCellValue('M1','婚姻状况');
        $resultExcel->getActiveSheet()->setCellValue('N1','入职日期');
        $resultExcel->getActiveSheet()->setCellValue('O1','最高教育程度');
        $resultExcel->getActiveSheet()->setCellValue('P1','认证讲师');
        $resultExcel->getActiveSheet()->setCellValue('Q1','博学成绩');
        $resultExcel->getActiveSheet()->setCellValue('R1','精英社资格');
        $resultExcel->getActiveSheet()->setCellValue('S1','一年内红黄线');
        $resultExcel->getActiveSheet()->setCellValue('T1','一年内投诉');
        $resultExcel->getActiveSheet()->setCellValue('U1','半年业绩');
        $resultExcel->getActiveSheet()->setCellValue('V1','半年业绩大区排名');
        $resultExcel->getActiveSheet()->setCellValue('W1','合作单边比');
        $resultExcel->getActiveSheet()->setCellValue('X1','合作单边比大区排名');
        $resultExcel->getActiveSheet()->setCellValue('Y1','半年(房)');
        $resultExcel->getActiveSheet()->setCellValue('Z1','半年(客)');
        $resultExcel->getActiveSheet()->setCellValue('AA1','半年(带)');
        $i=2;
        foreach ($model as $k=>$v){
            $data=InterviewData::findOne(['work_number'=>$v->work_number,'year_month'=>$yearMonth]);
            if(empty($data)){
                continue;
            }
            $resultExcel->getActiveSheet()->setCellValue('A'.$i,$k+1);
            $resultExcel->getActiveSheet()->setCellValue('B'.$i,$v->work_number);
            $resultExcel->getActiveSheet()->setCellValue('C'.$i,$v->name);
            $resultExcel->getActiveSheet()->setCellValue('D'.$i,$data->level);
            $resultExcel->getActiveSheet()->setCellValue('E'.$i,$v->district_name);
            $resultExcel->getActiveSheet()->setCellValue('F'.$i,CommonUtil::getDescByValue('interview_register', 'interview_result', $v->interview_result));
            $resultExcel->getActiveSheet()->setCellValue('G'.$i,$v->remark);
            $resultExcel->getActiveSheet()->setCellValue('H'.$i,$v->mobile);
            $resultExcel->getActiveSheet()->setCellValue('I'.$i,"'".$data->sale_district."'");
            $resultExcel->getActiveSheet()->setCellValue('J'.$i,"'".$data->business_district."'");
            $resultExcel->getActiveSheet()->setCellValue('K'.$i,"'".$data->shop."'");
            $resultExcel->getActiveSheet()->setCellValue('L'.$i,"'".$data->age."'");
            $resultExcel->getActiveSheet()->setCellValue('M'.$i,"'".$data->marriage."'");
            $resultExcel->getActiveSheet()->setCellValue('N'.$i,"'".$data->join_date."'");
            $resultExcel->getActiveSheet()->setCellValue('O'.$i,"'".$data->top_edu."'");
            $resultExcel->getActiveSheet()->setCellValue('P'.$i,"'".$data->teacher."'");
            $resultExcel->getActiveSheet()->setCellValue('Q'.$i,"'".$data->score."'");
            $resultExcel->getActiveSheet()->setCellValue('R'.$i,"'".$data->qual."'");
            $resultExcel->getActiveSheet()->setCellValue('S'.$i,"'".$data->year_yellow."'");
            $resultExcel->getActiveSheet()->setCellValue('T'.$i,"'".$data->year_sue."'");
            $resultExcel->getActiveSheet()->setCellValue('U'.$i,"'".$data->half_score."'");
            $resultExcel->getActiveSheet()->setCellValue('V'.$i,"'".$data->half_range."'");
            $resultExcel->getActiveSheet()->setCellValue('W'.$i,"'".$data->co_single."'");
            $resultExcel->getActiveSheet()->setCellValue('X'.$i,"'".$data->co_single_range."'");
            $resultExcel->getActiveSheet()->setCellValue('Y'.$i,"'".$data->half_qual."'");
            $resultExcel->getActiveSheet()->setCellValue('Z'.$i,"'".$data->half_record."'");
            $resultExcel->getActiveSheet()->setCellValue('AA'.$i,"'".$data->half_cus."'");
            $i++;
        }
         
        //设置导出文件名
        $outputFileName =$user->district_name."-报名结果".date('Y-m-d',time()).'.xls';
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
    
    public function actionExportResult(){
        $yearMonth=date('Ym');
        $user=yii::$app->user->identity;
        $model=InterviewRegister::findAll(['district_code'=>$user->district_code,'year_month'=>$yearMonth,'signup_result'=>'1']);
        if(empty($model)){
            yii::$app->getSession()->setFlash('error','还没有人报名,没有数据哦!');
            return $this->redirect(yii::$app->getRequest()->referrer);
        }
    
        $resultExcel=new \PHPExcel();
        $resultExcel->getActiveSheet()->setCellValue('A1','序号');
        $resultExcel->getActiveSheet()->setCellValue('B1','工号');
        $resultExcel->getActiveSheet()->setCellValue('C1','姓名');
        $resultExcel->getActiveSheet()->setCellValue('D1','级别');
        $resultExcel->getActiveSheet()->setCellValue('E1','面试大区');
        $resultExcel->getActiveSheet()->setCellValue('F1','面试结果');
        $resultExcel->getActiveSheet()->setCellValue('G1','面试评价');
        $resultExcel->getActiveSheet()->setCellValue('H1','联系方式');
        $resultExcel->getActiveSheet()->setCellValue('I1','营销区域');
        $resultExcel->getActiveSheet()->setCellValue('J1','业务区域');
        $resultExcel->getActiveSheet()->setCellValue('K1','门店');
        $resultExcel->getActiveSheet()->setCellValue('L1','年龄');
        $resultExcel->getActiveSheet()->setCellValue('M1','婚姻状况');
        $resultExcel->getActiveSheet()->setCellValue('N1','入职日期');
        $resultExcel->getActiveSheet()->setCellValue('O1','最高教育程度');
        $resultExcel->getActiveSheet()->setCellValue('P1','认证讲师');
        $resultExcel->getActiveSheet()->setCellValue('Q1','博学成绩');
        $resultExcel->getActiveSheet()->setCellValue('R1','精英社资格');
        $resultExcel->getActiveSheet()->setCellValue('S1','一年内红黄线');
        $resultExcel->getActiveSheet()->setCellValue('T1','一年内投诉');
        $resultExcel->getActiveSheet()->setCellValue('U1','半年业绩');
        $resultExcel->getActiveSheet()->setCellValue('V1','半年业绩大区排名');
        $resultExcel->getActiveSheet()->setCellValue('W1','合作单边比');
        $resultExcel->getActiveSheet()->setCellValue('X1','合作单边比大区排名');
        $resultExcel->getActiveSheet()->setCellValue('Y1','半年(房)');
        $resultExcel->getActiveSheet()->setCellValue('Z1','半年(客)');
        $resultExcel->getActiveSheet()->setCellValue('AA1','半年(带)');
        $i=2;
        foreach ($model as $k=>$v){
            $data=InterviewData::findOne(['work_number'=>$v->work_number,'year_month'=>$yearMonth]);
            if(empty($data)){
                continue;
            }
            $resultExcel->getActiveSheet()->setCellValue('A'.$i,$k+1);
            $resultExcel->getActiveSheet()->setCellValue('B'.$i,$v->work_number);
            $resultExcel->getActiveSheet()->setCellValue('C'.$i,$v->name);
            $resultExcel->getActiveSheet()->setCellValue('D'.$i,$data->level);
            $resultExcel->getActiveSheet()->setCellValue('E'.$i,$v->district_name);
            $resultExcel->getActiveSheet()->setCellValue('F'.$i,CommonUtil::getDescByValue('interview_register', 'interview_result', $v->interview_result));
            $resultExcel->getActiveSheet()->setCellValue('G'.$i,$v->remark);
            $resultExcel->getActiveSheet()->setCellValue('H'.$i,$v->mobile);
            $resultExcel->getActiveSheet()->setCellValue('I'.$i,$data->sale_district);
            $resultExcel->getActiveSheet()->setCellValue('J'.$i,$data->business_district);
            $resultExcel->getActiveSheet()->setCellValue('K'.$i,$data->shop);
            $resultExcel->getActiveSheet()->setCellValue('L'.$i,$data->age);
            $resultExcel->getActiveSheet()->setCellValue('M'.$i,$data->marriage);
            $resultExcel->getActiveSheet()->setCellValue('N'.$i,$data->join_date);
            $resultExcel->getActiveSheet()->setCellValue('O'.$i,$data->top_edu);
            $resultExcel->getActiveSheet()->setCellValue('P'.$i,"'".$data->teacher."'");
            $resultExcel->getActiveSheet()->setCellValue('Q'.$i,$data->score);
            $resultExcel->getActiveSheet()->setCellValue('R'.$i,$data->qual);
            $resultExcel->getActiveSheet()->setCellValue('S'.$i,$data->year_yellow);
            $resultExcel->getActiveSheet()->setCellValue('T'.$i,$data->year_sue);
            $resultExcel->getActiveSheet()->setCellValue('U'.$i,$data->half_score);
            $resultExcel->getActiveSheet()->setCellValue('V'.$i,$data->half_range);
            $resultExcel->getActiveSheet()->setCellValue('W'.$i,$data->co_single);
            $resultExcel->getActiveSheet()->setCellValue('X'.$i,$data->co_single_range);
            $resultExcel->getActiveSheet()->setCellValue('Y'.$i,$data->half_qual);
            $resultExcel->getActiveSheet()->setCellValue('Z'.$i,$data->half_record);
            $resultExcel->getActiveSheet()->setCellValue('AA'.$i,$data->half_cus);
            $i++;
        }
         
        //设置导出文件名
        $outputFileName =$user->district_name."-面试结果".date('Y-m-d',time()).'.xls';
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
     * Updates an existing InterviewDistrict model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = InterviewAddress::findOne($id);
        $model->time=date('Y-m-d H:i',$model->time);
        $year_month=$model->year_month;
        if ($model->load(Yii::$app->request->post()) ) {
            $model->year_month=date('Ym',strtotime($model->time));
            if($year_month!=$model->year_month){
                yii::$app->getSession()->setFlash('error',$model->year_month."已有面试地址,面试时间只能是$model->year_month,其他月份面试时间请新增!");
                return $this->redirect(yii::$app->request->referrer);
            }
            
            $model->time=strtotime($model->time);
            $model->created_at=time();
            if($model->save()){
                return $this->redirect(['interview-address', 'id' => $model->id]);
            }else{
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing InterviewDistrict model.
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
     * Finds the InterviewDistrict model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InterviewDistrict the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InterviewDistrict::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
