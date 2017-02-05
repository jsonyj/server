<?php

class StaffController extends AppController {

    var $staffModel = null;
    var $classModel = null;
    var $signModel = null;


    public $sign_status = array(
        '0' => array('name'=>'正常', value=> '4'),
        '1' => array('name' => '缺勤', value=> '1'),
        '2' => array('name' => '迟到', value=> '3,6,7'),
        '3' => array('name' => '早退', value=> '5,7'),
        '4' => array('name' => '异常', value=> '2,3'),
    );

    function StaffController() {
        $this->AppController();
        $this->staffModel = $this->getModel('Staff');
        $this->classModel = $this->getModel('Class');
        $this->signModel = $this->getModel('Sign');
    }

    /**
     * @desc 职工一览
     * @author ly
     */
    function indexAction() {
        $sh = $this->get('sh');
        $user = $this->getSession(SESSION_USER);

        $staffList = $this->staffModel->getStaffList($user['school_id'], $sh);
        $this->view->assign('paging', $this->staffModel->paging);
        $this->view->assign('staffList', $staffList);
        $this->view->assign('sh', $sh);
        $this->view->layout();
    }

    /**
     * @desc 职工信息编辑
     * @author ly
     */
    function inputAction() {
        $user = $this->getSession(SESSION_USER);
        $page = $this->get('page');
        $form = $this->post('form');
        $id = $this->get('id');
        if ($this->isComplete()) {
            $form['id'] = $id;
            $form['school_id'] = $user['school_id'];
            if ($this->staffModel->validStaff($form, $errors)) {
                $timeForm['in_time'] = $form['in_time'];
                $timeForm['out_time'] = $form['out_time'];
                $typeId = $this->signModel->getSaveType($timeForm);
                if($typeId){
                    $form['sign_type_id'] = $typeId;
                    if ($rs = $this->staffModel->saveStaff($form)) {
                        if($form['type'] == ACT_SCHOOL_TEACHER){
                            if($id){
                                $staff = $this->staffModel->getStaff($id);
                                $staffClass['id'] = $staff['staff_class_id'];
                            }
                            $staffClass['school_id'] = $user['school_id'];
                            $staffClass['class_id'] = $form['class_id'];
                            $staffClass['staff_id'] = $rs;
                            
                            $this->staffModel->saveStaffClass($staffClass);
                        }
                        
                        if($id && $form['type'] != ACT_SCHOOL_TEACHER){
                            $staff = $this->staffModel->getStaff($id);
                            $this->staffModel->deleteStaffClass($staff['staff_class_id']);
                        }
                        
                        $this->redirect("?c=staff&a=index&sh[page]=".$page, true, '保存成功');
                        exit;
                    }
                    $this->redirect("?c=staff&a=input&pc=staff&pa=index&id={$id}", true, '保存失败');
                    exit;
                }
            }else {
                $this->view->assign('errors', $errors);
                $this->view->assign('form', $form);
            }
        } else {
            
            if($id){
                $form = $this->staffModel->getStaff($id);
                if($id && empty($form)) {
                    $this->redirect("?c=staff&a=index", true, $this->lang('ID不存在'));
                    exit();
                }
                $this->view->assign('form', $form);
            }
        }
        
        //获取所有签到类型
        $signTypeList = $this->staffModel->getSignTypeList();
        $sign_type = array();
        foreach ($signTypeList as $value) {
            $sign_type[] = array('name' => $value['title']. '（' . date('H:i', strtotime($value['in_time'])) . '~' . date('H:i', strtotime($value['out_time'])) . '）', 'value' => $value['id']);
        }
        $this->view->assign('sign_type', $sign_type);
        
        //获取所有班级
        $classList = $this->classModel->getAllClassList($user['school_id']);

        $class = array();
        foreach ($classList as $value) {
            $class[] = array('name' => $value['title'], 'value' => $value['id']);
        }
        $this->view->assign('class', $class);
        $this->view->layout();
    }
    
    /**
     * @desc 删除职工信息
     * @author ly
     */
    function deleteAction() {
        $id = $this->get('id');
        $staff = $this->staffModel->getStaff($id);
        $this->staffModel->deleteStaff($id);
        $this->staffModel->deleteStaffClass($staff['staff_class_id']);
        $this->redirect("?c=staff&a=index", true, '删除成功');
    }


