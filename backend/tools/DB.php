<?php
require_once __DIR__ . "/../env.php";


/**
 * Contiene la lógica para conectar e interactuar con una base de datos
 */
class DB {
  /* Constantes */
    public const DB_CONNECTION_ERROR = "No se ha podido conectar con la base de datos. Inténtelo de nuevo";
    public const DB_CREATE_ERROR = "Se ha producido un error al crear el recurso. Inténtelo de nuevo";
    public const DB_DELETE_ERROR = "Se ha producido un error al eliminar el recurso. Inténtelo de nuevo";
    public const DB_GET_ERROR = "Se ha producido un error al recuperar el recurso de la base de datos. Inténtelo de nuevo";
    public const DB_TRANSACTION_ERROR = "Se ha producido un error durante la transacción con la base de datos. Los cambios han sido deshechos. Inténtelo de nuevo";
    public const DB_UPDATE_ERROR = "Se ha producido un error al actualizar el recurso. Inténtelo de nuevo";


  /* Atributos */
    /** @var ?PDO Conexión con la base de datos */
    private ?PDO $connection;
    /**
     *  @var array Array que almacena queries y parámetros para realizar una transacción
     *
     * Cada elemento del array debe tener la estructura `["query" => string, "params" => array]`
    */
    private array $queries;


  /* Constructor */
    /**
     * Instancia una conexión con la base de datos
     */
    public function __construct() {
      $this->queries = [];
      $this->connect();
    }


  /* Métodos */
    /**
     * Realiza una conexión a una DB. Los datos de conexión deben encontrarse en `env.php`
     * - `DB_HOST`
     * - `DB_NAME`
     * - `DB_USER`
     * - `DB_PASSWORD`
     */
    private function connect(): void {
      $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
      $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ];

      try {
        $this->connection = new PDO(
          $dsn,
          DB_USER,
          DB_PASSWORD,
          $options
        );
      } catch (PDOException $e) {
        $this->connection = null;
      }
    }


    /**
     * Comprueba si se ha realizado exitosamente la conexión con la base de datos
     *
     * @return bool `true` si se ha establecido una conexión, `false` si no
     */
    public function isConnected(): bool {
      return
        $this->connection instanceof PDO &&
        $this->connection->getAttribute(PDO::ATTR_CONNECTION_STATUS) !== null;
    }


    /**
     * Retorna un array con las queries almacenadas para realizar una transacción
     *
     * Cada elemento del array retornado tiene la estructura:
     * `["query" => string, "params" => array]`
     *
     * @return array
     */
    public function getQueries(): array { return $this->queries; }


    /**
     * Añade una query (y sus parámetros) a la lista de queries para realizar una transacción
     *
     * @param string $query Query a añadir. Utilizar `:varName` o `?` si se desean parámetros.
     * - Ejemplo: `SELECT * FROM table WHERE column = :varName AND column2 = :varName2`
     * - Ejemplo 2: `SELECT * FROM table WHERE column = ? AND column2 = ?`
     *
     * @param array $params Array escalar o asociativo con los parámetros de la query. Default `[]`
     * - Ejemplo: `["varName" => value, "varName2" => value2, ...]`
     * - Ejemplo 2: `[value1, value2, ...]`
     */
    public function addQuery(string $query, array $params = []): void { $this->queries[] = ["query" => $query, "params" => $params]; }


    /**
     * Ejecuta una transacción con las queries almacenadas
     *
     * @return bool `true` si se ha realizado la transacción exitosamente, `false` si no hay queries almacenadas o se produce algún error
     * durante la transacción. Los cambios se deshacen
     */
    public function executeTransaction(): bool {
      if (empty($this->queries)) return false;

      try {
        $this->connection->beginTransaction(); // Comenzar transacción

        // Ejecutar queries almacenadas
        foreach ($this->queries as $query) {
          $stmt = $this->connection->prepare($query["query"]);
          $stmt->execute($query["params"]);
        }

        return $this->connection->commit(); // Terminar transacción
      } catch (PDOException $e) {
        $this->connection->rollBack(); // Deshacer transacción
        return false;
      } finally {
        $this->queries = []; // Vaciar queries en éxito y en error
      }
    }


    /**
     * Realiza una consulta (`select`) y retorna un array con arrays asociativos con los resultados de la consulta
     *
     * @param string $query Query a ejecutar. Utilizar `:varName` o `?` si se desean parámetros.
     * - Ejemplo: `SELECT * FROM table WHERE column = :varName AND column2 = :varName2`
     * - Ejemplo 2: `SELECT * FROM table WHERE column = ? AND column2 = ?`
     *
     * @param array $params Array escalar o asociativo con los parámetros de la query. Default `[]`
     * - Ejemplo: `["varName" => value, "varName2" => value2, ...]`
     * - Ejemplo 2: `[value1, value2, ...]`
     *
     * @return ?array Un array con los resultados de la consulta con la estructura `["column" => value, "column2" => value2, ...]`.
     * Retorna `null` si se produce un error durante la consulta
     */
    public function select($query, $params = []): ?array {
      try {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);

        $result = $stmt->fetchAll();

        return $result !== false ? $result : null;
      } catch (PDOException $e) {
        return null;
      }
    }
}
