<?php

/**
 * @brief Allows fetch requests from our Vue application
 */
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json");

/**
 * @brief Needed includes
 */
include_once("defines.php");
include_once("json.php");

/**
 * @brief Global variables that are used within the whole server indirectly
 */
$gSqlConnection = new mysqli(gHostName, gUserName, gPassword);
$gResponse = new JsonObject();
$gReceived = Base::DecodeInputAsAssoc();

/**
 * @brief Container class for all
 * the core functionalities
 */
class Base
{
    /**
     * @param queries
     * 
     * @brief Loops through queries and executes them one
     * by one
     */
    public static function ExecMultipleQueries(array $queries): array
    {
        $retval = array();

        foreach ($queries as $query) {
            $retval[] = Base::Exec($query);
        }

        return $retval;
    }

    /**
     * @param query
     * 
     * @brief Executes a specific query internally
     * and performs error checks
     */
    private static function ExecInternal(string $query)
    {
        global $gSqlConnection;

        try {
            $result = $gSqlConnection->query($query);
        } catch (Exception $e) {
            Base::Log(__FILE__, __LINE__, "Exception: " . $e . " : " . $gSqlConnection->error);
        }

        return $result;
    }

    /**
     * @param query
     * @param log
     * 
     * @brief Executes a specific query and logs into a file if necessary
     */
    public static function Exec(string $query, bool $log = false): mysqli_result | bool
    {
        global $gSqlConnection;
        global $gResponse;

        Base::Log(__FILE__, __LINE__, $query);

        $resultQuery = Base::ExecInternal("USE " . gDatabaseName . ";");
        $resultQuery = Base::ExecInternal($query);

        Base::Log(__FILE__, __LINE__, "Right before if");

        if (!$resultQuery) {
            if ($log)
                $gResponse->setData($query, false);

            Base::Log(__FILE__, __LINE__, var_export($gSqlConnection->errno, true));
            return false;
        }

        if ($log)
            $gResponse->setData($query, true);

        Base::Log(__FILE__, __LINE__, var_export($resultQuery, true));
        return $resultQuery;
    }

    /**
     * @param preparedQuery
     * @param args
     * 
     * @brief Binds parameters to a query and executes it
     * 
     * @details Executes a prepared query i.e. with bindable parameters,
     * difference with the standard using '?' is the order of
     * parameters is specified, so you actually can reorder them
     * inside the query without having to change the order of
     * the parameters within the function call
     */
    public static function ExecPreparedQuery(string $preparedQuery, ...$args): mysqli_result | bool
    {
        $counter = 1;

        foreach ($args as $currentArg) {
            $preparedQuery = str_replace("[" . strval($counter) . "]", strval($currentArg), $preparedQuery);
            $counter++;
        }

        Base::Log(__FILE__, __LINE__, $preparedQuery);
        return Base::Exec($preparedQuery);
    }

    /**
     * @brief Decodes data received from AJAX, probably
     * still working with Axios requests, to try
     */
    public static function DecodeInputAsAssoc(): array | null
    {
        $returnValue = json_decode(file_get_contents("php://input"), true);
        return $returnValue;
    }

    /**
     * @brief Checks the status of the SQL Connection
     */
    private static function CheckStatus(): void
    {
        global $gSqlConnection;
        global $gResponse;

        if (!$gSqlConnection) {
            $gResponse->setData("SQLConnection", false);
        } else {
            $gResponse->setData("SQLConnection", true);
        }
    }

    /**
     * @brief Creates the database if it doesn't exist already,
     * and use it.
     */
    private static function CreateDatabase(): void
    {
        Base::Exec("CREATE DATABASE IF NOT EXISTS " . gDatabaseName . ";");
        Base::Exec("USE " . gDatabaseName . ";");
    }

    /**
     * @brief Creates the default table(s) used within this website
     */
    private static function CreateTables(): void
    {
        Base::Exec("CREATE TABLE IF NOT EXISTS Visiteur (
					" . dbVisiteurPrimaryKey . " BIGINT UNIQUE PRIMARY KEY AUTO_INCREMENT NOT NULL,
					" . dbVisiteurName . " VARCHAR(100),
					" . dbVisiteurDaysCount . " INT,
					" . dbVisiteurDailyFee . " BIGINT
					);");
    }

    /**
     * @brief Initializes the whole database, prepares it for our
     * needed operations
     */
    public static function Init(): void
    {
        Base::CheckStatus();
        Base::CreateDatabase();
        Base::CreateTables();
    }

    /**
     * @param field
     * @param value
     * 
     * @brief Shortcut function to set some JSON data
     * in $gResponse
     */
    public static function SetResponseData(string $field, mixed $value)
    {
        global $gResponse;

        $gResponse->setData($field, $value);
    }

    /**
     * @param field
     * @param array
     * 
     * @brief Shortcut function to set some JSON array
     * in $gResponse
     */
    public static function SetResponseArray(string $field, array $array)
    {
        global $gResponse;

        Base::Log(__FILE__, __LINE__, var_export($gResponse, true));
        $gResponse->setArray($field, $array);
        Base::Log(__FILE__, __LINE__, var_export($gResponse, true));
    }

    /**
     * @brief 'Sends' the JSON response back to the client
     * 
     * @details Really justs echoes the data
     */
    public static function SendJSONResponse()
    {
        global $gResponse;

        echo ($gResponse->toJson());
    }

    /**
     * @brief Clears the JSON object
     * 
     * @details Wipes the response data, useful when used inside
     * other .php files that don't necessarily need the
     * previous data
     */
    public static function WipeResponseData()
    {
        global $gResponse;

        $gResponse->wipe();
    }

    /**
     * @param file
     * @param line
     * @param message
     * 
     * @brief Logs a message from file; at line into log.txt
     * 
     * @details Is useful for debugging purposes
     */
    public static function Log(string $file, int $line, string $message)
    {
        $towrite = "[LOG:{$file}:{$line}] = {$message}\n";

        $file = fopen("log.txt", "a");
        fwrite($file, $towrite);
        fclose($file);
    }
}

/**
 * @brief Check if the request performed currently was
 * sent with proper arguments
 */
if (isset($gReceived["init"]) && $gReceived["init"] == "true") {
    $gResponse->setData("Init", true);
    Base::Init();
    Base::Log(__FILE__, __LINE__, "L");
}
