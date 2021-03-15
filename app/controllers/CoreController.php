<?php

namespace App\controllers;

class CoreController {
    public function show ($viewName, $viewVars = array()) {
        // viewVars is an array which will be available in each view file
        $viewVars['currentPage'] = $viewName;
        // turns key and value into variable (key name) and value
        extract($viewVars);

        // $viewVars est disponible dans chaque fichier de vue
        require_once $_SERVER['DOCUMENT_ROOT'] . '/../app/views/header.tpl.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/../app/views/' . $viewName . '.tpl.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/../app/views/footer.tpl.php';
    }
}