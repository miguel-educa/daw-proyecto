<?php
require_once __DIR__ . "/../env.php";
require_once __DIR__ . "/../" . DB_TOOLS_PATH;


/**
 * Modelo de `User` para interactuar con la base de datos
 */
class FoldersModel {
  /* Constantes */
    public const TABLE = "folders";
    public const COL_ID = "id";
    public const COL_OWNER_ID = "owner_user_id";
    public const COL_NAME = "name";


  /* Métodos */
    /**
     * Retorna todas las `Folder` de un usuario
     *
     * @param string $userId `owner_user_id` a buscar. *Case insensitive*
     *
     * @return ?array Array con los `Folder` de un usuario. Los items tienen la estructura `["id" => string, "name" => string]`
     *
     * @throws \Exception Si se produce algún error
     */
    public static function getFoldersByUserId(string $userId): ?array {
      $query = "SELECT " . self::COL_ID . ", " . self::COL_NAME .
        " FROM " . self::TABLE .
        " WHERE " . self::COL_OWNER_ID . " = ?
        ORDER BY " . self::COL_NAME . " ASC";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      // Obtener resultados
      $data = $db->select($query, [ $userId ]);

      if ($data === false) throw new \Exception(DB::DB_GET_ERROR);

      return $data;
    }


    /**
     * Retorna un `Folder` de un usuario mediante el nombre de ésta
     *
     * @param string $userId `owner_user_id` a buscar. *Case insensitive*
     * @param string $name `name` a buscar. *Case insensitive*
     *
     * @return ?array Se retorna un array con la estructura `["id" => string, "name" => string]`, `null` si no se encuentra
     *
     * @throws \Exception Si se produce algún error
     */
    public static function getFolderByUserIdAndName(string $userId, string $name): ?array {
      $query = "SELECT " . self::COL_ID . ", " . self::COL_NAME .
        " FROM " . self::TABLE .
        " WHERE " . self::COL_OWNER_ID . " = ? AND " . self::COL_NAME . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      // Obtener resultados
      $data = $db->select($query, [ $userId, $name ]);

      if ($data === false) throw new \Exception(DB::DB_GET_ERROR);

      return $data[0] ?? null;
    }


    /**
     * Retorna un `Folder` de un usuario mediante el id de ésta
     *
     * @param string $userId `owner_user_id` a buscar. *Case insensitive*
     * @param string $id `id` a buscar. *Case insensitive*
     *
     * @return ?array Se retorna un array con la estructura `["id" => string, "name" => string]`, `null` si no se encuentra
     *
     * @throws \Exception Si se produce algún error
     */
    public static function getFolderByUserIdAndId(string $userId, string $id): ?array {
      $query = "SELECT " . self::COL_ID . ", " . self::COL_NAME .
        " FROM " . self::TABLE .
        " WHERE " . self::COL_OWNER_ID . " = ? AND " . self::COL_ID . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      // Obtener resultados
      $data = $db->select($query, [ $userId, $id ]);

      if ($data === false) throw new \Exception(DB::DB_GET_ERROR);

      return $data[0] ?? null;
    }


    /**
     * Crea y retorna un `Folder`
     *
     * @param array $data Array asociativo con los deatos del `Folder` a crear. Debe contener:
     * - `owner_user_id`: ID del `User` dueño del `Folder`
     * - `name`: Nombre del `User`
     *
     * @return array Se retorna un array con la estructura `["id" => string, "name" => string]`, `null` si no se encuentra
     *
     * @throws \Exception Si se produce algún error
     */
    public static function create(array $data): array {
      // Generar campos
      $id = Encrypt::generateUUIDv4();

      $query = "INSERT INTO " . self::TABLE . "
         (" . self::COL_ID . ", " . self::COL_OWNER_ID . ", " . self::COL_NAME . ")
         VALUES (?, ?, ?)";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      $db->addQuery($query, [
        $id,
        $data[self::COL_OWNER_ID],
        $data[self::COL_NAME]
      ]);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_CREATE_ERROR);

      $newResource = self::getFolderByUserIdAndName($data[self::COL_OWNER_ID], $data[self::COL_NAME]);

      if ($newResource === null) throw new \Exception(DB::DB_GET_ERROR);

      return $newResource;
    }
}
