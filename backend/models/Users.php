<?php
require_once __DIR__ . "/../env.php";
require_once __DIR__ . "/../" . DB_TOOLS_PATH;
require_once __DIR__ . "/../" . ENCRYPT_TOOLS_PATH;


/**
 * Modelo de `User` para interactuar con la base de datos
 */
class UsersModel {
  /* Constantes */
    public const TABLE = "users";
    public const COL_USERNAME = "username";
    public const COL_NAME = "name";


  /* Métodos */
    /**
     * Retorna un `User` mediante su `username`
     *
     * @param string $username `username` a buscar. *Case insensitive*
     *
     * @return ?array Si se encuntra un `User`, se retorna un array con la estructura `["username" => string, "name" => string]`. Retorna `null` si no se enecuntra un `User`
     *
     * @throws \Exception Si se produce algún error
     */
    public static function getUserByUsername(string $username): ?array {
      $query = "SELECT " . self::COL_USERNAME . ", " . self::COL_NAME .
        " FROM " . self::TABLE .
        " WHERE " . self::COL_USERNAME . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      // Obtener resultados
      $data = $db->select($query, [ $username ]);

      if ($data === false) throw new \Exception(DB::DB_GET_ERROR);

      return $data[0] ?? null;
    }


    /**
     * Retorna un `User` mediante su `name`
     *
     * @param string $name `name` a buscar. Se busca que *contenga* y es *Case insensitive*
     *
     * @return ?array Si se encuntra un o varios `User`, se retorna un array con arrys con la estructura `["name" => string, "name" => string]` por cada `User` encontrado. Retorna `null` si no se enecuntra un `User`
     *
     * @throws \Exception Si se produce algún error
     */
    public static function getUsersByName(string $name) {
      $query = "SELECT " . self::COL_USERNAME . ", " . self::COL_NAME .
        " FROM " . self::TABLE .
        " WHERE " . self::COL_NAME . " like ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      // Obtener resultados
      $data = $db->select($query, [ "%$name%" ]);

      if ($data === false) throw new \Exception(DB::DB_GET_ERROR);

      return $data;
    }
}