     /**
     * @desc 签到一览
     * @wjd
     */
    function signIndexAction() {

        $sh = $this->get('sh');
        $user = $this->getSession(SESSION_USER);
        // var_dump($sh);
        
        $sh[start_timestamp] = strtotime($sh[start_time]);
        $sh[end_timestamp] = strtotime($sh[end_time]);
        // $sh[staff_name] = $sh[staff_name];
       
        $sh['school_id'] = $user['school_id'];
        
        $this->view->assign('sh',$sh);
        
        if($sh['sign_status']) {
            $sh['sign_status'] = explode(',', trim($sh['sign_status']));
        }
        $sign_list = $this->signModel->getSignList($sh);
        $this->view->assign('sign_status', $this->sign_status);
        $this->view->assign('paging',$this->signModel->paging);
        $this->view->assign('sign_list',$sign_list);
        $staffAllList = $this->staffModel->getStaffOptionList($user['school_id']); 
        $this->view->assign('staffAllList', $staffAllList);
        $this->view->layout();
    }

        /**
     * @desc 签到修改
     * @wjd
     */
    function signInputAction() {
        $id = $this->get('id');
        $form = $this->post('form');

        if ($this->isComplete()) {
            $form['id'] = $id;
            if ($this->signModel->validSign($form, $errors)) {
                if ($rs = $this->signModel->getSaveSign($form)) {
                    $this->redirect("?c=staff&a=signInput&pc=staff&pa=signInput&id={$rs}", true, '保存成功');
                    exit;
                }

                $this->redirect("?c=staff&a=signInput&pc=staff&pa=signInput&id={$id}", true, '保存失败');
                exit;
            }
            else {
                $this->view->assign('errors', $errors);
                $this->view->assign('form', $form);
            }
        } else {
            if($id){
                $form = $this->signModel->getSignById($id);
                if($id && empty($form)) {
                    $this->redirect("?c=sign&a=index", true, $this->lang('ID不存在'));
                    exit();
                }
                $this->view->assign('form', $form);
            }
        }

        $this->view->layout();
    }

       /**
     * @desc 考勤列表
     * @wjd
     */
    function typeIndexAction() {
        $sh = $this->get('sh');
        $type_list = $this->signModel->getTypeList($sh);
        $this->view->assign('paging',$this->signModel->paging);
        $this->view->assign('type_list',$type_list);
        $this->view->layout();
    }

        /**
     * @desc 考勤修改、新增
     * @wjd
     */
    function typeInputAction() {
        $id = $this->get('id');
        $form = $this->post('form');
        if ($this->isComplete()) {
            $form['id'] = $id;
            if ($this->signModel->validSign($form, $errors)) {
                if ($rs = $this->signModel->getSaveType($form)) {
                    $this->redirect("?c=staff&a=typeInput&pc=staff&pa=typeInput&id={$rs}", true, '保存成功');
                    exit;
                }

                $this->redirect("?c=staff&a=typeInput&pc=staff&pa=typeInput&id={$id}", true, '保存失败');
                exit;
            }
            else {
                $this->view->assign('errors', $errors);
                $this->view->assign('form', $form);
            }
        } else {
            if($id){
                $form = $this->signModel->getTypeById($id);
                if($id && empty($form)) {
                    $this->redirect("?c=staff&a=typeIndex", true, $this->lang('ID不存在'));
                    exit();
                }
                $this->view->assign('form', $form);
            }
        }

        $this->view->layout();
    }


        /**
     * @desc 删除考勤
     * @author wjd
     */
    function signDeleteAction() {
        $id = $this->get('id');
        if ($this->signModel->deleteType($id)) {
            $this->redirect('?c=staff&a=typeIndex', true, '删除成功');
        }
        $this->redirect('?c=staff&a=typeIndex', true, '删除失败');
    }

