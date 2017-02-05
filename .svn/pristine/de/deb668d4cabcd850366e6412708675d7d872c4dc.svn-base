<?php

/**
 * @description 绑定客户端相关接口
 */
class BindDeviceController extends AppController {

    var $studentModel = null;
    var $fileModel = null;
    var $bindDeviceModel = null;
    var $schoolRoleModel = null;

    function BindDeviceController() {
        $this->AppController();
        $this->studentModel = $this->getModel('Student');
        $this->fileModel = $this->getModel('File');
        $this->bindDeviceModel = $this->getModel('BindDevice');
        $this->schoolRoleModel = $this->getModel('SchoolRole');
    }
    
    /**
     * @author   wei
     * @description ✔ 同步学生和家长信息到绑定终端
     * @param name=学校ID var=school_id type=int required=true remark=学校ID
     * @param name=时间戳 var=timestamp type=string required=false remark=时间戳，用于增量更新，格式：YYYYMMDDHHMMSS，非必填
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
              'school_title' => '幼儿园名称', //幼儿园名称
              'student' => array(
                  array(
                      'id' => 999, // 唯一ID
                      'class_id' => 67, //班级ID
                      'class_title' => '班级名称', //班级名称
                      'name' => '小诺',  //姓名
                      'gender' => 1, //性别 1 - 男 2 - 女
                      'birthday' => '2015-11-28', //生日
                      'is_registered' => 0, //是否已在终端注册：0-否、1-是
                      'parents_list' => array( //家长列表
                            array(
                                'parent_id' => 111, //家长ID
                                'parent_name' => '张三', //家长姓名
                                'parent_title' => '家长称谓',
                                'parent_phone' => '15122222222', //家长手机号
                                'parent_rfid' => '15122222222', //家长IC卡号
                            ),
                            array(
                                'parent_id' => 111, //家长ID
                                'parent_name' => '张三', //家长姓名
                                'parent_title' => '家长称谓',
                                'parent_phone' => '15122222222', //家长手机号
                                'parent_rfid' => '15122222222', //家长IC卡号
                            ),
                      )
                  ),
                  array(
                      'id' => 999, // 唯一ID
                      'class_id' => 67, //班级ID
                      'class_title' => '班级名称', //班级名称
                      'name' => '小诺',  //姓名
                      'gender' => 1, //性别 1 - 男 2 - 女
                      'birthday' => '2015-11-28', //生日
                      'is_registered' => 0, //是否已在终端注册：0-否、1-是
                      'parents_list' => array( //家长列表
                            array(
                                'parent_id' => 111, //家长ID
                                'parent_name' => '张三', //家长姓名
                                'parent_title' => '家长称谓',
                                'parent_phone' => '15122222222', //家长手机号
                                'parent_rfid' => '15122222222', //家长IC卡号
                            ),
                            array(
                                'parent_id' => 111, //家长ID
                                'parent_name' => '张三', //家长姓名
                                'parent_title' => '家长称谓',
                                'parent_phone' => '15122222222', //家长手机号
                                'parent_rfid' => '15122222222', //家长IC卡号
                            ),
                      )
                  )
              )
          )
      )
     */
     
