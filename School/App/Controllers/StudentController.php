<?php

define('SESSION_IMPORT_DATA', 'SESSION_IMPORT_DATA');

class StudentController extends AppController {

    var $schoolModel = null;
    var $classModel = null;
    var $studentModel = null;
    var $parentModel = null;
    var $relationModel = null;
    var $importModel = null;

    var $schoolParentModel = null;
    var $smsModel = null;

    function StudentController() {
        $this->AppController();
        $this->schoolModel = $this->getModel('School');
        $this->classModel = $this->getModel('Class');
        $this->studentModel = $this->getModel('Student');
        $this->parentModel = $this->getModel('SchoolParent');
        $this->relationModel = $this->getModel('Relation');
        $this->importModel = $this->getModel('Import');
        $this->smsModel = $this->getModel('Sms');
        $this->schoolParentModel = $this->getModel('SchoolParent');
    }

    function indexAction() {
        $user = $this->getSession(SESSION_USER);
        $sh = $this->get('sh');
        $this->view->assign('sh', $sh);
        if($sh['phone']){
            $studentListByPhone = $this->studentModel->getStuentByParentPhone($user['school_id'],$sh['phone']);
            foreach ($studentListByPhone as $k => $v) {
                if ($sh['name'] && $sh['class_id']) {
                    if($sh['name'] == $v['name'] && $sh['class_id'] == $v['class_id']) {
                        $studentList[$k] = $v;
                    }
                }elseif($sh['name']){
                    if($sh['name'] == $v['name']){
                        $studentList[$k] = $v;
                    } 
                }elseif ($sh['class_id']){
                    if ($sh['class_id'] == $v['class_id']) {
                        $studentList[$k] = $v;
                    }
                }elseif(!$sh['name'] && !$sh['class_id']){
                    $studentList[$k] = $v;
                }
            }
        }else{ 
            $studentList = $this->studentModel->getStudentList($user['school_id'], $sh);
        }
        $this->view->assign('paging', $this->studentModel->paging);
        foreach($studentList as $k => $v) {
            $v['parentList'] = $this->parentModel->getParentListByStudentId($v['id']);
            $studentList[$k] = $v;
        }
        $this->view->assign('studentList', $studentList);

        $classList = $this->classModel->getClassOptionList($user['school_id']);
        $this->view->assign('classList', $classList);

        $this->view->layout();
    }

    function inputAction() {
        $user = $this->getSession(SESSION_USER);
        $page = $this->get('page');
        $id = $this->get('id');
        $form = $this->post('form');
        $parent_list = array_values(array_splice($form,5));
        $form = array_slice($form,0,5);
        $form['school_id'] = $user['school_id'];
        if ($this->isComplete()) {
            $form['id'] = $id;
            if ($this->studentModel->validSaveStudent($form, $errors)) {
                if ($student_id = $this->studentModel->saveStudent($form)) {
                    if(!empty($parent_list)){
                        foreach ($parent_list as $key => $value) {
                            $value['school_id'] = $user['school_id'];
                            $value['type'] = 1;
                            $value['status'] = true;
                            $value['class_id'] = $form['class_id'];
                            if(!$this->schoolParentModel->validSaveParent($value, $errors)){
                                $this->view->assign('errors', $errors);
                            }else{
                                if ($parent_id = $this->schoolParentModel->saveParent($value)){
                                    $value['parent_id'] = $parent_id;
                                    $value['student_id'] = $student_id;
                                    include_once(LIBRARY . 'mutex.php');
                                    $mutex = new Mutex("schoolAdminSaveStudentParent");
                                    // while(!$mutex->getLock()){
                                    //     sleep(.5);
                                    // }
                                    $studentParent = $this->schoolParentModel->getStudentParentByStudentId($student_id, $value['relation_id']);
                                    if($studentParent && $parent_id != $studentParent['id'] && $value['relation_id'] != PARENT_TYPE_OTHER) {
                                        $errors['relation_id'] = "相同家长（{$studentParent['relation_title']}，{$studentParent['parent_phone']}）称谓学生关联已经存在";
                                        $this->view->assign('errors', $errors);
                                    } else {
                                        $rs = $this->schoolParentModel->saveStudentParent($value);
                                        $mutex->releaseLock();
                                        if($rs) {
                                            //存学生家长绑定短信
                                            $student = $this->studentModel->getStudent($form['school_id'], $student_id);
                                            $smsTemplate = $this->code('sms.template.parentAdd');
                                            $groupAddSmsUrl = WX_APP_URL;
                                            $sms['message'] = str_replace(array('$studentName'), array($student['name']), $smsTemplate);
                                            $sms['phone'] = $value['phone'];
                                            $this->smsModel->saveSms($sms);
                                        }
                                    }
                                }else{
                                    $this->redirect("?c=student&a=input&pc=student&pa=index&id={$id}", true, '保存失败');
                                    exit;
                                }
                            }
                        }
                        $this->redirect("?c=student&a=index&sh[page]=".$page, true, '保存成功');
                        exit;
                    }
                    $this->redirect("?c=student&a=index&sh[page]=".$page, true, '保存成功');
                }else{
                    $this->redirect("?c=student&a=input&pc=student&pa=index&id={$id}", true, '保存失败');
                    exit;
                }
            }else{
                $this->view->assign('errors', $errors);
                $this->view->assign('form', $form);
            }
        }else {
            $form = $this->studentModel->getStudent($user['school_id'], $id);
            if($id && empty($form)) {
                $this->redirect("?c=student&a=index", true, $this->lang('ID不存在'));
                exit();
            }
            $parent_list = $this->schoolParentModel->getSchoolParentListByStudentId($id);
            $form['parentNum'] = count($parent_list);
            $this->view->assign('form', $form);
            $this->view->assign('parent_list', $parent_list);
        }

        $classList = $this->classModel->getClassOptionList($user['school_id']);
        $this->view->assign('classList', $classList);
        $this->view->layout();
    }

