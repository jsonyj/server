<?php

/**
 *
 */
class WeichatPushMessageModel extends AppModel {

     function genDayReportMessage($form) {
         $data = array();

         $data['touser'] = $form['open_id'];
         $data['template_id'] = WX_TMP_ID_DETECTION;
         $type = REPORT_TYPE_DAY;

         $data['url'] = WX_APP_URL . "?c=parent&a=report&type={$type}&studentId={$form['student']['id']}&id={$form['day_report_id']}&full=true";

         //组装数据
         $data['data']['first'] = array(
              'value' => "尊敬的家长，您的宝贝已经安全到园，以下是来自小诺健康机器人的报告",
              'color' => "#000000"
         );

         $data['data']['keyword1'] = array(
             'value' => $form['device']['school_title'],
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
    
    /**
     * @desc 保存孩子接走微信推送消息
     * @author   wei
     */
    function genStudentTakeAwayMessage($form){
        $data = array();
        
        $data['touser'] = $form['open_id'];
        $data['template_id'] = WX_TMP_ID_DETECTION;
        $type = REPORT_TYPE_SHUTTLE;

        $data['url'] = WX_APP_URL . "?c=parent&a=report&type={$type}&away_id={$form['day_report_id']}&studentId={$form['student']['id']}｝&full=true";
         
        //组装数据
        $data['data']['first'] = array(
            'value' => "尊敬的家长，您的宝贝已经放学，被{$form['parent_title']}接走，请知晓。",
            'color' => "#000000"
        );

        $data['data']['keyword1'] = array(
            'value' => $form['device']['school_title'],
            'color' => "#000000"
        );


        $data['data']['keyword2'] = array(
            'value' => "放学通知",
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
            'value' => "详细情况请点击查看",
            'color' => "#7B68EE"
        );

        return $data;
    }
}

?>
