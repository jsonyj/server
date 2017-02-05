<?php

/**
 * @description 服务接口
 */
class ServiceController extends AppController {

    var $serviceModel = null;
    
    function ServiceController() {
        $this->AppController();
        $this->serviceModel = $this->getModel('Service');
    }

    /**
     * @description 查询页面测试接口
     * @param name=学生ID var=id type=int required=true remark=学生ID
     * @param name=类型 var=type type=int required=true remark=类型：1-日报、2-月报、3-接送报告、4-身高、5-体重、6-温度、7-照片
     * @param name=统计类型 var=type_chart type=int required=false remark=类型：1-月表、2-年表、3-总表、（当type为4、5的时候传入）
     * @param name=起始日期 var=date_start type=date required=true remark=类型:YYYYmmdd(当单日查询时，只上传起始日期，结束日期留空或不上传)
     * @param name=结束日期 var=date_end type=date required=false remark=类型:YYYYmmdd
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
              array(
                'date' => '20160606', //日期
                'value' => '11', //值
                'url' => 'www.aaa.com', //跳转链接
              ),
          )
        )
     */
    /*
    function searchAction() {
        $form['id'] = $this->post('id');
        $form['type'] = $this->post('type');
        $form['type_chart'] = $this->post('type_chart');
        $form['date_start'] = $this->post('date_start');
        $form['date_end'] = $this->post('date_end');
        
        if($this->serviceModel->validIsSearch($form, $errors)){
            $data = array();
            switch($form['type']){
                case SERVICE_TYPE_DAILY :
                    $date_start = date('Y-01-01', strtotime($form['date_start']));
                    $date_end = date('Y-12-31', strtotime($date_start));
                    
                    $serviceDailyList = $this->serviceModel->getServiceDailyList($form['id'], $date_start, $date_end);
                    foreach($serviceDailyList as $val){
                        $daily = array(
                            'date' => date('Ymd', strtotime($val['created'])), //日期
                            'value' => '', //值
                            'url' => APP_SERVICE_TYPE_URL.'?c=parent&a=report&id='.$val['id'] . '&studentId='. $form['id'].'&type='.REPORT_TYPE_DAY, //跳转链接
                        );
                        $data[] = $daily;
                    }
                    
                    $return = array(
                        'code' => 0, //  0 - 失败、
                        'message' => '', // code 不为 0 时的错误信息
                        'data' => $data, // code 不为 0 时的错误信息
                    );
                    
                break;
                    
                case SERVICE_TYPE_MONTH :
                    $date_start = date('Y-01-01', strtotime($form['date_start']));
                    $date_end = date('Y-12-31', strtotime($date_start));
                    
                    $serviceMonthList = $this->serviceModel->getServiceMonthList($form['id'], $date_start, $date_end);
                    foreach($serviceMonthList as $val){
                        $month = array(
                            'date' =>  date('Ymd', strtotime($val['start'])), //日期
                            'value' => '', //值
                            'url' => APP_SERVICE_TYPE_URL.'?c=parent&a=report&id='.$val['id'] . '&studentId='. $form['id'].'&day='.date('Y-m-d', strtotime($val['start'])).'&type='.REPORT_TYPE_MONTH , //跳转链接
                        );
                        $data[] = $month;
                    }
                    
                    $return = array(
                        'code' => 0, //  0 - 失败、
                        'message' => '', // code 不为 0 时的错误信息
                        'data' => $data, // code 不为 0 时的错误信息
                    );
                    
                break;
                
                case SERVICE_TYPE_SHUTTLE :
                    $date_start = date('Y-01-01', strtotime($form['date_start']));
                    $date_end = date('Y-12-31', strtotime($date_start));
                    
                    $serviceShuttleList = $this->serviceModel->getServiceShuttleList($form['id'], $date_start, $date_end);
                    
                    foreach($serviceShuttleList as $val){
                        $month = array(
                            'date' =>  date('Ymd', strtotime($val['created'])), //日期
                            'value' => '', //值
                            'url' => APP_SERVICE_TYPE_URL.'?c=parent&a=report&away_id='.$val['id'].'&studentId='.$form['id'].'&type='.REPORT_TYPE_SHUTTLE, //跳转链接
                        );
                        $data[] = $month;
                    }
                    
                    $return = array(
                        'code' => 0, //  0 - 失败、
                        'message' => '', // code 不为 0 时的错误信息
                        'data' => $data, // code 不为 0 时的错误信息
                    );
                    
                break;
                
                case SERVICE_TYPE_STATURE :
                    if($form['type_chart']  == QUERY_TABLE_MONTH){
                        $date_start = date('Y-m-01', strtotime($form['date_start']));
                        $date_end = date('Y-m-d', strtotime("$date_start +1 month -1 day"));
                        $serviceStatureList = $this->serviceModel->getServiceStatureList($form['id'], $date_start, $date_end);
                    
                        foreach($serviceStatureList as $key =>$val){
                            if($key%2 == 0){
                                $month = array(
                                    'date' =>  date('Ymd', strtotime($val['created'])), //日期
                                    'value' => $val['height']/100, //值
                                    'url' => '', //跳转链接
                                );
                                $data[] = $month; 
                            }
                        }
                        $return = array(
                            'code' => 0, //  0 - 失败、
                            'message' => '', // code 不为 0 时的错误信息
                            'data' => $data, // code 不为 0 时的错误信息
                        );
                        
                    }else if($form['type_chart']  ==QUERY_TABLE_YEAR){
                        $date_start = date('Y-01-01', strtotime($form['date_start']));
                        $date_end = date('Y-12-31', strtotime($date_start));
                        $serviceStatureYearList = $this->serviceModel->getServiceStatureYearList($form['id'], $date_start, $date_end);
                        
                        foreach($serviceStatureYearList as $val){
                            $month = array(
                                'date' =>  date('Ym', strtotime($val['created'])), //日期
                                'value' => $val['height']/100, //值
                                'url' => '', //跳转链接
                            );
                            $data[] = $month;
                        }
                        $return = array(
                            'code' => 0, //  0 - 失败、
                            'message' => '', // code 不为 0 时的错误信息
                            'data' => $data, // code 不为 0 时的错误信息
                        );
                        
                    }else if($form['type_chart']  ==QUERY_TABLE_TOTAL){
                        $season = ceil((date('n', strtotime($form['date_start'])))/3);//当月是第几季度
                        //$quarter = date('Y-m-d H:i:s', mktime(0, 0, 0, $season*3-3+1, 1, date('Y'))),"\n"; // 当前季度的1号
                        
                        $serviceStatureQuarterList = $this->serviceModel->getServiceStatureQuarterList($form['id']);
                        $quaterCount = count($serviceStatureQuarterList);
                        foreach($serviceStatureQuarterList as $key => $val){
                            if(date('m', strtotime($val['created']))%3 == 0 || ($key+1) == $quaterCount){
                                $month = array(
                                    'date' =>  date('Ym', strtotime($val['created'])), //日期
                                    'value' => $val['height']/100, //值
                                    'url' => '', //跳转链接
                                );
                                $data[] = $month;
                            }
                            
                        }
                        $return = array(
                            'code' => 0, //  0 - 失败、
                            'message' => '', // code 不为 0 时的错误信息
                            'data' => $data, // code 不为 0 时的错误信息
                        );
                        
                    }else{
                        $return = array(
                            'code' => 1, //  0 - 失败
                            'message' => '上传类型不存在。', // code 不为 0 时的错误信息
                        );
                        
                    }
                    
                    
                    
                break;
                
                case SERVICE_TYPE_WEIGHT :
                    
                    if($form['type_chart']  == QUERY_TABLE_MONTH){
                        $date_start = date('Y-m-01', strtotime($form['date_start']));
                        $date_end = date('Y-m-d', strtotime("$date_start +1 month -1 day"));
                        $serviceStatureList = $this->serviceModel->getServiceStatureList($form['id'], $date_start, $date_end);
                    
                        foreach($serviceStatureList as $key =>$val){
                            if($key%2 == 0){
                                $month = array(
                                    'date' =>  date('Ymd', strtotime($val['created'])), //日期
                                    'value' => $val['weight']/1000, //值
                                    'url' => '', //跳转链接
                                );
                                $data[] = $month; 
                            }
                        }
                        $return = array(
                            'code' => 0, //  0 - 失败、
                            'message' => '', // code 不为 0 时的错误信息
                            'data' => $data, // code 不为 0 时的错误信息
                        );
                        
                    }else if($form['type_chart']  ==QUERY_TABLE_YEAR){
                        $date_start = date('Y-01-01', strtotime($form['date_start']));
                        $date_end = date('Y-12-31', strtotime($date_start));
                        $serviceStatureYearList = $this->serviceModel->getServiceStatureYearList($form['id'], $date_start, $date_end);
                        
                        foreach($serviceStatureYearList as $val){
                            $month = array(
                                'date' =>  date('Ym', strtotime($val['created'])), //日期
                                'value' => $val['weight']/1000, //值
                                'url' => '', //跳转链接
                            );
                            $data[] = $month;
                        }
                        $return = array(
                            'code' => 0, //  0 - 失败、
                            'message' => '', // code 不为 0 时的错误信息
                            'data' => $data, // code 不为 0 时的错误信息
                        );
                        
                    }else if($form['type_chart']  ==QUERY_TABLE_TOTAL){
                        $season = ceil((date('n', strtotime($form['date_start'])))/3);//当月是第几季度
                        //$quarter = date('Y-m-d H:i:s', mktime(0, 0, 0, $season*3-3+1, 1, date('Y'))),"\n"; // 当前季度的1号
                        
                        $serviceStatureQuarterList = $this->serviceModel->getServiceStatureQuarterList($form['id']);
                        $quaterCount = count($serviceStatureQuarterList);
                        foreach($serviceStatureQuarterList as $key => $val){
                            if(date('m', strtotime($val['created']))%3 == 0 || ($key+1) == $quaterCount){
                                $month = array(
                                    'date' =>  date('Ym', strtotime($val['created'])), //日期
                                    'value' => $val['weight']/1000, //值
                                    'url' => '', //跳转链接
                                );
                                $data[] = $month;
                            }
                            
                        }
                        $return = array(
                            'code' => 0, //  0 - 失败、
                            'message' => '', // code 不为 0 时的错误信息
                            'data' => $data, // code 不为 0 时的错误信息
                        );
                        
                    }else{
                        $return = array(
                            'code' => 1, //  0 - 失败
                            'message' => '上传类型不存在。', // code 不为 0 时的错误信息
                        );
                        
                    }
                break;
                
                case SERVICE_TYPE_TEMPERATURE :
                    $date_start = $form['date_end'] ? $form['date_start'] : date('Y-m-d', strtotime($form['date_start']));
                    $time = strtotime($date_start);
                    $date_end = $form['date_end'] ? $form['date_end'] : date("Y-m-d",mktime(0, 0 , 0,date("m", $time),date("d", $time)-14,date("Y", $time)));
                    
                    $serviceStatureList = $this->serviceModel->getServiceStatureList($form['id'], $date_start, $date_end);
                    
                    foreach($serviceStatureList as $val){
                        $month = array(
                            'date' =>  date('Ymd', strtotime($val['created'])), //日期
                            'value' => $val['temperature'].'℃', //值
                            'url' => '', //跳转链接
                        );
                        $data[] = $month;
                    }
                    
                    $return = array(
                        'code' => 0, //  0 - 失败、
                        'message' => '', // code 不为 0 时的错误信息
                        'data' => $data, // code 不为 0 时的错误信息
                    );
                    
                break;
                
                case SERVICE_TYPE_IMG :
                    $date_start = $form['date_end'] ? $form['date_start'] : date('Y-m-01', strtotime($form['date_start']));
                    $date_end = $form['date_end'] ? $form['date_end'] : date('Y-m-d', strtotime("$date_start +1 month -1 day"));
                    
                    $serviceImgList = $this->serviceModel->getServiceImgList($form['id'], $date_start, $date_end);
                    
                    foreach($serviceImgList as $val){
                        $month = array(
                            'date' =>  date('Ymd', strtotime($val['created'])), //日期
                            'value' => '', //值
                            'url' => APP_RESOURCE_URL   . $val['file_path'], //跳转链接
                        );
                        $data[] = $month;
                    }
                    
                    $return = array(
                        'code' => 0, //  0 - 失败、
                        'message' => '', // code 不为 0 时的错误信息
                        'data' => $data, // code 不为 0 时的错误信息
                    );
                    
                break;
                
                
                default:
                
                    $return = array(
                        'code' => 1, //  0 - 失败
                        'message' => '上传类型不存在。', // code 不为 0 时的错误信息
                    );
                break;
            }
            
            
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
    */
    
