<?php
/**
 * Created by JetBrains PhpStorm.
 * User: kris
 * Date: 15/11/11
 * Time: 22:32
 * To change this template use File | Settings | File Templates.
 */

namespace Kunstmaan\KAdminListBundle\AdminList;

abstract class AbstractAdminListConfigurator {

    abstract function configureListFields($array);

    function adaptQueryBuilder($querybuilder){

    }

    function getValue($item, $columnName){
        $var = "get".$columnName;
        $result = $item->$var();
        if($result instanceof \DateTime){
            return $result->format('Y-m-d H:i:s');
        } else {
            return $result;
        }
    }

    function getLimit(){
        return 10;
    }
}
