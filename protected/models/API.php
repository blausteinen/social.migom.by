<?php

abstract class API extends CModel {

    protected $_rest;
    protected $_config;
    private $methods = array('get' => 3, 'post' => 4, 'put' => 3, 'delete' => 6);

    /**
     * @example protected function getApiTitle(){ return 'migom'; }
     * @return string Default api config name
     */
    protected function getApiTitle() {
        return 'migom';
    }

    public function __construct() {
        if (!YII::app()->hasComponent('RESTClient')) {
            throw new CHttpException(404, Yii::t('App', 'Have not the extention "RESTClient"'), 404);
        }
        $this->_rest = YII::app()->RESTClient;
    }

    public function __call($function, $args) {
        $error = true;
        $params = array();
        $id = null;
        foreach ($this->methods as $method => $len) {
            if (strpos($function, $method) === 0) {
                $function = substr($function, $len);
                $error = false;
                break;
            }
        }
        if ($error) {
            throw new CHttpException(404, Yii::t('App', 'REST haven`t got method {method}', array('{method}' => $function)), 404);
        }
        foreach ($args as $arg) {
            if (is_array($arg)) {
                $params = $arg;
            } else {
                $id = $arg;
            }
        }
        $controller = substr(get_class($this), 0, -3);
        return $this->query($controller, $function, $id, $method, $params);
    }

    public function query($controller, $function = '', $id = null, $method = 'get', $params = array()) {
        $this->_rest->initialize($this->getApiTitle());
        $uri = $this->_createUri($controller, $function, $id);
        Yii::trace(get_class($this) . '.query()', 'RESTClient');
        $responce = $this->_rest->{$method}($uri, $params, 'json');

//        $this->_rest->debug();
        return $responce;
    }

    protected function _createUri($controller, $function = '', $id = null) {
        $uri = $controller;
        if ($function) {
            $uri .= '/' . $function;
        }
        if ($id) {
            $uri .= '/' . $id;
        }
        return $uri;
    }

}