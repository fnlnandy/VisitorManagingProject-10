<?php
include_once("defines.php");

$gSqlConnection = new mysqli(gHostName, gUserName, gPassword);

class Base {
	/**
	 * Executes an array of queries by iteratively calling
	 * Base::Exec()
	 */
	public static function ExecMultipleQueries(array $queries): array {
		$retval = array();

		foreach ($queries as $query) {
			$retval[] = Base::Exec($query);
		}

		return $retval;
	}

	/**
	 * Executes a query, shortcut to referencing $gSqlQuery and using
	 * the my_sqli::query() function
	 */
	public static function Exec(string $query): mysqli_result | bool {
		global $gSqlConnection;

		$resultSwitch = $gSqlConnection->query("USE DATABASE ".gDatabaseName.";");
		$resultQuery  = $gSqlConnection->query($query);

		if (!$resultSwitch || $resultQuery) {
			die("There was an error executing the query: '" . $query . "'.");
		}

		return $resultSwitch && $resultQuery;
	}

	/**
	 * Executes a prepared query i.e. with bindable parameters,
	 * difference with the standard using '?' is the order of
	 * parameters is specified, so you actually can reorder them
	 * inside the query without having to change the order of
	 * the parameters within the function call
	 */
	public static function ExecPreparedQuery(string $preparedQuery, ... $args): mysqli_result | bool {
		$counter = 1;

		foreach ($args as $currentArg) {
			$preparedQuery = str_replace("[".strval($counter)."]", $currentArg, $preparedQuery);
			$counter++;
		}

		return Base::Exec($preparedQuery);
	}

	/**
	 * Decodes data received from AJAX, probably
	 * still working with Axios requests, to try
	 */
	public static function DecodeInputAsAssoc(): array | null
	{
		$returnValue = json_decode(file_get_contents("php://input"), true);
        return $returnValue;
	}

	/**
	 * Checks the status of the SQL Connection
	 */
	private static function CheckStatus(): void {
		global $gSqlConnection;

		if (!$gSqlConnection) {
			die("There was an error connecting to the MySQL database.");
		}
	}

	/**
	 * Creates the database if it doesn't exist already,
	 * and use it.
	 */
	private static function CreateDatabase(): void {
		Base::Exec("CREATE DATABASE IF NOT EXISTS ".gDatabaseName.";");
		Base::Exec("USE DATABASE ".gDatabaseName.";");
	}

	/**
	 * Creates the default table(s) used within this website
	 */
	private static function CreateTables(): void {
		Base::Exec("CREATE TABLE Visiteur (
					".dbVisiteurPrimaryKey." BIGINT UNIQUE PRIMARY KEY AUTO INCREMENT NOT NULL,
					".dbVisiteurName." VARCHAR(100),
					".dbVisiteurDaysCount." INT,
					".dbVisiteurDailyFee." BIGINT
					);");
	}

	/**
	 * Initializes the whole database, prepares it for our
	 * needed operations
	 */
	public static function Init(): void {
		Base::CheckStatus();
		Base::CreateDatabase();
		Base::CreateTables();
	}
}
?>