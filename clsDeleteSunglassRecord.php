<?php
require_once("asstinclude.php");
class clsDeleteSunglassRecord 
{
    function deleteTheRecord(&$mysqlObj,$TableName, $brandName)
    {
        $mysqlObj = createConnectionObject(); 
        $query = "Delete from $TableName Where brandName = ?";
        if (($stmtObj = $mysqlObj->prepare($query)))
        $BindSuccess = $stmtObj->bind_param("s", $brandName); 
        $deleteResult = $stmtObj->execute();
        $numberRecordsDeleted = $stmtObj->affected_rows;
        $stmtObj->close();	
        return $numberRecordsDeleted;
    }
}
?>