<?php

class proveedoresClass
{
    public function getCategories(){
        include  'connection/connection.php';
        $query = "SELECT CAT_UID,
                         CAT_NOMBRE,
                         CAT_ESTADO
                  FROM categorias
                  WHERE CAT_ESTADO = 'ACTIVO'";
        $result = $con->query($query);
        $array = array();
        $array = mysqli_fetch_all($result,MYSQLI_ASSOC);
        mysqli_close($con);
        return $array;
    }

    public function getCategoriasPorProveedor($proUid){
        include  'connection/connection.php';
        $query = "SELECT C.CAT_UID AS CAT_UID,
                         $proUid AS PRO_UID,
                         PC.PC_UID AS PC_UID,
                         C.CAT_NOMBRE AS CAT_NOMBRE,
                         PC.PC_ESTADO AS PC_ESTADO
                  FROM categorias C
                  LEFT JOIN proveedores_categorias PC 
                  ON PC.CAT_UID = C.CAT_UID AND PC.PRO_UID = $proUid
                  WHERE C.CAT_ESTADO = 'ACTIVO'                  
                  ORDER BY C.CAT_NOMBRE";
        $result = $con->query($query);
        $array = array();
        $array = mysqli_fetch_all($result,MYSQLI_ASSOC);
        mysqli_close($con);
        return $array;
    }

    public function addCategoriasPorProveedor($pcUid,$proUid,$catUid){
        include  'connection/connection.php';
        $query = "INSERT INTO proveedores_categorias
                  (PC_UID, PRO_UID, CAT_UID, PC_ESTADO)
                  VALUES(NULL, " . $proUid . ", " . $catUid . ", 'ACTIVO');";
        $result = $con->query($query);
        mysqli_close($con);
        return 'OK';
    }

    public function removeCategoriasPorProveedor($pcUid,$proUid,$catUid){
        include  'connection/connection.php';
        $query = "DELETE FROM  proveedores_categorias
                  WHERE PC_UID=$pcUid;";
        $result = $con->query($query);
        mysqli_close($con);
        return 'OK';
    }

    public function getProveedores($proUid = ''){
        include  'connection/connection.php';
        $where = ' ';
        if ($proUid == '') {
            $where = " ";
        } else {
            $where = "AND PRO_UID = '$proUid' ";
        }
        $query = "SELECT PRO_UID,
                         PRO_NOMBRE,
                         PRO_NOMBRE_CARPETA,
                         PRO_DESCRIPCION,
                         PRO_IMAGEN_LOGO,
                         PRO_IMAGEN_PORTADA,
                         PRO_WHATSAPP,
                         PRO_MESSENGER,
                         PRO_EMAIL,
                         PRO_HORARIOS,
                         PRO_GEOLOCALIZACION,
                         PRO_RANKING,
                         PRO_TELEFONOS,
                         PRO_ESTADO
                  FROM proveedores
                  WHERE PRO_ESTADO = 'ACTIVO'" . $where;
        $result = $con->query($query);
        $array = array();
        $array = mysqli_fetch_all($result,MYSQLI_ASSOC);
        mysqli_close($con);
        return $array;
    }

    public function removeProveedor($proUid,$proNombre)
    {   
        include  'connection/connection.php';
        $query = "DELETE FROM  proveedores_promociones
                  WHERE PRO_UID=$proUid;";                          
        $result = $con->query($query);
        
        $query = "DELETE FROM  proveedores_galeria
                  WHERE PRO_UID=$proUid;";
        $result = $con->query($query);

        $query = "DELETE FROM  proveedores_categorias
                  WHERE PRO_UID=$proUid;";                          
        $result = $con->query($query);

        $query = "DELETE FROM  proveedores
                  WHERE PRO_UID=$proUid;";                          
        $result = $con->query($query);

        //error_log(getcwd());

        $actualFoleder = getcwd();
        $dir = str_replace('system\\classes', $proNombre, $actualFoleder);

        //unlink($dir);
        exec('rm -rf '.escapeshellarg($dir));


        //rmdir($actualFoleder);    
        return 'OK';
    }

}

?>