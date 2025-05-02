<?php
require_once __DIR__ . "/../env.php";
require_once __DIR__ . "/../" . DB_TOOLS_PATH;


/**
 * Modelo de `User` para interactuar con la base de datos
 */
class PasswordsModel {
  /* Constantes */
    public const TABLE = "passwords";
    public const COL_ID = "id";
    public const COL_OWNER_ID = "owner_user_id";
    public const COL_FOLDER_ID = "folder_id";
    public const COL_NAME = "name";
    public const COL_PASSWORD = "password";
    public const COL_USERNAME = "username";
    public const COL_URLS = "urls";
    public const COL_NOTES = "notes";


  /* Métodos */
    /**
     * Retorna todas las `Password` de un usuario
     *
     * @param string $userId `id` del `User` a buscar. *Case insensitive*
     *
     * @return array Array con las `Password` con la estructura `["id" => string, "folder_id" => string, "name" => string, "password" => string, "username" => string, "urls" => array, "notes" => string]`
     *
     * @throws \Exception Si se produce algún error
     */
    public static function getPasswordsByUserId(string $userId): array {
      $query = "SELECT " . self::COL_ID . ", " . self::COL_FOLDER_ID . ", " . self::COL_NAME . ", " . self::COL_PASSWORD . ", " . self::COL_USERNAME . ", " . self::COL_URLS . ", " . self::COL_NOTES .
        " FROM " . self::TABLE .
        " WHERE " . self::COL_OWNER_ID . " = ?
        ORDER BY " . self::COL_NAME . " ASC";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      // Obtener resultados
      $data = $db->select($query, [ $userId ]);

      if ($data === false) throw new \Exception(DB::DB_GET_ERROR);

      foreach ($data as &$item) {
        $item["urls"] = json_decode($item["urls"], true);
        $item["password"] = $item["password"] !== null ? Encrypt::decrypt($item["password"]) : null;
      }

      return $data;
    }


    /**
     * Retorna una `Password` de un usuario mediante su `id`
     *
     * @param string $userId `id` del `User` a buscar. *Case insensitive*
     * @param string $id `id` de la `Password` a buscar. *Case insensitive*
     *
     * @return ?array Se retorna un array con la estructura `["id" => string, "folder_id" => string, "name" => string, "password" => string, "username" => string, "urls" => array, "notes" => string]`. `null` si no se encuentra
     *
     * @throws \Exception Si se produce algún error
     */
    public static function getPasswordByUserIdAndId(string $userId, string $id): ?array {
      $query = "SELECT " . self::COL_ID . ", " . self::COL_FOLDER_ID . ", " . self::COL_NAME . ", " . self::COL_PASSWORD . ", " . self::COL_USERNAME . ", " . self::COL_URLS . ", " . self::COL_NOTES .
        " FROM " . self::TABLE .
        " WHERE " . self::COL_OWNER_ID . " = ? AND " . self::COL_ID . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      // Obtener resultados
      $data = $db->select($query, [ $userId, $id ]);

      if ($data === false) throw new \Exception(DB::DB_GET_ERROR);

      if ($data === null) return null;

      $data[0]["urls"] = json_decode($data[0]["urls"], true);
      $data[0]["password"] = $data[0]["password"] === null ? $data[0]["password"] : Encrypt::decrypt($data[0]["password"]);

      return $data[0] ?? null;
    }


    /**
     * Crea y retorna una `Password`
     *
     * @param array $data Array asociativo con los datos de la `Password` a crear. Debe contener:
     * - `name`: Nombre de la `Password`
     * - `folder_id`: ID del `Folder` que almacena la `Password`
     * - `password`: Contraseña de la `Password`
     * - `username`: Nombre de usuario de la `Password`
     * - `urls`: *Array* de *Strings* con las URLs de la `Password` (de 1 a 5 items)
     * - `notes`: Notas de la `Password`
     *
     * @return array Se retorna un array con la estructura `["id" => string, "folder_id" => string, "name" => string, "password" => string, "username" => string, "urls" => array, "notes" => string]`
     *
     * @throws \Exception Si se produce algún error
     */
    public static function create(array $data): array {
      // Generar campos
      $id = Encrypt::generateUUIDv4();

      $query = "INSERT INTO " . self::TABLE . "
         (" . self::COL_ID . ", " . self::COL_OWNER_ID . ", " . self::COL_FOLDER_ID . ", " . self::COL_NAME . ", " . self::COL_PASSWORD . ", " . self::COL_USERNAME . ", " . self::COL_URLS . ", " . self::COL_NOTES . ")
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      $db->addQuery($query, [
        $id,
        $data[self::COL_OWNER_ID],
        $data[self::COL_FOLDER_ID],
        $data[self::COL_NAME],
        $data[self::COL_PASSWORD] === null ? null : Encrypt::encrypt($data[self::COL_PASSWORD]),
        $data[self::COL_USERNAME],
        $data[self::COL_URLS],
        $data[self::COL_NOTES]
      ]);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_CREATE_ERROR);

      $newResource = self::getPasswordByUserIdAndId($data[self::COL_OWNER_ID], $id);

      if ($newResource === null) throw new \Exception(DB::DB_GET_ERROR);

      return $newResource;
    }


    /**
     * Actualiza una `Password`
     * @param array $data Array asociativo con los datos de la `Password` a actualizar
     * @param mixed $passwordId ID de la `Password` a actualizar
     * @param mixed $userId ID del `User` que es el dueño
     *
     * @return array Se retorna un array con la estructura `["id" => string, "folder_id" => string, "name" => string, "password" => string, "username" => string, "urls" => array, "notes" => string]`
     *
     * @throws \Exception Si se produce algún error
     */
    public static function update(array $data, $passId, $userId): array {
      if (isset($data[self::COL_PASSWORD])) {
        $data[self::COL_PASSWORD] = Encrypt::encrypt($data[self::COL_PASSWORD]);
      }

      // Claves y valores
      $keys = implode(" = ?,", array_keys($data));
      $values = array_values($data);
      $values[] = $passId;

      $query = "UPDATE " . self::TABLE . " SET $keys = ? WHERE " . self::COL_ID . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      $db->addQuery($query, $values);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_UPDATE_ERROR);

      $updatedPassword = self::getPasswordByUserIdAndId($userId, $passId);

      if ($updatedPassword === null) throw new \Exception(DB::DB_GET_ERROR);

      return $updatedPassword;
    }


    /**
     * Elimina una `Password`
     * @param string $id ID de la `Password` a eliminar
     *
     * @return bool `true` si se eliminó con éxito
     *
     * @throws \Exception Si se produce algún error
     */
    public static function delete(string $id) {
      $query = "DELETE FROM " . self::TABLE . " WHERE " . self::COL_ID . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      $db->addQuery($query, [ $id ]);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_DELETE_ERROR);

      return true;
    }
}
