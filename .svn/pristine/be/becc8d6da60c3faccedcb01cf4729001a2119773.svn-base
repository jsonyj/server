<?php

class SchoolParentModel extends AppModel {

	/**
	 * @desc 保存家长
	 * @author ly
	 * @param $form
	 */
    function saveSchoolParent($form) {
        $table = 'tb_school_parent';
        $data = array(
            'name' => $form['name'] ? $form['name'] : '',
            'school_id' => $form['school_id'],
            'phone' => $form['phone'],
            'type' => $form['type'] ? $form['type'] : 0,
            'status' => $form['status'] ? false : true,
        );

        if ($form['id'] > 0) {
            $whereId = $form['id'];
            $this->escape($whereId);
            $whereSql = "id = '{$whereId}'";
            $this->Update($table, $data, $whereSql);
            return $form['id'];
        }
        else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
    
}

?>