    function deleteAction() {
        $user = $this->getSession(SESSION_USER);
        $id = $this->get('id');
        $page = $this->get('page');
        $this->studentModel->deleteStudent($user['school_id'], $id);      
        $this->studentModel->deleteStudentParent($user['school_id'], $id);
        if($page){
            $this->redirect("?c=student&a=index&sh[page]=".$page, true, '删除成功');
        }else{
            $this->redirect("?c=student&a=index", true, '删除成功');
        }
    }

    function getStudentOptionAction() {
        $user = $this->getSession(SESSION_USER);
        $id = $this->get('id');
        $studentOptionList = $this->studentModel->getStudentOptionList($user['school_id'], $id);
        print json_encode(array(
            'code' => 0,
            'optionList' => $studentOptionList,
        ));
    }

    function importAction() {

        $user = $this->getSession(SESSION_USER);

        $form = $this->post('form');
        $op = $this->post('op');

        if ($this->isComplete()) {

            if($this->importModel->validUploadFile($form, $errors)){

                $columns = array(
                    'class_name',
                    'student_name',
                    'student_gender',
                    'student_birthday',
                    'relation_title',
                    'parent_phone',
                    'relation_title1',
                    'parent_phone1',
                    'relation_title2',
                    'parent_phone2',
                    'relation_title3',
                    'parent_phone3',
                    'relation_title4',
                    'parent_phone4',
                    'relation_title5',
                    'parent_phone5',
                );

                //获得excel数组
                $importDataList = $this->importModel->readExcel($_FILES['file']['tmp_name'], $columns);
                foreach ($importDataList as $key => $value) {
                    $importDataList[$key] = array_slice($value,0,4);
                    $importDataList[$key]['parent_list'] = array_chunk(array_slice($value,4),2);
                }
                // 循环判断是否重名
                foreach ($importDataList as $key => $value) {
                    if(isset($arr[$value['student_name'].$value['student_birthday']])){
                        $importDataList[$key]['errors'] = array('student_name'=>$value['student_name']."出现重复");;
                    }else{
                        $arr[$value['student_name'].$value['student_birthday']] = $value['student_name'];
                    }
                }
                // 循环获取称谓
                $relationList = $this->relationModel->getRelationOptionList();
                $relationkeyList = array();
                foreach($relationList as $relation) {
                    $relationkeyList[] = $relation['name'];
                }
                
                // 循环获取性别
                $genderList = array();
                foreach($this->code('gender') as $gender) {
                    $genderList[] = $gender['name'];
                }
                
                $importDataValid = true;

                $existedStudentKeyList = array();

                foreach($importDataList as $key => $value) {
                    
                    //学校id
                    $value['school_id'] = $user['school_id'];

                    $importDataList[$key] = $value;
                    
                    if(!$this->importModel->validImport($value, $error, $relationkeyList, $genderList, $existedStudentKeyList)){
                        $importDataList[$key]['errors'] = $error;
                        // 循环判断是否重名
                        foreach ($importDataList as $key => $value) {
                            if(isset($arr[$value['student_name'].$value['student_birthday']])){
                                $importDataList[$key]['errors'] = array('student_name'=>$value['student_name']."出现重复");
                            }else{
                                $arr[$value['student_name'].$value['student_birthday']] = $value['student_name'];
                            }
                        }
                        $importDataValid = false;
                    }
                    foreach ($form['parent_list'] as $v) {
                        if(!empty($v[0])){
                            $existedStudentKeyList[$key][] = $form['student_name'] . "-" . $v[0];
                        }
                    }
                    // $existedStudentKeyList[] = "{$value['student_name']}-{$value['relation_title']}";
                }

                if($importDataValid){
                    $this->setSession(SESSION_IMPORT_DATA, $importDataList);
                }
                
                $this->view->assign('importDataList', $importDataList);
                $this->view->assign('importDataValid', $importDataValid);
                $this->view->assign('class', $this->classModel->getClass($user['school_id'], $form['class_id']));

                $this->view->layout('import_confirm');
                exit();

            } else {
                $this->view->assign('errors', $errors);
            }
        } else if($op == 'save') {
            $importDataList = $this->getSession(SESSION_IMPORT_DATA);
            if($this->importModel->saveImport($importDataList)) {
                $this->unsetSession(SESSION_IMPORT_DATA);
                $this->redirect("?c=student&a=index", true, '保存成功');
                exit;
            } else {
                $this->view->assign('importDataList', $importDataList);
                $this->view->assign('importDataValid', false);
                $this->view->layout('import_confirm');
                exit();
            }
        }

        $this->view->assign('form', $form);

        $this->view->layout();
    }

