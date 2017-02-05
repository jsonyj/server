<?php

/**
 * @desc 签到签退model
 */

class SignModel extends AppModel {

    /**
    * @desc 签到一览
    * @author
    */
    function getSignList($sh, $all=FALSE) {

        $this->setSearch($sh);
        $sql = "
        SELECT r.*,s.type AS type,s.name AS name,s.phone AS phone FROM tb_staff_signdate r
        LEFT JOIN tb_staff s on r.staff_id = s.id
        AND s.status = 1
        AND s.deleted = 0
        WHERE r.deleted = 0
        ";

        $this->where($sql, 's.id', 'staffId', '=', 'AND');
        $this->where($sql, 's.school_id', 'school_id', '=');
        $this->where($sql, 'r.sign_timestamp', 'start_timestamp', '>=', 'AND');
        $this->where($sql, 'r.sign_timestamp', 'end_timestamp', '<=', 'AND');
        $this->where($sql, 'r.sign_status', 'sign_status', 'in');

        if ($all) {
            $this->order($sql, 'order.sign');
            $rs = $this->getAll($sql);
        }
        else {
            $countSql = "
                    SELECT COUNT(*) AS count,s.type AS type
                    FROM tb_staff_signdate AS r
                    LEFT JOIN tb_staff s on r.staff_id = s.id
                    WHERE r.deleted = 0
                    AND s.status = 1
                    AND s.deleted = 0
            ";
            $this->where($countSql, 's.id', 'staffId', '=', 'AND');
            $this->where($countSql, 's.school_id', 'school_id', '=');
            $this->where($countSql, 'r.sign_timestamp', 'start_timestamp', '>=', 'AND');
            $this->where($countSql, 'r.sign_timestamp', 'end_timestamp', '<=', 'AND');
            $this->where($countSql, 'r.sign_status', 'sign_status', 'in');



            $this->paging('paging.sign');
            $this->order($sql, 'order.sign');
            $rs = $this->paginate($sql,$countSql);
        }
        return $rs;
    }

    /**
     * @desc 修改签到、签退时间
     * @author wjd
     */
    function getSaveSign($form) {
        $table = 'tb_staff_signdate';
        $data = array(
            'in_time' => $form['in_time'],
            'out_time' => $form['out_time'],
        );

        if (($form['id'] > 0)) {
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

    /**
     * @desc 验证
     * @author wjd
     */
    function validSign($form, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'title' => array(
                array('isNotNull', '请输入类型名称'),
            ),
            'in_time' => array(
                array('isNotNull', '请输入签到时间'),
            ),
            'out_time' => array(
                array('isNotNull', '请选输入签退时间'),
            )
        );

        if (!$validator->valid($config, $form)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }

    /**
     * @desc 回调单条信息
     * @author wjd
     */
     function getSignById($id) {
        $this->escape($id);
        $sql = "
        SELECT r.*,s.type,s.name AS name,s.phone AS phone
        FROM tb_staff_signdate r
        LEFT JOIN tb_staff s ON r.staff_id = s.id
        WHERE s.deleted = 0
        AND s. STATUS = 1
        AND r.deleted = 0
        AND r.id= '{$id}'
        ";

        $rs = $this->getOne($sql);
        return $rs;
    }

    /**
    * @desc 考勤列表
    * @author
    */
    function getTypeList($sh,$all) {
        $this->setSearch($sh);
        $sid = $_SESSION[SESSION_USER]["school_id"];
        $sql = "
            SELECT * FROM tb_sign_type
            WHERE school_id = '{$sid}'
            AND deleted = 0
            ";
        if($all){
            $this->order($sql, 'order.default');
            $rs = $this->getAll($sql);
        }else{
            $countSql = "
                SELECT COUNT(*) AS count
                FROM tb_sign_type
                WHERE school_id = '{$sid}'
                AND deleted = 0
                ";
            $this->paging('paging.sign');
            $this->order($sql, 'order.default');
            $rs = $this->paginate($sql,$countSql);
        }
        return $rs;
    }

    /**
     * @desc 新增、修改考勤
     * @author wjd
     */
    function getSaveType($form) {
        $table = 'tb_sign_type';
        $data = array(
            'school_id' => $_SESSION["XIAONUO_SCHOOL_SESSION_USER"]["school_id"],
            'title' => '',
            'in_time' => $form['in_time'],
            'out_time' => $form['out_time'],
        );


        if (($form['id'] > 0)) {
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

    /**
     * @desc 回调单条考勤信息
     * @author wjd
     */
     function getTypeById($id) {
        $this->escape($id);
        $sql = "
            SELECT * FROM tb_sign_type
            WHERE deleted = 0
            AND id= '{$id}'
        ";

        $rs = $this->getOne($sql);
        return $rs;
    }

    /**
     * @desc 删除考勤
     * @author wjd
     */
     function deleteType($id) {
         $this->escape($id);
         $table = "tb_sign_type";
         $where = "id = '{$id}'";
         $data = array(
             'deleted' => 1
         );
         return $this->Update($table, $data, $where);
     }



     /*增加导出功能*/
     function exportSign($sh){
        $this->setSearch($sh);
        $sql = "
        SELECT r.*,s.type AS type,s.name AS name,s.phone AS phone FROM tb_staff_signdate r
        LEFT JOIN tb_staff s on r.staff_id = s.id
        AND s.status = 1
        AND s.deleted = 0
        WHERE r.deleted = 0
        ";

        $this->where($sql, 's.type', 'type', '=', 'AND');
        $this->where($sql, 's.school_id', 'school_id', '=');
        $this->where($sql, 'r.sign_timestamp', 'start_timestamp', '>=', 'AND');
        $this->where($sql, 'r.sign_timestamp', 'end_timestamp', '<=', 'AND');
        $this->where($sql, 's.id', 'staffId', '=', 'AND');
        $this->where($sql, 'r.sign_status', 'sign_status', 'in');
        $this->order($sql, 'order.sign');
        $rs = $this->getAll($sql);
        return $rs;
     }

     


}

?>
