<?php 
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json");

include_once("base.php");

class RmVisitor
{
    /**
     * Checks if the provided data is even a valid
     * a VisitorNumber at all
     */
    private static function IsValidVisitorNum(array | null $data): bool
    {
        if (is_null($data))
            return false;
        if (!isset($data[dbVisiteurPrimaryKey]))
            return false;
        if (intval($data[dbVisiteurPrimaryKey]) == 0)
            return false;
        
        return true;
    }

    /**
     * Tries to remove a visitor
     */
    public static function RemoveVisitor(): void
    {
        $receivedData = Base::DecodeInputAsAssoc();

        if (!RmVisitor::IsValidVisitorNum($receivedData))
            return;

        $preparedQuery = "DELETE FROM Visiteur WHERE ".dbVisiteurPrimaryKey." = [1];";
        
        Base::ExecPreparedQuery($preparedQuery, $receivedData[dbVisiteurPrimaryKey]);
    }
}

RmVisitor::RemoveVisitor();
?>