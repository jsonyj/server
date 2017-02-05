<?php

class BraveController extends Brave {

    var $actionPostfix = 'Action';
    var $data = null;
    var $view = null;

    function hasAction($name) {
        $name.= $this->actionPostfix;
        return method_exists($this, $name);
    }

    function setData($data, $key = null) {
        if (is_null($key))
            $this->data = $data;
        else
            $this->setValue($this->data, $key, $data);

        global $page;
        
        if (!isset($this->data['page'])) {
            $this->data['page'] = $page;    
        }
        
        if ($this->view) {
            $this->view->setData($this->data);
        }
    }

    function execAction($name) {
        $actionName = $name . $this->actionPostfix;
        $this->$actionName();
    }

    function forward($forward, $data = null) {
        $dispatcher = $this->getGlobal('BraveDispatcher');
        $dispatcher->dispatch($forward, $data);
    }

    function isConfirm() {
        return $this->isPost('confirm');
    }
    
    function isComplete() {
        return $this->isPost('complete');
    }
	
	function isGetComplete() {
        return $this->isGet('complete');
    }
    
    function execJs($js = '') {
    	if (!$js) {
    		return;
    	}
    	
    	$js = '<script type="text/javascript">' . $js . '</script>';
    	echo $js;
    }

    function aes128Encrypt($data, $key, $iv=null) {
        if(16 !== strlen($key)) $key = hash('MD5', $key, true);
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
        return base64_encode($ciphertext);
    }

    function aes128Decrypt($data, $key, $iv=null) {
        $data = base64_decode($data);
        if(16 !== strlen($key)) $key = hash('MD5', $key, true);
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv));
    }
}

?>
