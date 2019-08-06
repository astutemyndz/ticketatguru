<?php
if (!defined("ROOT_PATH")) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

class App {

    public static function setSession($key, $value) {
        if(isset($key)) $_SESSION[$key] = $value;
    }
    public static function getSession($key) {
        if(isset($key)) return $_SESSION[$key];
        
    }
    public static function getRoleId() {
        return self::getSession('role_id');
    }

    public static function isSuperAdmin() {
        if(self::getSession('is_superadmin')) { 
            return true;
        } 
        return false;
    }

    public static function getUserRoles() {
        $roles = array();
        if(self::getSession('roles')) { 
            foreach(self::getSession('roles') as $role) {
                $roles[] = $role['path'];
            }
        } 
        return $roles;
    }
    public static function getUserRoleControllers() {
        $controllers = array();
        if(self::getSession('roles')) { 
            foreach(self::getSession('roles') as $role) {
                $controllers[] = $role['controller'];
            }
        } 
        return $controllers;
    }
    public static function getQueryString() {
        return (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
    }

    public static function getController() {
        return $_GET['controller'];
    }
    public static function isView()
    {
        if (self::isSuperAdmin()) {
            return true;
        }

        $controllers = self::getUserRoleControllers();
        // echo "<pre>";
        // print_r($controllers);
        foreach ($controllers as $v) {
            if ($v == self::getController()) {
                return (bool) $v['is_visible'];
            }
        }
    }


}