    /*
     * 未完成学生认领的学生图片列表、学生班级
     */
    function claimIndexAction() {

        $user = $this->getSession(SESSION_USER);
        $claimImg_list = $this->studentModel->getClaimImgList();
        $claimClass_list = $this->studentModel->getClaimClassList();
        $this->view->assign('claimImg_list',$claimImg_list);
        $this->view->assign('claimClass_list',$claimClass_list);
        $this->view->assign('claimStudent_list',$claimStudent_list);
        $this->view->layout();

    }

    /*
     * 未完成学生认领的学生列表
     */
    function getStudentListAction() {
        $class_id = $this->post("info");
        $img_id = $this->post("img_info");
        $detection_id = $this->post("detection_info");
        $terminal_img_id = $this->post("terminal_img_info");
        $file_img_id = $this->post("file_img_info");
        $claimStudent_list = $this->studentModel->getStudentListByClassId($class_id);

        $str = "";
        $str.="<input type='hidden' name='form[img_id]' value={$img_id}>";
        $str.="<input type='hidden' name='form[detection_id]' value={$detection_id}>";
        $str.="<input type='hidden' name='form[terminal_img_id]' value={$terminal_img_id}>";
        $str.="<input type='hidden' name='form[file_img_id]' value={$file_img_id}>";
        $str.="<select class='form-control' name='form[student_id]' style='width:230px;'>";
        foreach($claimStudent_list as $claimStudent) {
            $str.="<option value={$claimStudent["id"]}>".$claimStudent['name']."</option>";
        }
        $str.="</select>";
        print_r($str);
    }

    /*
     * 学生认领信息保存
     */
    function claimInputAction() {

        $form = $this->post('form');
        $id = $form['student_id'];
        if ($this->studentModel->validClaimStudent($form, $errors)) {
            if ($rs = $this->studentModel->updateClaimStudent($form)) {
                $this->studentModel->saveClaimStudent($form);
                $this->studentModel->updateStudentDection($form);
                $this->studentModel->updateFile($form);
                $this->studentModel->updateFirstLastestStudentDection($form);
                $this->redirect("?c=student&a=claimIndex&pc=student&pa=claimIndex&id={$rs}", true, '保存成功');
                exit;
            }

            $this->redirect("?c=student&a=claimIndex&pc=student&pa=claimIndex&id={$id}", true, '保存失败');
            exit;
        }
        else {
            $this->view->assign('errors', $errors);
            $this->view->assign('form', $form);
        }
        $this->view->layout();
    }
    
