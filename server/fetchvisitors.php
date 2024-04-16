<?php 
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json");

include_once("base.php");

class FetchVisitors
{
    /**
     * Cumulatively combines all the data into one big
     * array that will later be inserted into the response
     */
    private static function CumulateData(mysqli_result | bool $result): array | null
    {
        if (!$result)
            return null;

        $retVal = array();

        Base::Log(__FILE__, __LINE__, __FUNCTION__);

        while ($row = $result->fetch_assoc()) {
            if (is_null($row) || !isset($row[dbVisiteurPrimaryKey]) ||
                !isset($row[dbVisiteurName]) || !isset($row[dbVisiteurDaysCount]) ||
                !isset($row[dbVisiteurDailyFee]))
                continue;
            $id = strval($row[dbVisiteurPrimaryKey]);

            $retVal[] = $row;
        }

        return $retVal;
    }
    
    /**
     * Fetches all the data about visitors from
     * the database
     */
    public static function FetchAll(): void
    {
        Base::Log(__FILE__, __LINE__, "WHAT THE FUCK?!");
        $query = "SELECT * FROM Visiteur ORDER BY LENGTH(".dbVisiteurPrimaryKey.")
                  ASC, ".dbVisiteurPrimaryKey." ASC;";
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

Base::WipeResponseData();
FetchVisitors::FetchAll();
Base::Log(__FILE__, __LINE__, __FUNCTION__);
Base::SendJSONResponse();
?>