    function getStudentListAction() {
        
        $request = $this->rawData2Arr();
        
        if($this->studentModel->validGetSchooID($request, $errors)) {
            
            $timestamp = '';
            $schoo_id = $request['school_id'];
            if($request['timestamp']) {
                $timestamp = date('Y-m-d H:i:s', strtotime($request['timestamp']));
            }
            
            $schoolStudentParentList = $this->studentModel->getSchoolStudentParentList($schoo_id, $timestamp); //查询学校下面所有学生
            $tmp_arr = array();
            foreach ($schoolStudentParentList as $k => $val) {
                if (in_array($val['id'], $tmp_arr)) {
                    unset($schoolStudentParentList[$k]);
                } else {
                    $tmp_arr[] = $val['id'];
                }
            }
            $data = array();
            
            $student = array();
           
            foreach ($schoolStudentParentList as $key => $studentParent){
                $parentList = $this->studentModel->getParentList($studentParent['id']);
                $schoolClass = $this->studentModel->getSchoolClass($studentParent['school_id'],$studentParent['class_id']);
                     
                $parents_list = array();
                foreach($parentList as $parent){
                    $parents_list[] = array(
                        'parent_id' => $parent['id'], //家长ID
                        'parent_name' => $parent['name'], //家长姓名
                        'parent_type' => $parent['relation_id'], 
                        'parent_title' => $parent['relation_title'], 
                        'parent_phone' => $parent['phone'], //家长手机号
                        'parent_rfid' => $parent['rfid'], //家长IC卡号
                    );
                    
                }
                $student[] = array(
                    'id' => $studentParent['id'], // 唯一ID
                    'class_id' => $studentParent['class_id'], //班级ID
                    'class_title' => $schoolClass['class_title'], //班级名称
                    'name' => $studentParent['name'],  //姓名
                    'gender' => $studentParent['gender'], //性别 1 - 男 2 - 女
                    'birthday' => $studentParent['birthday'], //生日
                    'is_registered' => $studentParent['device_registered'],
                    'parents_list' => $parents_list,//家长列表
                );
                
                $data = array(
                    'school_title' => $schoolClass['title'], //幼儿园名称
                    'student' => $student,
                );
            }
            
            $return = array(
                'code' => 0, //  0 - 成功
                'message' => '', // code 不为 0 时的错误信息
                'data' => $data,
            );
        } 
        else {
            $return = array(
                'code' => 1, //  0 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
        
        echo $this->json_encode($return);
        exit();
        
        
    }
    
    
    /**
     * @author wei
     * @description  ✔ 同步学校职工信息到绑定终端
     * @param name=学校ID var=school_id type=int required=true remark=学校ID
     * @param name=时间戳 var=timestamp type=string required=false remark=时间戳，用于增量更新，格式：YYYYMMDDHHMMSS，非必填
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
              'school_id' => 111, //学校ID
              'school_title' => '幼儿园名称', //幼儿园名称
              'staff_list' => array(
                  array(
                      'id' => 999, // 职工ID
                      'type' => 32, // 职工类型：31-园长、32-老师、33-保健医生、34-后勤人员
                      'name' => '老师',  //姓名
                      'rfid' => '1111111', //RFID值
                      'phone' => '15188888888', //手机号
                  ),
                  array(
                      'id' => 999, // 职工ID
                      'type' => 32, // 职工类型：31-园长、32-老师、33-保健医生、34-后勤人员
                      'name' => '保健医生',  //姓名
                      'rfid' => '1111111', //RFID值
                      'phone' => '15188888888', //手机号
                  ),
              )
          )
      )
     */
    function getStaffListAction() {
        
        $request = $this->rawData2Arr();
        
        if($this->schoolRoleModel->validGetSchooID($request, $errors)) {
            
            $timestamp = '';
            $school_id = $request['school_id'];
            if($request['timestamp']) {
                $timestamp = date('Y-m-d H:i:s', strtotime($request['timestamp']));
            }
            $school = $this->schoolRoleModel->getSchool($school_id);
            $schoolStaffList = $this->schoolRoleModel->getSchoolStaffList($school_id, $timestamp); //查询学校下面所有职工
            
            $data = array();
            $staff_list = array();
            foreach($schoolStaffList as $val){
                $staff_list[] = array(
                    'id' => $val['id'], // 唯一ID
                    'type' => $val['type'], // 角色类型：32-老师、33-保健医生、34-后勤人员
                    'name' => $val['name'],  //姓名
                    'rfid' => $val['rfid'], //RFID值
                    'phone' => $val['phone'], //手机号
                
                );
            }
            
            $data = array(
                'school_id' => $school_id, //幼儿园ID
                'school_title' => $school['title'], //幼儿园名称
                'staff_list' => $staff_list,
            );
            
            $return = array(
                'code' => 0, //  0 - 成功
                'message' => '', // code 不为 0 时的错误信息
                'data' => $data,
            );
        } 
        else {
            $return = array(
                'code' => 2, //  0 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
        
        echo $this->json_encode($return);
        exit();
    }
    
    /**
     * @author wei
     * @change lxs
     * @description  ✔ 家庭成员和IC卡号绑定
     * @param name=家长ID var=parent_id type=int required=true remark=家长ID
     * @param name=学生ID var=student_id type=int required=true remark=学生ID
     * @param name=RFID var=rfid type=string required=true remark=IC卡号
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
       )
     */
    function bindParentRfidAction() {
        
        $request = $this->rawData2Arr();
        
        if($this->bindDeviceModel->validgetBindParentRfid($request, $errors)){
            
            if ($this->bindDeviceModel->setBindParentRfid($request)){
                
                $return = array(
                      'code' => 0, //  0 - 成功
                      'message' => '', // code 不为 0 时的错误信息
                );
            } 
            
        } 
         else {
            $return = array(
                'code' => 1, //  0 - 成功
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
        
        echo $this->json_encode($return);
        exit();    
    }
    
    /**
     * @author wei
     * @description ✔ 学校职工和IC卡号绑定
     * @param name=职工ID var=id type=int required=true remark=职工对应的唯一ID
     * @param name=RFID var=rfid type=string required=true remark=IC卡号
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
       )
     */
    function bindStaffRfidAction() {

        $request = $this->rawData2Arr();
        
        if($this->bindDeviceModel->validSchoolRole($request, $errors)){
            
            if($this->bindDeviceModel->getScholRole($request['id'])){
                if($this->bindDeviceModel->sverScholRole($request)){
                    $return = array(
                          'code' => 0, //  0 - 成功
                          'message' => '', // code 不为 0 时的错误信息
                    );
                }
            }else{
                $return = array(
                    'code' => 1, //  0 - 成功
                    'message' => '该角色用户不存在。', // code 不为 0 时的错误信息
                );
            }
                    
        }
         else {
            $return = array(
                'code' => 1, //  0 - 成功
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
        
        echo $this->json_encode($return);
        exit();    
    }
    
}
?>
