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
     * @brief Returns whether we're in edit mode or not
     */
    private static function IsAddingMode(array | null $rec): bool
    {
        if (!isset($rec[reqIsAddMode])) {
            //! None of the needed data is set, so we assume it's edit mode
            if (!isset($rec[dbVisiteurPrimaryKey])) {
                return true;
            }

            $currId = intval($rec[dbVisiteurPrimaryKey]);

            //! We don't know the mode for sure, so we must assume
            //! depending on the id's value
            return ($currId == 0);
        }

        //! We know the mode, so we can discard any id parameter
        $isAddMode = boolval($rec[reqIsAddMode]);

        if ($isAddMode)
            return true;

        //! The flag is set to false, however we cannot add a visitor
        //! that has the number '0'
        $currId = intval($rec[dbVisiteurPrimaryKey]);

        return ($currId == 0);
    }

    /**
     * @param data
     * 
     * @brief Adds a new visitor by fetching data
     */
    private static function AddVisitor(array $data): bool
    {
        $recId         = intval($data[dbVisiteurPrimaryKey]);
        $name          = strval($data[dbVisiteurName]);
        $daysCount     = strval($data[dbVisiteurDaysCount]);
        $dailyFee      = strval($data[dbVisiteurDailyFee]);

        if ($recId == 0) {
            $preparedQuery = "INSERT INTO Visiteur ("
                . dbVisiteurName . ", " . dbVisiteurDaysCount . ", "
                . dbVisiteurDailyFee . ") VALUES('[1]', [2], [3]);";

            $result = Base::ExecPreparedQuery($preparedQuery, $name, $daysCount, $dailyFee);

            return ($result != false);
        }

        $preparedQuery = "INSERT INTO Visiteur ("
            . dbVisiteurPrimaryKey . ", " . dbVisiteurName . ", "
            . dbVisiteurDaysCount . ", " . dbVisiteurDailyFee . ") VALUES([1], '[2]', [3], [4]);";

        $result = Base::ExecPreparedQuery($preparedQuery, $recId, $name, $daysCount, $dailyFee);

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
            . dbVisiteurPrimaryKey . " = [2],"
            . dbVisiteurName     . " = '[3]',"
            . dbVisiteurDaysCount . " = [4],"
            . dbVisiteurDailyFee  . " = [5] WHERE "
            . dbVisiteurPrimaryKey . " = [1];";
        $previousId   = intval($data[reqPreviousId]);
        $newId        = intval($data[dbVisiteurPrimaryKey]);
        $newName      = strval($data[dbVisiteurName]);
        $newDaysCount = intval($data[dbVisiteurDaysCount]);
        $newDailyFee  = intval($data[dbVisiteurDailyFee]);
        Base::Log(__FILE__, __LINE__, var_export($preparedQuery, true));
        Base::Log(__FILE__, __LINE__, var_export($previousId, true));
        Base::Log(__FILE__, __LINE__, var_export($newId, true));

        if ($previousId == 0)
            return false;

        $result = Base::ExecPreparedQuery($preparedQuery, $previousId, $newId, $newName, $newDaysCount, $newDailyFee);

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

        //! Checking for the 'add mode' flag, as well as the id's validity
        if (DatabaseActions::IsAddingMode($receivedData))
            DatabaseActions::AddVisitor($receivedData);
        else
            DatabaseActions::UpdateVisitor($receivedData);
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
