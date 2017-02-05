<?php

/**
 * @desc 微信消息推送类
 * @author lxs 
 */
class WeichatPushMessageModel extends AppModel {
    
    /**
     * @desc 绑定成功后保存微信消息给其他家长
     * @author lxs
     */
    function genBindSuccessMessage($form) {
        $data = array();
        
        $data['touser'] = $form['open_id'];
        $data['template_id'] = WX_TMP_ID_BINDSUCCESS;
        $data['url'] = WX_APP_URL . "?c=group&a=index&id={$form['student_id']}";
         
        //组装数据
        $data['data']['first'] = array(
            'value' => "您好，手机号为{$form['phone']}的新用户已经绑定到{$form['student_name']}小朋友账户，该新用户将会看到您家小朋友的信息，请知晓！",
            'color' => "#000000"
        );

        $data['data']['keyword1'] = array(
            'value' => $form['student_name'],
            'color' => "#000000"
        );

        $data['data']['keyword2'] = array(
            'value' => "如果您对此新用户存在疑问或不想其看到小朋友的信息，可以在家庭成员管理中将他删除。",
            'color' => "#000000"
        );

        $data['data']['remark'] = array(
            'value' => "点击进入家庭成员管理",
            'color' => "#7B68EE"
        );

        return $data;
    }
    
    
    /**
     * @desc 生成绑定微信用户验证其他用户申请绑定信息推送
     * @author lxs
     */
    function genValidBindApplyMessage($form) {
        $data = array();
        
        $data['touser'] = $form['open_id'];
        $data['template_id'] = WX_TMP_ID_BINDSUCCESS;
        $data['url'] = WX_APP_URL . "?c=bind&a=bindDetail&id={$form['apply_id']}&key={$form['bind_key']}";
         
        //组装数据
        $data['data']['first'] = array(
            'value' => "您好，手机号为{$form['phone']}的用户申请绑定为{$form['student_name']}小朋友的{$form['relation_title']}",
            'color' => "#000000"
        );

        $data['data']['keyword1'] = array(
            'value' => $form['student_name'],
            'color' => "#000000"
        );

        $data['data']['keyword2'] = array(
            'value' => "绑定后他将能看到小朋友的个人信息！如果同意，请点击授权通过；如果不同意，请点击拒绝。",
            'color' => "#000000"
        );

        $data['data']['remark'] = array(
            'value' => "点击查看详情",
            'color' => "#7B68EE"
        );

        return $data;
    }
    
    
    /**
     * @desc 申请通过后向申请者生成绑定成功通知
     * @author lxs
     */
    function genBindApplySuccessMessage($form) {
        $data = array();
        
        $data['touser'] = $form['open_id'];
        $data['template_id'] = WX_TMP_ID_BINDSUCCESS;
        $data['url'] = WX_APP_URL . "?c=parent&a=index";
         
        //组装数据
        $data['data']['first'] = array(
            'value' => "您好，
            {$form['student_name']}小朋友的{$form['relation_title']}已经同意你的绑定申请",
            'color' => "#000000"
        );

        $data['data']['keyword1'] = array(
            'value' => $form['student_name'],
            'color' => "#000000"
        );

        $data['data']['keyword2'] = array(
            'value' => "绑定成功",
            'color' => "#000000"
        );

        $data['data']['remark'] = array(
            'value' => "点击进入宝贝中心",
            'color' => "#7B68EE"
        );

        return $data;
    }
    
    /**
     * @desc 申请被拒后向申请者生成绑定成功通知
     * @author lxs
     */
    function genBindApplyRefuesMessage($form) {
        $data = array();
        
        $data['touser'] = $form['open_id'];
        $data['template_id'] = WX_TMP_ID_BINDSUCCESS;
        $data['url'] = WX_APP_URL . "?c=bind&a=index";
         
        //组装数据
        $data['data']['first'] = array(
            'value' => "您好，
            {$form['student_name']}小朋友的{$form['relation_title']}已经拒绝你的绑定申请",
            'color' => "#000000"
        );

        $data['data']['keyword1'] = array(
            'value' => $form['student_name'],
            'color' => "#000000"
        );

        $data['data']['keyword2'] = array(
            'value' => "绑定成功",
            'color' => "#000000"
        );

        $data['data']['remark'] = array(
            'value' => "点击重新绑定",
            'color' => "#7B68EE"
        );

        return $data;
    }

    function saveMessage($form) {
        $table = 'tb_weichat_push';
        $data = array(
            'open_id' => $form['open_id'],
            'message' => serialize($form['message']),
            'send_time' => $form['send_time'],
        );

        $data['created'] = NOW;
        return $this->Insert($table, $data);
    }
    
    function genDayReportMessage($form) {
         $data = array();

         $data['touser'] = $form['open_id'];
         $data['template_id'] = WX_TMP_ID_DETECTION;
         $type = REPORT_TYPE_DAY;

         $data['url'] = WX_APP_URL . "?c=parent&a=report&type={$type}&studentId={$form['studentId']}&id={$form['day_report_id']}&full=true";

         //组装数据
         $data['data']['first'] = array(
              'value' => "尊敬的家长，您的宝贝已经安全到园，以下是来自小诺健康机器人的报告",
              'color' => "#000000"
         );

         $data['data']['keyword1'] = array(
             'value' => $form['school_title'],
             'color' => "#000000"
         );

         $report = ($form['detection']['temperature'] < $form['detection']['temperature_threshold']) ? '体温未见异常' : '体温偏高，请家长留意！';

         $data['data']['keyword2'] = array(
             'value' => "当日晨检信息 {$report}",
             'color' => "#7B68EE"
         );

         $data['data']['keyword3'] = array(
             'value' => $form['student']['name'],
             'color' => "#000000"
         );

         $data['data']['keyword4'] = array(
             'value' => '小诺健康机器人',
             'color' => "#000000"
         );

         $data['data']['keyword5'] = array(
             'value' => date('H:i', time()),
             'color' => "#7B68EE"
         );

         $data['data']['remark'] = array(
             'value' => "详细晨检结果和宝贝靓照请点击详情",
             'color' => "#7B68EE"
         );

         return $data;
    }

    /*园长消息微信推送*/
    function genImportantNotice($form){
        $data = array();
        $data['touser'] = $form['open_id'];
        $data['template_id'] = WX_TMP_ID_DETECTION;
        $data['url'] = WX_APP_URL . "?c=public&a=messageIndex&studentId={$form['student_id']}";
         
        //组装数据
        $data['data']['first'] = array(
            'value' => "亲爱的家长，{$form['school_title']}学校园长给你发了一条校园通知，请查看！",
            'color' => "#000000"
        );
        $data['data']['keyword1'] = array(
             'value' => $form['school_title'],
             'color' => "#000000"
         );
        $data['data']['keyword2'] = array(
            'value' => "园长通知",
            'color' => "#7B68EE"
        );
        $data['data']['keyword3'] = array(
            'value' => $form['student_name'],
            'color' => "#000000"
        );
        $data['data']['keyword4'] = array(
            'value' => $form['staff_name'],
            'color' => "#000000"
        );
        $data['data']['keyword5'] = array(
            'value' => date('H:i', time()),
            'color' => "#7B68EE"
        );
        $data['data']['remark'] = array(
             'value' => "详细情况请点击查看",
             'color' => "#7B68EE"
         );
        // $this->log('data:'.print_r($data, true), 'data');
        return $data;
    }


    
}

?>
