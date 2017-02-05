<?php

class AdminModel extends AppModel {

    function login($form, &$errors) {
        $this->escape($login);
        $login = $form['login'];
        $password = md5($form['password']);
        $schoolTitle = $form['school_title'];
        $sql = "
            SELECT tb_school_admin.*, tb_school.id AS school_id, tb_school.title AS school_title, tb_school.phone AS school_phone, tb_school.address AS school_address
            FROM tb_school
            LEFT JOIN tb_school_admin ON tb_school.id = tb_school_admin.school_id
            WHERE
              tb_school_admin.login = '{$login}' AND tb_school_admin.password = '{$password}' AND tb_school_admin.deleted = 0
              AND tb_school.title = '{$schoolTitle}' AND tb_school.deleted = 0
            LIMIT 1
        ";
        
        if (!$rs = $this->getOne($sql)) {
            $errors['login'] = '登录账号或密码不正确，请重新输入';
            return false;
        }
        
        if ($rs['status'] != 1) {
            $errors['login'] = '账号已被禁用，请联系管理员';
            return false;
        }

        $this->setSession(SESSION_ROLE, ACT_SCHOOL_ADMIN_ROLE);
        $this->setSession(SESSION_USER, $rs);
        return true;
    }
    
    function adminList($sh, $all = false) {
        $this->setSearch($sh);
        $sql = "
            SELECT * FROM tb_school_admin
            WHERE deleted = 0 AND role_id <> " . ACT_ADMIN_ROLE . " ";
        
        $this->where($sql, 'account', 'account', 'lk');
        $this->where($sql, 'name', 'name', 'lk');
        $this->where($sql, 'role_id', 'role_id', '=');
        
        if ($all) {
            $this->order($sql, 'order.admin');
            $rs = $this->getAll($sql);
        }
        else {
            $this->paging('paging.admin');
            $this->order($sql, 'order.admin');
            $rs = $this->paginate($sql);
        }
        
        return $rs;
    }

    function admin($id) {
        $this->escape($id);
        $sql = "
            SELECT * FROM tb_school_admin
            WHERE id = '{$id}' AND deleted = 0
            LIMIT 1
        ";
        
        return $this->getOne($sql);
    }
    
    function validAdminChange($admin, &$errors) {
        $validator = $this->load(APP_CORE, 'AppValidator');
        $config = array(
            'password' => array(
                array('isNotNull', '请输入密码'),
                array('isSame', '确认密码不一致', array('compare' => 'password_confirm')),
            ),
            'password_confirm' => array(
                array('isNotNull', '请输入确认密码'),
            )
        );
        
        if (!$validator->valid($config, $admin)) {
            $errors = $this->langs($validator->getError());
            return false;
        }

        return true;
    }
    
    function saveAdmin($admin) {
        $table = 'tb_school_admin';
        $data = array(
            'account' => $admin['account'],
            'name' => $admin['name'],
            'role_id' => $admin['role_id'],
            'status' => $admin['status'] ? $admin['status'] : 0,
        );
        
        if (strlen($admin['password'])) {
            $data['password'] = md5($admin['password']);
        }
        
        if ($admin['id'] > 0) {
            $where_id = $admin['id'];
            $this->escape($where_id);
            $where = "id = '{$where_id}'";
            $this->Update($table, $data, $where);
            return $admin['id'];
        }
        else {
            $data['created'] = NOW;
            return $this->Insert($table, $data);
        }
    }
    
    function deleteAdmin($id) {
        $this->escape($id);
        $data = array('deleted' => 1);
        $where = "id = '{$id}'";
        return $this->Update('tb_school_admin', $data, $where);
    }

    function updateAdminPwd($id, $pwd) {
        $this->escape($id);
        
        $pwd = trim($pwd);
        $data = array('password' => md5($pwd));
        $where = "id = '{$id}' AND deleted = 0";
        
        return $this->Update('tb_school_admin', $data, $where);
    }
}

?>
