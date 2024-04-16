<?php 
header("Access-Control-Allow-Origin: http://localhost:8080");
include_once("base.php");

class DatabaseActions
{
    /**
     * Returns whether the received visitor number is valid
     * or not
     */
    private static function IsValidVisitorNumber(array | null $rec): bool {
        if (is_null($rec))
            return false;
        if (!isset($rec[dbVisiteurPrimaryKey]))
            return false;
        if (intval($rec[dbVisiteurPrimaryKey]) == 0)
            return false;
        return true;
    }

    /**
     * Adds a new visitor
     */
    private static function AddVisitor(array $data): bool {
        $preparedQuery = "INSERT INTO Visiteur ("
                          .dbVisiteurName.", ".dbVisiteurDaysCount.", "
                          .dbVisiteurDailyFee.") VALUES('[1]', [2], [3]);";
        $name          = $data[dbVisiteurName];
        $daysCount     = $data[dbVisiteurDaysCount];
        $dailyFee      = $data[dbVisiteurDailyFee];

        $result = Base::ExecPreparedQuery($preparedQuery, $name, $daysCount, $dailyFee);

        return ($result != false);
    }

    /**
     * Updates an existing visitor
     */
    private static function UpdateVisitor(array $data): bool {
        $preparedQuery = "UPDATE Visiteur SET "
                          .dbVisiteurName     ." = '[2]',"
                          .dbVisiteurDaysCount ." = [3],"
                          .dbVisiteurDailyFee  ." = [4] WHERE "
                          .dbVisiteurPrimaryKey." = [1];";
        $id = $data[dbVisiteurPrimaryKey];
        $newName = $data[dbVisiteurName];
        $newDaysCount = $data[dbVisiteurDaysCount];
        $newDailyFee = $data[dbVisiteurDailyFee];

        $result = Base::ExecPreparedQuery($preparedQuery, $id, $newName, $newDaysCount, $newDailyFee);

        return ($result != false);
    }
    
    /**
     * Determines whether to add a new visitor or to update
     * an existing one
     */
    public static function ExecVisitorAction(): void {
        $receivedData = Base::DecodeInputAsAssoc();

        if (is_null($receivedData)) {
            return;
        }
        
        // If it's invalid or = 0 then it is a new visitor
        if (DatabaseActions::IsValidVisitorNumber($receivedData))
            DatabaseActions::UpdateVisitor($receivedData);
        else
            DatabaseActions::AddVisitor($receivedData);
    }
}

DatabaseActions::ExecVisitorAction();
Base::WipeResponseData();
Base::SetResponseData("Success", true);
Base::SendJSONResponse();
?>