<?php

/**
 * @description 安卓交互接口
 */
class FrontDeviceController extends AppController {

    var $classModel = null;
    var $schoolRoleModel = null;
    var $studentModel = null;
    var $relationModel = null;
    var $schoolParentModel = null;
    
    function FrontDeviceController() {
        $this->AppController();
        $this->classModel = $this->getModel("Class");
        $this->schoolRoleModel = $this->getModel("SchoolRole");
        $this->studentModel = $this->getModel("Student");
        $this->relationModel = $this->getModel("Relation");
        $this->schoolParentModel = $this->getModel("SchoolParent");
    }

    /**
     * @description ✔ 获取学校班级信息列表
     * @param name=学校ID var=school_id type=int required=true remark=学校ID
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
              'school_title' => '幼儿园名称', //幼儿园名称
              'class_list' => array(
                  array(
                      'id' => 999, // 班级ID
                      'title' => '班级名称',
                  ),
                  array(
                      'id' => 999, // 班级ID
                      'title' => '班级名称',
                  ),
              )
          )
        )
     */
    function getClassListAction() {
        
        $request = $this->rawData2Arr();
        
        if (!$school = $this->schoolRoleModel->getSchool($request['school_id'])) {
            $return = array(
                'code' => 1,
                'message' => '学校不存在',
            );

            echo $this->json_encode($return);
            exit();
        }
        
        $classList = $this->classModel->getClassList($request['school_id']);
        
        $return = array(
            'code' => 0,
            'message' => '',
            'data' => array(
                'school_title' => $school['title'],
                'class_list' => $classList,
            ),
        );
        
        echo $this->json_encode($return);
        exit();
    }
    
