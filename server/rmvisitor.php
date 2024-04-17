<?php

/**
 * @brief Allows fetch requests from our Vue app
 */
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json");

/**
 * @brief Needed includes
 */
include_once("base.php");

/**
 * @brief Container class for
 * all the removing funtions
 */
class RmVisitor
{
    /**
     * @param data
     * 
     * @brief Checks if the provided data is even a valid
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
     * @brief Tries to remove a visitor
     */
    public static function RemoveVisitor(): void
    {
        $receivedData = Base::DecodeInputAsAssoc();
        Base::Log(__FILE__, __LINE__, var_export($receivedData, true));

        if (!RmVisitor::IsValidVisitorNum($receivedData))
            return;

        $preparedQuery = "DELETE FROM Visiteur WHERE " . dbVisiteurPrimaryKey . " = [1];";

        Base::Log(__FILE__, __LINE__, var_export($preparedQuery, true));
        Base::Log(__FILE__, __LINE__, var_export(Base::ExecPreparedQuery($preparedQuery, $receivedData[dbVisiteurPrimaryKey]), true));
    }
}

/**
 * @brief File's main callbacks
 * 
 * @details Tries to remove a visitor,
 * wipes the JSON output, sets 'Success'
 * to true and sends the JSON response
 */
RmVisitor::RemoveVisitor();
Base::WipeResponseData();
Base::SetResponseData("Success", true);
Base::SendJSONResponse();
