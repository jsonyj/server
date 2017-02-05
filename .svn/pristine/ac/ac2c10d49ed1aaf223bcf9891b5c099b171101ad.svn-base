<?php

/**
 *
 */
class WeichatPushMessageModel extends AppModel {

    function getMessageList() {
        $sql = "SELECT * FROM tb_weichat_push WHERE deleted = 0 AND status = 0 AND NOW() >= send_time ORDER BY created ASC LIMIT 100";
        return $this->getAll($sql);
    }

    function updateMessageStatus($id, $form) {
        $table = 'tb_weichat_push';
        $data = array(
            'status' => $form['status'],
        );

        if(isset($form['result'])) {
            $data['result'] = $form['result'];
        }

        if(isset($form['data'])) {
            $data['data'] = $form['data'];
        }

        $this->escape($id);
        $where = "id = '{$id}'";
        $this->Update($table, $data, $where);

    }

    function genWeekReportMessage($form) {
         $data = array();

         $data['touser'] = $form['open_id'];
         $data['template_id'] = WX_TMP_ID_DETECTION;

         $data['url'] = WX_APP_URL . "?c=report&a=week&studentId={$form['student']['id']}&id={$form['week_report_id']}&full=true";

         //组装数据
         $data['data']['first'] = array(
              'value' => "尊敬的家长，以下是来自小诺健康机器人的晨检周报",
              'color' => "#000000"
         );

         $data['data']['keyword1'] = array(
             'value' => $form['school']['title'],
             'color' => "#000000"
         );

         $data['data']['keyword2'] = array(
             'value' => "晨检周报",
             'color' => "#7B68EE"
         );

         $data['data']['keyword3'] = array(
             'value' => $form['student']['name'],
             'color' => "#7B68EE"
         );

         $data['data']['keyword4'] = array(
             'value' => '健康机器人小诺',
             'color' => "#7B68EE"
         );

         $data['data']['keyword5'] = array(
             'value' => $form['send_time'],
             'color' => "#7B68EE"
         );

         $data['data']['remark'] = array(
             'value' => "详细晨检周报请点击查看",
             'color' => "#7B68EE"
         );

         return $data;
    }

    function genMonthReportMessage($form) {
         $data = array();

         $data['touser'] = $form['open_id'];
         $data['template_id'] = WX_TMP_ID_DETECTION;

         $data['url'] = WX_APP_URL . "?c=report&a=month&studentId={$form['student']['id']}&id={$form['month_report_id']}&full=true";

         //组装数据
         $data['data']['first'] = array(
              'value' => "尊敬的家长，以下是来自小诺健康机器人的晨检月报",
              'color' => "#000000"
         );

         $data['data']['keyword1'] = array(
             'value' => $form['school']['title'],
             'color' => "#000000"
         );

         $data['data']['keyword2'] = array(
             'value' => "每月成长报告",
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
             'value' => $form['send_time'],
             'color' => "#7B68EE"
         );

         $data['data']['remark'] = array(
             'value' => "看看这个月宝贝的成长情况，点击详情详查看",
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
}

?>