        /*导出签到表*/
    function exportAction() {
        $user = $this->getSession(SESSION_USER);       
        $sh = $this->get('sh');

        $sh['school_id'] = $user['school_id'];
        $export_title = $this->get('export_title');
        $sh[start_timestamp] = strtotime($export_title[start_time]);
        $sh[end_timestamp] = strtotime($export_title[end_time]);
        $sh[staffId] = $export_title[staffId];
        if($sh['sign_status']) {
            $sh['sign_status'] = explode(',', trim($sh['sign_status']));
        }
        if($sh[start_timestamp]){
        //数据
        $exportList = $this->signModel->exportSign($sh);
        $date_num = count($exportList);


        $objPHPExcel = $this->load(LIBRARY. 'PHPExcel/', 'PHPExcel');
        $this->includeOnce(LIBRARY. 'PHPExcel/PHPExcel/Writer/', 'Excel5.php');
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $objPHPExcel->getActiveSheet()->setTitle('职工签到考勤表');
        

        $excel_end_col = "F";
        //表头名称
        $objPHPExcel->getActiveSheet()->mergeCells( "A1:".$excel_end_col."1" );
        $objPHPExcel->getActiveSheet()->SetCellValue( 'A1', '职工签到考勤表' );
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        
        //项目
        $title = array('签到日期', '姓名', '电话号码','用户类型','考勤状态','签到时间','签退时间');        
        $row = 'A';
        foreach ($title as $k => $v) {
            $objPHPExcel->getActiveSheet()->SetCellValue( $row . '4', $v );
            $objPHPExcel->getActiveSheet()->getStyle($row . '4')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension($row)->setWidth(30);
            $row++;
        }
        
        //数据写入
        // $code = $this->code('gender');
        $now_row = 5;
        foreach($exportList as $key => $export) {
            foreach ($export as $k => $v) {
                $type_name = "";
                switch ($export['type']) {
                    case '31':
                        $type_name = "园长";
                        break;
                    case '32':
                        $type_name = "老师";
                        break;
                    case '33':
                        $type_name = "保健医生";
                        break;
                    case '34':
                        $type_name = "勤务";
                        break;
                    default:
                        $type_name = "其他";
                        break;
                }
                $signStatus = "";
                if($export['sign_status'] == "1" ){
                    $signStatus = "已缺勤";
                }elseif ($export['sign_status'] == "2") {
                    $signStatus = "签到+未签退";
                }elseif ($export['sign_status'] == "3") {
                    $signStatus = "迟到+未签退";
                }elseif ($export['sign_status'] == "4") {
                    $signStatus = "签到+签退";
                }elseif ($export['sign_status'] == "5") {
                    $signStatus = "签到+早退";
                }elseif ($export['sign_status'] == "6") {
                    $signStatus = "迟到+签退";
                }else{
                    $signStatus = "迟到+早退";
                }

                $objPHPExcel->getActiveSheet()->SetCellValue( "A".$now_row, $export['sign_date']);
                $objPHPExcel->getActiveSheet()->SetCellValue( "B".$now_row, $export['name']);
                $objPHPExcel->getActiveSheet()->SetCellValue( "C".$now_row, $export['phone']);
                $objPHPExcel->getActiveSheet()->SetCellValue( "D".$now_row, $type_name);
                $objPHPExcel->getActiveSheet()->SetCellValue( "E".$now_row, $signStatus);
                $objPHPExcel->getActiveSheet()->SetCellValue( "F".$now_row,  $export['in_time']);
                $objPHPExcel->getActiveSheet()->SetCellValue( "G".$now_row,  $export['out_time']);
            }
            $now_row ++;        
        }
        
        $d = date('Y-m-d');
        ob_end_clean();
        $objWrite = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
        header ( "Pragma: public" );
        header ( "Expires: 0" );
        header ( "Cache-Control:must-revalidate,post-check=0,pre-check=0" );
        header ( "Content-Type:application/force-download" );
        header ( "Content-Type:application/vnd.ms-execl" );
        header ( "Content-Type:application/octet-stream" );
        header ( "Content-Type:application/download" );
        header ( "Content-Disposition:attachment;filename=".'职工签到考勤表('.$d . ").xls" );
        header ( "Content-Transfer-Encoding:binary" );
        $objWrite->save ( "php://output" );
    }
    }

}

?>