    /**
    *@author: wzl
    *@desc: 导出
    **/
    function exportAction() {

        $user = $this->getSession(SESSION_USER);
        
        $sh = $this->get('sh');
        $sh['school_id'] = $user['school_id'];
        $export_title = $this->get('export_title');
        
        $class_title = $this->studentModel->getClassTitle($sh['class_id']);
        //数据
        $exportList = $this->studentModel->exportStudent($sh, $export_title['height'], $export_title['weight'], $export_title['temperature']);

  
        $objPHPExcel = $this->load(LIBRARY. 'PHPExcel/', 'PHPExcel');
        $this->includeOnce(LIBRARY. 'PHPExcel/PHPExcel/Writer/', 'Excel5.php');
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $objPHPExcel->getActiveSheet()->setTitle('学生表');
        $date_num = count($exportList);
        $excel_end_col = "F";
        // 设置execl格式，居中
        $objPHPExcel->getActiveSheet()->getStyle('A:F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //表头名称
        $objPHPExcel->getActiveSheet()->mergeCells( "A1:".$excel_end_col."1" );
        $objPHPExcel->getActiveSheet()->SetCellValue( 'A1', '学生表' );
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        // $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        //搜索条件sh
        // $objPHPExcel->getActiveSheet()->mergeCells( "A2:".$excel_end_col."2" );
        // $objPHPExcel->getActiveSheet()->SetCellValue( 'A2', '搜索条件： ' . ($sh['name']?('姓名:'.$sh['name']):'') . (($sh['name'] && $sh['class_id'])?"---":'') . ($sh['class_id']?('班级:'.$class_title['title']):''));
        // $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        
        //项目
        $title = array('姓名', '性别', '班级');
        // if($export_title['height']){
        //     $title[] .= $export_title['start_time'].'身高/cm';
        //     $title[] .= $export_title['end_time'].'身高/cm';
        //     $title[] .= '身高变化/cm';
        // }elseif ($export_title['weight']) {
        //     $title[] .= $export_title['start_time'].'体重/kg';
        //     $title[] .= $export_title['end_time'].'体重/kg';
        //     $title[] .= '体重变化/kg';
        // }elseif ($export_title['temperature']) {
        //     $title[] .= $export_title['start_time'].'体温/℃';
        //     $title[] .= $export_title['end_time'].'体温/℃';
        // }
        foreach ($export_title as $k => $v) {
            $title[] .= $v;
        }
        
        $row = 'A';
        foreach ($title as $k => $v) {
            $objPHPExcel->getActiveSheet()->SetCellValue( $row . '2', $v );
            $objPHPExcel->getActiveSheet()->getStyle($row . '2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension($row)->setWidth(30);
            $row++;
        }

        //数据写入
        $code = $this->code('gender');
        $now_row = 3;
        foreach($exportList as $key => $export) {
            $now_col ="A";
            foreach ($export as $k => $v) {
                if ($k == 'gender') {
                    $objPHPExcel->getActiveSheet()->SetCellValue( $now_col.$now_row, $code[$export['gender']]['name']);
                } elseif ($k == 'weight') {
                    $objPHPExcel->getActiveSheet()->SetCellValue( $now_col.$now_row, ($v/1000) > 0 ? ($v/1000) : $v);
                } else {
                    $objPHPExcel->getActiveSheet()->SetCellValue( $now_col.$now_row, $v);
                }
                $now_col++;
            }
            $now_row ++;
        }
        
        $d = date('Y-m-d');
        $objWrite = new PHPExcel_Writer_Excel5 ( $objPHPExcel );
        header ( "Pragma: public" );
        header ( "Expires: 0" );
        header ( "Cache-Control:must-revalidate,post-check=0,pre-check=0" );
        header ( "Content-Type:application/force-download" );
        header ( "Content-Type:application/vnd.ms-execl" );
        header ( "Content-Type:application/octet-stream" );
        header ( "Content-Type:application/download" );
        header ( "Content-Disposition:attachment;filename=".'学生表('.$d . ").xls" );
        header ( "Content-Transfer-Encoding:binary" );
        $objWrite->save ( "php://output" );
    }

    /**
    *@desc: print
    *@author: wzl
    **/
    function printAction() {
        
        $user = $this->getSession(SESSION_USER);
        
        $sh = $this->get('sh');
        $sh['school_id'] = $user['school_id'];
        
        $print_title = array(
            'name' => '姓名',
            'sex' => '性别',
            'class' => '班级'
        );
        
        if (($height = $this->get('print_title_height')) == '身高/cm') {
            $print_title['height'] = $height;
        }
        if (($weight = $this->get('print_title_weight')) == '体重/kg') {
            $print_title['weight'] = $weight;
        }
        if (($temperature = $this->get('print_title_temperature')) == '体温/℃') {
            $print_title['temperature'] = $temperature;
        }
        
        $printList = $this->studentModel->exportStudent($sh, $print_title['height'], $print_title['weight'], $print_title['temperature']);
        
        $this->view->assign('print_title', $print_title);
        $this->view->assign('printList', $printList);
        $this->view->layout();
    }


    /*新增批量删除*/
    function batchDeleteAction(){
        
        $user = $this->getSession(SESSION_USER);
        $id = $this->get('id');
        $page = $this->get('page');
        $checkedId = explode(",", $id);
        for($i = 0; $i < count($checkedId); $i++){
            $this->studentModel->deleteStudent($user['school_id'], $checkedId[$i]);
            $this->studentModel->deleteStudentParent($user['school_id'], $checkedId[$i]);
            // $this->studentModel->deleteSchoolParent($user['school_id'], $checkedId[$i]);
        }
        if($page){
            $this->redirect("?c=student&a=index&sh[page]=".$page, true, '删除成功');
        }else{
            $this->redirect("?c=student&a=index", true, '删除成功');
        }
       
    }

    function exportIndexAction(){
        $this->view->layout();
    }

    function printIndexAction(){
        $user = $this->getSession(SESSION_USER);
        $classList = $this->classModel->getClassOptionList($user['school_id']);
        $this->view->assign('classList', $classList);
        $this->view->layout();
    }

}

?>
