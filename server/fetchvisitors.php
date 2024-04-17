<?php

/**
 * @brief Allows fetch requests from our Vue application
 */
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json");

/**
 * @brief Needed includes
 */
include_once("base.php");

/**
 * @brief Container class for all
 * the fetching functions
 */
class FetchVisitors
{
    /**
     * @param result
     * 
     * @brief Cumulatively combines all the data into one big
     * array that will later be inserted into the response
     * 
     * @details Was supposed to create an object instead,
     * but that was scrapped. Now it just ultimately performs
     * checks on the primary key; that's it
     */
    private static function CumulateData(mysqli_result | bool $result): array | null
    {
        if (!$result)
            return null;

        $retVal = array();

        Base::Log(__FILE__, __LINE__, __FUNCTION__);

        while ($row = $result->fetch_assoc()) {
            if (
                is_null($row) || !isset($row[dbVisiteurPrimaryKey]) ||
                !isset($row[dbVisiteurName]) || !isset($row[dbVisiteurDaysCount]) ||
                !isset($row[dbVisiteurDailyFee])
            )
                continue;

            $retVal[] = $row;
        }

        return $retVal;
    }

    /**
     * @brief Fetches all the data about visitors from
     * the database
     */
    public static function FetchAll(): void
    {
        Base::Log(__FILE__, __LINE__, "WHAT THE FUCK?!");
        $query = "SELECT * FROM Visiteur ORDER BY LENGTH(" . dbVisiteurPrimaryKey . ")
                  ASC, " . dbVisiteurPrimaryKey . " ASC;";
        $result = Base::Exec($query);
        $data = FetchVisitors::CumulateData($result);

        if (is_null($data)) {
            Base::Log(__FILE__, __LINE__, "\$data is null");
            Base::SetResponseData("Error", true);
            return;
        }

        Base::Log(__FILE__, __LINE__, __FUNCTION__);
        Base::SetResponseArray("FetchedData", $data);
        Base::SetResponseData("Error", false);
    }
}

/**
 * @brief File's main callbacks
 * 
 * @details Clears the JSON Object,
 * fetch all the database's data (FetchAll will
 * handle storing the data inside the JSON object)
 * and finally, sends the JSON response
 */
Base::WipeResponseData();
FetchVisitors::FetchAll();
Base::Log(__FILE__, __LINE__, __FUNCTION__);
Base::SendJSONResponse();
