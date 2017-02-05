<?php
class ImportModel extends AppModel {
    
    //引入文件
    function __construct() {
        include_once(LIBRARY . '/PHPExcel/PHPExcel.php');
    }
    
    /**
     * 读取excel
     */
    function readExcel($filename, $key = null, $encode='utf-8') {
        $objReader = new PHPExcel();
        $objReader = new PHPExcel_Reader_Excel2007();
        
        if(!$objReader->canRead($filename)){
            $objReader = new PHPExcel_Reader_Excel5();
            
            if(!$objReader->canRead($filename)){
                return array();
            }
        }
        
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData = array();
        for ($row = 2; $row <= $highestRow; $row++) {
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                if($col == 3){
                    $excelData[$row][$key ? $key[$col] : $col] = $this->formatExcelTime($objWorksheet->getCellByColumnAndRow($col, $row)->getValue());
                }else{
                    $excelData[$row][$key ? $key[$col] : $col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                }
            }
        }
        return $excelData; 
    }
    
    /**
     * 对excel里的日期进行格式转化
     */
    function formatExcelTime($date, $time = false) {
        if(function_exists('GregorianToJD')){
            if (is_numeric( $date )) {
            $jd = GregorianToJD( 1, 1, 1970 );
            $gregorian = JDToGregorian( $jd + intval ( $date ) - 25569 );
            $date = explode( '/', $gregorian );
            $date_str = str_pad( $date [2], 4, '0', STR_PAD_LEFT )
            ."-". str_pad( $date [0], 2, '0', STR_PAD_LEFT )
            ."-". str_pad( $date [1], 2, '0', STR_PAD_LEFT )
            . ($time ? " 00:00:00" : '');
            return $date_str;
            }
        }else{
            $date=$date>25568?$date+1:25569;
            /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
            $ofs=(70 * 365 + 17+2) * 86400;
            $date = date("Y-m-d",($date * 86400) - $ofs).($time ? " 00:00:00" : '');
        }
      return $date;
    }
    
    /**
     * 验证Excel数组
     */
    function validImport($form, &$errors, $relationKeyList, $genderList, $existedStudentKeyList) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        foreach ($form['parent_list'] as $v) {
            if(!empty($v[0])){
                $student_parent_name[] = $form['student_name'] . "-" . $v[0];
            }
        }
        
        $config = array(
            'student_name' => array(
                array('isNotNull', '请输入学生姓名'),
                // array('isKeyNotExisted', '相同学生相同家长重复', array('key' => "{$form['student_name']}-{$form['relation_title']}", 'keys' => $existedStudentKeyList)),
                array('isKeyNotExisted', '相同学生相同家长重复', array('key' => $student_parent_name, 'keys' => $existedStudentKeyList)),
                ),
            // 'class_name' => array(
            //     array('isNotNull', '请检查班级'),
            //     array('isKeyExisted', '请检查班级', array('keys' => $classList)),
            // ),
            'student_gender' => array(
                array('isNotNull', '请输入学生性别'),
                array('isKeyExisted', '请输入正确的性别', array('keys' => $genderList)),
            ),
        );
        
