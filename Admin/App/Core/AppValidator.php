<?php

class AppValidator extends BraveValidator {

    function isAdminExist($field, $vars) {
        $id = (int)$this->data['id'];
        $this->escape($id);
        $login = $this->data[$field];
        
        $sql = "
            SELECT * FROM tb_admin 
            WHERE account = '{$login}' AND id <> '{$id}' AND deleted = 0
        ";
        
        $rs = $this->getOne($sql);
        return $rs? false: true;
    }

    function isUploadValid($field, $vars) {
        $data = $this->data[$field];
        return isset($data['save'])? true: false;
    }

    function isTelCode($field, $vars) {
        $regex = "/^1[0-9]{10}$/";
        //return preg_match($regex, $this->data[$field]);
        return $this->commonCompare($field,$vars,$regex);
    }

    function isPromotionUpload($field, $vars) {
        $valid = false;
        $id = isset($this->data['id']) ? $this->data['id'] : 0;
        if($id) {
            if(isset($this->data['deleted_' . $field]) && $this->data['deleted_' . $field]) {
                $valid = isset($this->data[$field]) && $this->data[$field];
            } else {
                $valid = true;
            }
        } else {
            $valid = isset($this->data[$field]) && $this->data[$field];
        }
        return $valid;
    }

    function isSupplierNameExist($field, $vars) {
        $id = $this->data['id'];
        $this->escape($id);
        $name = $this->data[$field];
        
        $sql = "
            SELECT * FROM tb_suppliers
            WHERE name = '{$name}' AND id <> '{$id}' AND deleted = 0
        ";
        $rs = $this->getOne($sql);
        return $rs? false: true;
    }

    function isSupplierTitleExist($field, $vars) {
        $id = $this->data['id'];
        $this->escape($id);
        $title = $this->data[$field];

        $sql = "
            SELECT * FROM tb_suppliers
            WHERE title = '{$title}' AND id <> '{$id}' AND deleted = 0
        ";
        $rs = $this->getOne($sql);
        return $rs? false: true;
    }

    function isProductUpload($field, $vars) {
        $valid = false;
        $id = isset($this->data['id']) ? $this->data['id'] : 0;
        if($id) {
            if(isset($this->data['deleted_' . $field]) && $this->data['deleted_' . $field]) {
                $valid = isset($this->data[$field]) && $this->data[$field];
            } else {
                $valid = true;
            }
        } else {
            $valid = isset($this->data[$field]) && $this->data[$field];
        }
        return $valid;
    }

    function isDateEndGreatStart($field, $vars) {
    $start = $this->data['start'];
    $end = $this->data['end'];

    $start = strtotime($start);
    $end = strtotime($end);

    return $end >= $start;
    }
    
    function isIconUpload($field, $vars) {
        $valid = false;
        $id = isset($this->data['id']) ? $this->data['id'] : 0;
        if($id) {
            if(isset($this->data['deleted_' . $field]) && $this->data['deleted_' . $field]) {
                $valid = isset($this->data[$field]) && $this->data[$field];
            } else {
                $valid = true;
            }
        } else {
            $valid = isset($this->data[$field]) && $this->data[$field];
        }
        return $valid;
    }
    
    function isNotMore($field, $vars) {
        if (!isset($vars['compare']) || !$this->data[$vars['compare']]) {
            return false;
        }
        else {
            return ($this->data[$field] <= $this->data[$vars['compare']])? true: false;
        }
    }

    function isBeforeNow($field, $vars) {
        $start = $this->data[$field];
        return strtotime($start) > time() ? true : false;
    }
    
   function isPromotedCategoryNotOverflow($field, $vars) {
        $promoted = $this->data['promoted'];
        
        if($promoted) {
            $sql = "select COUNT(tb_category.id) AS total from tb_category left join tb_category_hierarchy on tb_category.id=tb_category_hierarchy.cid where tb_category_hierarchy.parent= 0 AND tb_category.deleted = 0 AND tb_category.promoted = 1 ";
            
            $rs = $this->getOne($sql);
            $total = $rs['total'];
            
            if ($total >= 4) {
                return false;
            }
            else {
                return true;
            }
        }
        else {
            return true;
        }
   }
   
   function isMemberPriceLessThanListPrice($field, $vars) {
        $memberPrice = $this->data[$field];
        $listPrice = $this->data['list_price'];
        
        $flag = $listPrice - $memberPrice;
   
        if ($flag < 0) {
            return false;
        }
        else {
            return true;
        }
    }
   
   function isMemberPriceLegal($field, $vars) {
        $paymentFee_max1 = max(PAYMENTFEE_BY_ALIPAY_CLIENT, PAYMENTFEE_BY_WAP);
        $paymentFee_max = max($paymentFee_max1, PAYMENTFEE_BY_ALIPAY_QRCODE);
        
        $memberPrice = $this->data[$field];
        
        $point = $this->data['point'];
        $sale_rebate = $this->data['sale_rebate'];
        $delivery_rebate = $this->data['delivery_rebate'];
        
        $flag = $memberPrice * (1 - $paymentFee_max) - $point - $sale_rebate - $delivery_rebate;
   
        if ($flag <= 0) {
            return false;
        }
        else {
            return true;
        }
    }

    function isSplitOrderProductTotalLessThanParentOrderProductTotal($field, $vars) {
        $productTotal = $this->data[$field];
        $productTotalParent = $this->data['product_total_parent'];
        
        $flag = $productTotal - $productTotalParent;
   
        if ($flag > 0) {
            return false;
        }
        else {
            return true;
        }
    }
    
