<?php

class ChristmasModel extends AppModel {
   
/*获取对应学生受到的礼物数量*/
	function getGettingGiftNum($studentId){
		$this->escape($studentId);
		$sql = "SELECT gift_num FROM tb_gift_getting WHERE getting_studentId = '{$studentId}'";
		$rs = $this->getOne($sql);
		return $rs;
	}


	function getGettingGift($studentId){
		$this->escape($studentId);
		$sql = "SELECT * FROM  tb_gift_getting_giving WHERE getting_studentId = '{$studentId}'";
		$rs = $this->getAll($sql);
		return $rs;
	}

	/*获取礼物列表*/
	function getGift(){
		$sql = "SELECT * FROM  tb_gift WHERE deleted = 0";
		$rs = $this->getAll($sql);
		return $rs;
	}

	// 获取送礼剩余数量
	function getGivingNum($studentId){
		$sql = "SELECT * FROM  tb_gift_giving WHERE giving_studentId = '{$studentId}' AND deleted = 0";
		$rs = $this->getOne($sql);
		$rs['gift_num'] = 3 - $rs['gift_num'];
		return $rs['gift_num'];
	}


	function saveGivingGift($form){
		$table = 'tb_gift_getting_giving';
        $data = array(
            'getting_studentId' => $form['getting_studentId'],
            'getting_studentName' => $form['getting_studentName'],
            'giving_studentId' => $form['giving_studentId'],
            'giving_studentName' => $form['giving_studentName'],
            'giving_studentImg' => $form['giving_studentImg'],
            'gift_id' => $form['gift_id'],
            'gift_name' => $form['gift_name'],
            'gift_img' => $form['gift_img'],
            'activity_id' => '', 
            'activity_name' => '',
        );
        $data['created'] = NOW;
        return $this->Insert($table, $data);
    }

    function saveGivingNum($form){
    	$table = 'tb_gift_giving';
    	$data = array(
    		'activity_id' => $form['activity_id'], 
    		'giving_studentId' => $form['giving_studentId'],
    		);
    	$data['created'] = NOW;
    	

    	$sql = "SELECT * FROM  tb_gift_giving WHERE giving_studentId = '{$form['giving_studentId']}'";
    	$rs = $this->getOne($sql);
    	if($rs){
    		if($rs['gift_num'] > 3){
    			return false;
    		}else{
	    		$data['gift_num'] = $rs['gift_num']+$form['classmatesNum'];
	            $whereSql = "giving_studentId = '{$form['giving_studentId']}'";
	            return $this->Update($table, $data, $whereSql);
	        }
    	}else{
    		$data['gift_num'] = $form['classmatesNum'];
    		return $this->Insert($table,$data);
    	}

    }

    function saveGettingNum($form){
    	$table = 'tb_gift_getting';
    	$data = array(
    		'activity_id' => $form['activity_id'],
    		'getting_studentId' => $form['getting_studentId'],
    		);
    	$data['created'] = NOW;

    	$sql = "SELECT * FROM  tb_gift_getting WHERE getting_studentId = '{$form['getting_studentId']}'";
    	$rs = $this->getOne($sql);
    	if($rs){
    		$data['gift_num'] = $rs['gift_num'] + $form['giftsNum'];
    		$whereSql = "getting_studentId = '{$form['getting_studentId']}'";
    		return $this->Update($table,$data,$whereSql);
    	}else{
    		$data['gift_num'] = $form['giftsNum'];
    		return $this->Insert($table,$data);
    	}

    }
}

?>