        if ($form['student_birthday']) {
            $config['student_birthday'] = array(
                array('isDate', '请输入正确的学生生日'),
            );
        }
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            $this->log(print_r($errors,true),'test');
            return false;
        }
        return true;
    }
    
    //上传验证
    function validUploadFile($form, &$errors) {

        $validator = $this->load(APP_CORE, 'AppValidator');
        // $config = array(
        //     'class_id' => array(
        //         array('isNotNull', '请选择班级'),
        //     ),
        // );

        $valid = true;

        // if (!$validator->valid($config, $form)) {
        //     $errors = $this->langs($validator->getError());
        //     $valid = false;
        // }

        if ($_FILES['file']['name'] != '') {
            if ($_FILES['file']['error'] > 0) {
                $errors['file'] = "上传失败，请稍后再试";
                return false;
            } else {
                return $valid;
            }
        } else {
            $errors['file'] = "请上传文件";
            return false;
        }

        return $valid;
    }

    function saveImport($dataList) {
        
        $studentModel = $this->getModel('Student');
        $schoolParentModel = $this->getModel('SchoolParent');
        $relationModel = $this->getModel('Relation');
        $smsModel = $this->getModel('Sms');
        $classModel = $this->getModel('Class');

        // $parentTypeList = array();
        // foreach($this->code('parentType') as $value) {
        //     $parentTypeList[$value['name']] = $value['value'];
        // }

        $genderList = array();
        foreach($this->code('gender') as $value) {
            $genderList[$value['name']] = $value['value'];
        }
        
        $relationList = $this->code('relation');
        $relationkeyList = array();
        foreach($relationList as $value) {
            $relationkeyList[$value['name']] = $value['value'];
        }
        
        //事务方式写入数据库
        //写学生信息
        //写家长信息
        //写关联关系
        //如果是新家长写短信
        $dbo = $this->getDBO();
        $dbo->BeginTrans();
        

        foreach($dataList as $form) {
            $schoolId = $form['school_id'];     // 学校ID
            $class_title = $form['class_name'];
            // 根据班级名称返回班级ID
            $class_info = $classModel->getOneClass($schoolId, $class_title);
            if($class_info){
                $class_id = $class_info['id'];
            }else{
                $class_save_info = array(
                    'title' => $class_title,
                    'start' => '',
                    'school_id' => $schoolId,
                    'status' => true ,
                    );
                $class_id = $classModel->saveClass($class_save_info);
            }

            $student = array(
                'name' => $form['student_name'],
                'birthday' => $form['student_birthday'] ? $form['student_birthday'] : '0000-00-00',
                'gender' => $genderList[$form['student_gender']],
                'school_id' => $schoolId,
                'class_id' => $class_id,
                'status' => true,
            );
            
            $rs = $studentModel->getClassStudentByName($form['student_name'], $class_id,$form['student_birthday']);
            if ($rs) {
                $student['id'] = $rs['id'];//根据班级、姓名查到学生，就更新学生信息
            }
            $studentId = $studentModel->saveStudent($student);

            if ($studentId) {
                foreach ($form['parent_list'] as $k => $v) {
                    if(!empty($v[0])){
                        $parentId = 0;
                        
                        $parent = array(
                            'school_id' => $schoolId,
                            'name' => $v[0],
                            'phone' => $v[1],
                            'type' => 1,
                            'status' => true,
                        );
                    
                        $parentPhone = $v[1];
                        $parentName = $v[0];
                        $relationTitle = $v[0];

                        if ($parentPhone && $parentName && $relationTitle) {    //Excel中包含家长信息
                            $rs = $schoolParentModel->getParentByPhone($schoolId, $v[1]);
                            if ($rs) {
                                $parent['id'] = $rs['id'];    //根据学校、手机号查到家长，就更新家长信息
                            }
                            $parentId = $schoolParentModel->saveParent($parent);
                            
                            if ($parentId) {
                                $studentParent = array(
                                    'parent_id' => $parentId,
                                    'student_id' => $studentId,
                                    'school_id' => $schoolId,
                                    'class_id' => $class_id,
                                    'relation_title' => $v[0],
                                );
                                if(isset($relationkeyList[$v[0]])){
                                    $studentParent['relation_id'] = $relationkeyList[$v[0]];
                                }else{
                                    $studentParent['relation_id'] = PARENT_TYPE_OTHER;
                                }
                                $rs = $schoolParentModel->getStudentParent($parentId, $studentId);
                                if ($rs) {
                                    $studentParent['id'] = $rs['id'];  //根据家长、学生Id查到关系，就更新关系
                                }
                                $relationId = $schoolParentModel->saveStudentParent($studentParent);
                                
                                if($relationId) {
                                    //写推广短信
                                    $smsTemplate = $this->code('sms.template.parentAdd');
                                    $groupAddSmsUrl = WX_APP_URL;
                                    $sms['message'] = str_replace(array('$studentName'), array($form['student_name']), $smsTemplate);
                                    $sms['phone'] = $v[1];
            
                                    $smsModel->saveSms($sms);
                                } else {
                                    $dbo->RollbackTrans();
                                    return false;
                                }
                             } else {
                                $dbo->RollbackTrans();
                                return false;
                            }
                        }
                    }
                }   // foreach结束
            } else {
                $dbo->RollbackTrans();
                return false;
            }
        }
        $dbo->CommitTrans();
        return true;
    }
}


?>