    /**
     * @description ✔ 新同学录入
     * @param name=学校ID var=school_id type=int required=true remark=学校ID
     * @param name=班级ID var=class_id type=int required=true remark=班级ID
     * @param name=学生姓名 var=name type=string required=true remark=学生姓名
     * @param name=学生性别 var=gender type=int required=true remark=学生性别：1-男、2-女
     * @param name=学生生日 var=birthday type=date required=true remark=格式：YYYYMMDD
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
            'id' => 111, //学生ID
          )
        )
     */
    function addStudentAction() {
        
        $request = $this->rawData2Arr();
        
        if($this->studentModel->validStudent($request, $errors)){
            
            $student_id = $this->studentModel->saveStudent($request);
            
            if($student_id){
                $return = array(
                    'code' => 0,
                    'message' => '',
                    'data' => array(
                        'id' => $student_id,
                    )
                );
            }else{
                $return = array(
                    'code' => 1, //  1 - 失败
                    'message' => '保存信息失败。', // code 不为 0 时的错误信息
                );
                
            }
            
            
        } else {
            $return = array(
                'code' => 1, //  1 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
        
        echo $this->json_encode($return);
        exit;
    }
    
    /**
     * @description ✔ 学生家长录入（一次多人）
     * @param name=学生ID var=student_id type=int required=true remark=学生ID
     * @param name=家长列表 var=parent_list type=string required=true remark=格式：关系ID，电话号码|关系ID，电话号码<br>例如：1,15122222222|2,15133333333<br>关系ID：1-爸爸、2-妈妈、3-爷爷、4-奶奶、5-外公、6外婆
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
            'parent_ids' => array(
                '15184442222' => 111,  //手机号、家长id
                '15773337777' => 222,  //手机号、家长id
            ), 
          ),
        )
     */
     /*
    function addStudentParentsAction() {
        
        $request = $this->rawData2Arr();
        
        //TODO判空
        if($this->studentModel->validStudentParent($request, $errors)){
            $parentList = explode("|", $request['parent_list']);
            
            $parentData = array();
            
            $data = array();
            
            $exitParent = array();
            $student = $this->studentModel->getStudentClass($request['student_id']);
            
            if($student){
                
                //验证
                foreach ($parentList as $value) {
                    $parentData = explode(",", $value);
                
                    $exitParent[] = $parentData[0];
                    $exitParent[] = $parentData[1];
                
                    $relation = $this->relationModel->getRelation($parentData[0]);
                    $parent = $this->schoolRoleModel->getSchoolParent($student['school_id'], $parentData[1]);
                    
                    if($relation){
                        $form = array(
                            'student_id' => $student['id'],
                            'parent_id' => $parent['id'],
                            'school_id' => $student['school_id'],
                            'class_id' => $student['class_id'],
                            'relation_id' => $parentData[0],
                            'relation_title' => $relation['title'],
                            'phone' => $parentData[1],
                        );
                        
                        if(!$this->studentModel->validParentPhone($form, $errors)){
                            $return = array(
                                'code' => 1,
                                'message' => implode(';', $errors),
                            );
                        
                            echo $this->json_encode($return);
                            exit;
                        }
                        
                        $data[] = $form;
                    }else{
                        
                        $return = array(
                            'code' => 1,
                            'message' => '关系不能为空或不存在，请重新选择',
                        );
                        
                        echo $this->json_encode($return);
                        exit;
                    }
                    
                }
            }else{
                $return = array(
                    'code' => 1,
                    'message' => '学生不存在',
                );
                echo $this->json_encode($return);
                exit;
            }
            
            if (count($exitParent) != count(array_unique($exitParent))) {
                $return = array(
                    'code' => 1,
                    'message' => '关系存在重复，请确认',
                );
                echo $this->json_encode($return);
                exit;
            }
            
            //保存家长与关系
            foreach ($data as $value) {
                if(!$value['parent_id']){
                    $parent_id = $this->schoolParentModel->saveSchoolParent($value);
                    $value['parent_id'] = $parent_id;
                }
            
                $this->studentModel->saveStudentParent($value);
            }
            
            $return = array(
                'code' => 0,  
                'message' => '',
                'data' => $parentList;
            );
            
            echo $this->json_encode($return);
            exit;
            
        }else{
            $return = array(
                'code' => 1, //  1 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
            
            echo $this->json_encode($return);
            exit;
        }
        
    }
    */
    /**
     * @description ✔ 学生家长录入（一次一人）
     * @param name=学生ID var=student_id type=int required=true remark=学生ID
     * @param name=手机号 var=phone type=string required=true remark=家长手机号
     * @param name=关系ID var=relation type=int required=true remark=关系ID列表：1-爸爸、2-妈妈、3-爷爷、4-奶奶、5-外公、6外婆、99-其他
     * @param name=关系名称 var=relation_title type=string required=false remark=关系名称，当且仅当&nbsp;relation&nbsp;等于&nbsp;99&nbsp;时上传
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
            'id' => 111, //家长ID
          ),
        )
     */
    function addParentAction() {
        
        $request = $this->rawData2Arr();
        
        //TODO判空
        if($this->studentModel->validStudentAddParent($request, $errors)){
            $student = $this->studentModel->getStudentClass($request['student_id']);
            if($student){
                
                //验证
                $isStudentParentRelation = $this->studentModel->getIsStudentParentRelation($request['student_id'], $request['relation']);
                
                if(!$isStudentParentRelation || $request['relation'] == PARENT_TYPE_OTHER){
                    
                    $isStudentParentPhone = $this->studentModel->getIsStudentParentPhone($student['school_id'], $request['phone']);
                    if(!$isStudentParentPhone){
                        $data = array(
                            'school_id' => $student['school_id'],
                            'name' => '',
                            'phone' => $request['phone'],
                            'type' => '0',
                            'status' => APP_UNIFIED_TRUE,
                        );
                        
                        $schoolParent_id =  $this->studentModel->saveSchoolParent($data);
                    }
                    
                    $isStudentParent = $this->studentModel->getIsStudentParent($isStudentParentPhone['id'], $student['id']);
                    
                    if(!$isStudentParent){
                        $relation = $this->relationModel->getRelation($request['relation']);
                        $relation_title = '';
                        if($request['relation'] != PARENT_TYPE_OTHER){
                            $relation_title = $relation['title'];
                        }else{
                            $relation_title = $request['relation_title'];
                        }
                        
                        $data2 = array(
                            'school_id' => $student['school_id'],
                            'class_id' => $student['class_id'],
                            'student_id' => $student['id'],
                            'parent_id' => $schoolParent_id ? $schoolParent_id : $isStudentParentPhone['id'],
                            'relation_id' => $request['relation'],
                            'relation_title' => $relation_title,
                        );
                        if($this->studentModel->saveStudentParent($data2)){
                            $return = array(
                                'code' => 0,  
                                'message' => '',
                                'data' => array(
                                    'id' => $schoolParent_id,
                                ),
                            );
                            
                            echo $this->json_encode($return);
                            exit;
                            
                        }else{
                            
                            $return = array(
                                'code' => 1,
                                'message' => '保存学生与家长关系信息失败。',
                            );
                            echo $this->json_encode($return);
                            exit;
                            
                        }
                    }else{
                        
                       $return = array(
                            'code' => 1,
                            'message' => '您已经是该学生家长。',
                        );
                        echo $this->json_encode($return);
                        exit; 
                    }
                    
                    
                    
                }else{
                    $return = array(
                        'code' => 1,
                        'message' => '学生家长关系已存在。',
                    );
                    echo $this->json_encode($return);
                    exit;
                }
                
            }else{
                $return = array(
                    'code' => 1,
                    'message' => '学生不存在',
                );
                echo $this->json_encode($return);
                exit;
            }
            
        }else{
            $return = array(
                'code' => 1, //  1 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
            
            echo $this->json_encode($return);
            exit;
        }
        
    }
    
    
}
?>
