<?php

class IndexController extends AppController {

    function IndexController() {
        $this->AppController();
    }

    function indexAction() {

        $class_name_list = array('StudentController', 'BindDeviceController', 'CardDeviceController', 'FrontDeviceController', 'ServiceController');

        $ref_classes = array();
        foreach($class_name_list as $class_name) {
            require_once(dirname(__FILE__) . DS . $class_name . '.php');
            $ref_class = new ReflectionClass($class_name);
            $ref_classes[] = $this->extraClass($ref_class);
        }

        $port = $_SERVER['SERVER_PORT'] == '80' ? '' : ':' . $_SERVER['SERVER_PORT'];

        $this->view->assign('type', $type);
        $this->view->assign('classes', $ref_classes);
        $this->view->assign('port', $port);
        $this->view->layout();
    }

    function extraClass($class) {

        $ref_class['name'] = $class->name;

        preg_match_all("/\*[[:space:]]*(@description[^(\*\/$)]*)/i", $class->getDocComment(), $matches, PREG_SET_ORDER);
        if(isset($matches[0][1])) {
            $ref_class['description'] = str_replace('@description ', '', $matches[0][1]);
        }

        $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach($methods as $method) {

            if(strpos($method->name, 'Action') !== false && $method->class == $class->name) {

                $ref_method = array();
                $ref_method['name'] = $method->name;

                //方法描述
                preg_match_all("/\*[[:space:]]*(@description[^(\*\/$)]*)/i", $method->getDocComment(), $matches, PREG_SET_ORDER);
                if(isset($matches[0][1])) {
                    $ref_method['description'] = str_replace('@description ', '', $matches[0][1]);
                }

                //方法参数
                preg_match_all("/\*[[:space:]]*(@param[^(\*\/$)]*)/i", $method->getDocComment(), $matches, PREG_SET_ORDER);

                $ref_method['name'] = $method->name;

                $ref_param = array();
                foreach($matches as $matche) {

                    $params = explode(' ', $matche[1]);

                    $param_comment = array();
                    foreach($params as $param) {
                        if(isset($param)) {
                            $kv = explode('=', $param);
                            if(isset($kv[0]) && isset($kv[1])) {
                                $param_comment[$kv[0]] = $kv[1];
                            }
                        }
                    }

                    $ref_param[] = $param_comment;
                }

                $ref_method['param'] = $ref_param;

                //返回值
                $regex = '/@[\s]*return(.*?)\*\//s';
                $regexClean = '/\**/';//'/\*[\s\t]*/';
                preg_match($regex, $method->getDocComment(), $matches);

                $return['array'] = preg_replace($regexClean, '', $matches[1]);

                $json = '';
                if($return['array']) {
                    eval("\$json = json_encode(" . $return['array'] . ");");
                }
                $return['json'] = $json;

                $ref_method['return'] = $return;

                $ref_class['method'][] = $ref_method;
            }
        }

        return $ref_class;
    }
}

?>