    /**
     * @description  ✔ 提交学生未读消息
     * @param name=应用ID var=app_id type=int required=true remark=应用ID
     * @param name=验证KEY var=key type=string required=true remark=格式：AES（授权key+当前时间戳）
     * @param name=学生ID var=student_id type=int required=true remark=学生ID
     * @param name=消息内容 var=content type=string required=true remark=消息内容
     * @param name=跳转url var=url type=string required=true remark=消息跳转的链接（完整的链接地址）
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
            'id' => 111, //消息ID
          )
        )
     */
    function submitStudentMessageAction() {
        
        $request = $this->rawData2Arr();
        
        if($this->serviceModel->validStudentMessage($request, $errors)){
            if($application = $this->serviceModel->getApplication($request['app_id'])){
                
                $BraveCrypt = $this->load(EXTEND, 'BraveCrypt');
                $BraveCrypt->init();
                //$qrcode_content = "1+".time();
                //$qrcode_aes = $BraveCrypt->encrypt($qrcode_content, $application['aes_key'], $application['aes_iv']);
                
                $key = $BraveCrypt->decrypt($request['key'], $application['aes_key'], $application['aes_iv']);
                $app_res = $BraveCrypt->decrypt($application['access_key'], $application['aes_key'], $application['aes_iv']);
                
                $key_e = explode('+', $key);
                $app_key = $key_e[0];
                $app_time = $key_e[1];
                
                if($app_res == $app_key && $app_time <= time()+5*60 && $app_time >= time()-5*60){
                    $data = array(
                        'student_id' => $request['student_id'],
                        'content' => $request['content'],
                        'url' => $request['url'],
                        'app_id' => $request['app_id'],
                    );
                    $id = $this->serviceModel->sverStudentMessage($data);
                    
                    $return = array(
                        'code' => 0, //  0 - 失败
                        'message' => '', // code 不为 0 时的错误信息
                        'data' => array(
                            'id' => $id, //消息ID
                        )
                    );
                    
                }else{
                    $return = array(
                        'code' => 2, //  0 - 失败
                        'message' => '授权key值错误或者验证时间超时。', // code 不为 0 时的错误信息
                    );
                }
                
            }
             else{
                $return = array(
                    'code' => 1, //  0 - 失败
                    'message' => '应用id不存在。', // code 不为 0 时的错误信息
                );
              
            }
             
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
     * @description  ✔ 获取服务器时间
     * @return
     * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
            'time' => 20160606060606, //当前系统时间
          )
        )
     */
    function getSystemTimeAction() {
        $return = array(
            'code' => 0,
            'message' => '',
            'data' => array(
                'time' => date('YmdHis'),
            ),
        );
        echo $this->json_encode($return);
        exit();
    }
    /**
    * @description  ✔ 获取设备程序信息
    * @param name=设备编号 var=device_no type=string required=true remark=当前设备的编号
    * @param name=设备型号 var=model type=string required=true remark=当前设备的型号
    * @param name=目标系统 var=system_type type=int required=true remark=ID关系:1-robot_android、2-robot_linux、3-gates_linux
    * @param name=当前版本 var=version type=string required=false remark=当前版本,可以为空
    * @return
    * array(
          'code' => 0, //  0 - 成功
          'message' => '', // code 不为 0 时的错误信息
          'data' => array(
            'version' => "1.2.1", //main_version,branch_version，安装完成写入配置表
            'version_url' => "http://www.didano.cn/res/upload/1.zip", //版本URL
            'version_details' => "测试", //版本说明
            'version_type'  => 0 //0,主版本，1，分支版本，安装完成写入配置表
          )
        )
    */
    function getVersionAction() {

        $json = file_get_contents("php://input");
        $request = json_decode($json);
        
            $getVersion = array();
            $data = array(
                'service_no' => $request->device_no,       // 设备编号
                'version' => $request->version,      // 设备当前版本
                'model' => $request->model,      //  设备型号
                'version_type' => 0,    // 当前版本类型
                'system_type' => $request->system_type,  // 目标系统
                );
        if($this->serviceModel -> validPutVersion($data, $errors)){
            $data1 = array(
                'service_no' => $data['service_no'],       // 设备编号
                'version' => $data['version'],      // 设备当前版本
                'model' => $data['model'],      //  设备型号
                'version_type' => 0,    // 当前版本类型
                );
            if($this->serviceModel -> savedevice($data1)){
                $data2 = array('system_type' => $data['system_type']);
                $getVersion = $this->serviceModel -> getVersion($data2);
                
                if($getVersion){
                    $getVersion['version_url'] = APP_OSS_URL . $getVersion['version_url'];
                    $return = array(
                    'code' => 0, //  0 - 成功
                    'message' => '', // code 不为 0 时的错误信息
                    'data' => $getVersion,
                    );
                }else{
                    $return = array(
                    'code' => 2, //  0 - 成功
                    'message' => '数据库读取失败', // code 不为 0 时的错误信息
                    );
                }
            }else{
                $return = array(
                    'code' => 3, //  0 - 成功
                    'message' => '数据库写入失败', // code 不为 0 时的错误信息
                    );   
            }
        }else{
            $return = array(
                'code' => 1, //  0 - 失败
                'message' => implode(';', $errors), // code 不为 0 时的错误信息
            );
        }
        
        echo $this->json_encode($return);
        exit();
    }

    //***结束分割线***//
}
?>
