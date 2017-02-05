<?php

class ActivityModel extends AppModel {
	function getActivityList(){
		$sql = "SELECT * FROM  tb_activity WHERE deleted = 0";
		$rs = $this->getAll($sql);
		return $rs;
	}



    function validSaveActivity($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'title' => array(
                array('isNotNull', '请输入活动名称'),
            ),
            'time' => array(
                array('isNotNull', '请输入活动时间'),
            ),
            'start' => array(
                array('isNotNull', '请输入结束时间'),
            ),
            'end' => array(
                array('isNotNull', '请输入开始时间'),
            )
        );
        
        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }
        return true;
    }
	function saveActivity($form){
		$table = 'tb_activity';
        $data = array(
            'name' => $form['title'],
            'activity_time' => $form['time'],
            'start_time' => $form['start'],
            'end_time' => $form['end'],
            'activity_des' => $form['des'],
            'sponsor_name' => '',
        );
        $data['created'] = NOW;
        return $this->Insert($table, $data);
	}
	 function deleteActivity($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_activity', $data, $where);
    }

    function getGiftList(){
    	$sql = "SELECT * FROM  tb_gift WHERE deleted = 0";
		$rs = $this->getAll($sql);
		return $rs;
    }


    function validGift($form, &$errors) {
        $valid = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'name' => array(
                array('isNotNull','礼物名字不能为空')
            )
        );

        if (!$valid->valid($config, $form)) {
            $errors = $this->langs($valid->getError());
            return false;
        }
        return true;
     }

    function getGiftById($id) {
        $this->escape($id);
        $sql = "SELECT * FROM tb_gift WHERE DELETED = 0 AND id ='{$id}'";
        $rs = $this->getOne($sql);
        return $rs;
    }

    function getActivityById($id) {
        $this->escape($id);
        $sql = "SELECT * FROM tb_activity WHERE DELETED = 0 AND id ='{$id}'";
        $rs = $this->getOne($sql);
        return $rs;
    }


    function saveGift($form){
    	$this->escape($form);
        $table = "tb_gift";
        $data = array(
            'gift_name' => $form['name'],
        );
        if ($form['id']>0) {
            $where = "id='{$form['id']}'";
            if (!$this->Update($table, $data, $where)) {
                return false;
            }
            return $form['id'];
         }
        $data['created'] = NOW;
        return $this->Insert($table, $data);
    }

    function saveGiftImg($file, $id){
    	$table = "tb_gift";
        $data = $file;
        $where = "id= {$id}";
        return $this->Update($table, $data, $where);
    }


    function deleteGift($id) {
         $this->escape($id);
         $table = "tb_gift";
         $data = array(
             'deleted' =>APP_UNIFIED_TRUE
         );
         $where = "id = '{$id}'";
         return $this->Update($table, $data, $where);
     }

     function deleteGiftImg($id) {
         $this->escape($id);
         $table = "tb_gift";
         $data = array(
             'gift_img' => ''
         );
         $where = "id = '{$id}'";
         $this->Update($table, $data, $where);

     }



}
?>