    function isBckOverlapping($field, $vars) {
        $id = $this->data['id'] ? $this->data['id'] : 0;
        $start = $this->data['start'];
        $end = $this->data['end'];
        
        $newDate = $this->intervalDate($start, $end);
        
        $sql = "SELECT DATE_FORMAT(start, '%Y-%m-%d') AS start, DATE_FORMAT(end, '%Y-%m-%d') AS end FROM tb_quality_products_bck WHERE id <> {$id} AND deleted = 0";
        $result = $this->getAll($sql);
        
        $oldDate = array();
        foreach ($result as $v){
            $arr = $this->intervalDate($v['start'], $v['end']);
            foreach ($arr as $strdate) {
                $oldDate[] = $strdate;
            }
        }
        
        $intersectDate = array_intersect($newDate, $oldDate);
        return $intersectDate ? false : true;
    }

    function isBckEndGreatStart($field, $vars) {
        $start = $this->data['start'];
        $end = $this->data['end'];

        $start = strtotime($start);
        $end = strtotime($end);

        return $end >= $start;
    }
    
    function intervalDate($begDate,$endDate) {
        $date = array();
        $begTime = strtotime($begDate);
        $endTime = strtotime($endDate);
        while($begTime <= $endTime)
        {
            $date[]= date("Y-m-d",$begTime);
            $begTime+=86400;
        }
        return $date;
    }
    
    function isSpreadTitleExist() {
        $title = $this->data['title'];
        
        $sql = "SELECT id FROM tb_spread WHERE deleted = 0 AND title = '{$title}'";
        
        $rs = $this->getOne($sql);
        return $rs? false: true;
    }
    
    function isTacticsTitleExist() {
        $title = $this->data['title'];
        
        $sql = "SELECT id FROM tb_tactics WHERE deleted = 0 AND title = '{$title}'";
        
        $rs = $this->getOne($sql);
        return $rs? false: true;
    }
    
    function isProductTitleExist($field, $vars) {
        $title = $this->data['title'];
        $id = $this->data['id'];
        
        $sql = "SELECT * FROM tb_products WHERE deleted = 0 AND title = '{$title}' AND id <> '{$id}'";
        
        $rs = $this->getOne($sql);
        return $rs? false: true;
    }
    
    function isProductNumberExist($field, $vars) {
        $product_number = $this->data['product_number'];
        $id = $this->data['id'];
        
        //货号与删除的商品也不能相同，以便同步
        $sql = "SELECT * FROM tb_products WHERE product_number = '{$product_number}' AND id <> '{$id}' AND product_number !=''";
        
        $rs = $this->getOne($sql);
        return $rs? false: true;
    }
    
    function isWaiterPhoneExistOfSameType($field, $vars) {
        $id = (int)$this->data['id'];
        $type = (int)$this->data['type'];
        $this->escape($id);
        $this->escape($type);
        $phone = $this->data[$field];
        
        $sql = "SELECT * FROM tb_customer_service 
            WHERE phone = '{$phone}' AND id <> '{$id}' AND type = '{$type}' AND deleted = 0 AND activated = 1 ";
        
        $rs = $this->getOne($sql);
        return $rs? false: true;
    }
    
    function isWaiterMaxNumOfSameType($field, $vars) {
        $id = (int)$this->data['id'];
        $type = (int)$this->data['type'];
        $this->escape($id);
        $this->escape($type);
        
        $sql = "SELECT COUNT(id) AS count FROM tb_customer_service 
            WHERE type = '{$type}' AND id <> '{$id}' AND deleted = 0 AND activated = 1 ";
        
        $rs = $this->getOne($sql);
        return $rs['count'] >= CUSTOMER_SERVICE_LIMIT_NUM ? false: true;
    }
    
    function isCategoryEntityTitleExist($field, $vars){  //判断实体分类标题是否已经存在
        $title = $this->data['title'];
        $parent_id = $this->data['parent_id'];
        $sql = "select count(tb_category_entity.title) as count from tb_category_entity where deleted = 0 and tb_category_entity.title = '".$title."' and tb_category_entity.parent_id = '".$parent_id."'";
        $rs = $this->getOne($sql);
        return $rs['count'] >= 1 ? false: true;
    }
    
    function isCategoryServiceTitleExist($field, $vars){  //判断实体分类标题是否已经存在
        $title = $this->data['title'];
        $parent_id = $this->data['parent_id'];
        $sql = "select count(tb_category_service.title) as count from tb_category_service where deleted = 0 and tb_category_service.title = '".$title."' and tb_category_service.parent_id = '".$parent_id."'";
        $rs = $this->getOne($sql);
        return $rs['count'] >= 1 ? false: true;
    }
    
    function isStartEndTime($field, $vars){
        $start_time = $this->data[$field];
        if(strtotime($start_time) <= strtotime($vars)){
            return true;
        }else{
            return false;
        }
    }
    
    function error($field, $vars){
        return false;
    }

    function isDeviceNoExisted($field, $vars) {
        $no = $this->data['no'];
        $id = $this->data['id'];
        
        $sql = "SELECT * FROM tb_device WHERE deleted = 0 AND no = '{$no}' AND id <> '{$id}'";
        
        $rs = $this->getOne($sql);
        return $rs? false: true;
    }

    function isSchoolParentNotExisted($field, $vars){  
        $phone = $this->data['phone'];
        $schoolId = $this->data['school_id'];
        $id = $this->data['id'];

        $this->escape($phone);
        $this->escape($schoolId);
        $this->escape($id);

        $sql = "SELECT count(id) AS count FROM tb_school_parent WHERE deleted = 0 AND phone = '{$phone}' AND school_id = '{$schoolId}' AND id <> '{$id}'";
        $rs = $this->getOne($sql);

        return $rs['count'] >= 1 ? false: true;
    }
    
}

?>