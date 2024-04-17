<?php

/**
 * @brief Allows fetch requests on this file from our Vue application
 */
header("Access-Control-Allow-Origin: http://localhost:8080");

/**
 * @brief Needed includes
 */
include_once("base.php");

class DatabaseActions
{
    /**
     * @param rec
     * 
     * @brief Returns whether the specified received array
     * has a valid visitor primary key (NumVisiteur)
     */
    private static function IsValidVisitorNumber(array | null $rec): bool
    {
        if (is_null($rec))
            return false;
        if (!isset($rec[dbVisiteurPrimaryKey]))
            return false;
        if (intval($rec[dbVisiteurPrimaryKey]) == 0)
            return false;
        return true;
    }

    /**
     * @param data
     * 
     * @brief Adds a new visitor by fetching data
     */
    private static function AddVisitor(array $data): bool
    {
        $preparedQuery = "INSERT INTO Visiteur ("
            . dbVisiteurName . ", " . dbVisiteurDaysCount . ", "
            . dbVisiteurDailyFee . ") VALUES('[1]', [2], [3]);";
        $name          = $data[dbVisiteurName];
        $daysCount     = $data[dbVisiteurDaysCount];
        $dailyFee      = $data[dbVisiteurDailyFee];

        $result = Base::ExecPreparedQuery($preparedQuery, $name, $daysCount, $dailyFee);

        return ($result != false);
    }

    /**
     * @param data
     * 
     * @brief Update a visitor based on the received data
     */
    private static function UpdateVisitor(array $data): bool
    {
        $preparedQuery = "UPDATE Visiteur SET "
            . dbVisiteurName     . " = '[2]',"
            . dbVisiteurDaysCount . " = [3],"
            . dbVisiteurDailyFee  . " = [4] WHERE "
            . dbVisiteurPrimaryKey . " = [1];";
        $id = $data[dbVisiteurPrimaryKey];
        $newName = $data[dbVisiteurName];
        $newDaysCount = $data[dbVisiteurDaysCount];
        $newDailyFee = $data[dbVisiteurDailyFee];

        $result = Base::ExecPreparedQuery($preparedQuery, $id, $newName, $newDaysCount, $newDailyFee);

        return ($result != false);
    }

    /**
     * @brief Performs a check on the visitor ID to
     * know whether to add a new one or update an
     * existing one's data
     */
    public static function ExecVisitorAction(): void
    {
        $receivedData = Base::DecodeInputAsAssoc();

        if (is_null($receivedData)) {
            return;
        }

        //! If it's invalid or = 0 then it is a new visitor
        if (DatabaseActions::IsValidVisitorNumber($receivedData))
            DatabaseActions::UpdateVisitor($receivedData);
        else
            DatabaseActions::AddVisitor($receivedData);
    }
}

/**
 * @brief File's main callbacks
 * 
 * @details Executes the matching action to
 * the received data, wipes the JSON output,
 * sets 'Success' to true and sends the new
 * JSON output
 */
DatabaseActions::ExecVisitorAction();
Base::WipeResponseData();
Base::SetResponseData("Success", true);
Base::SendJSONResponse();
