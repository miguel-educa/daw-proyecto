<?php
require_once __DIR__ . "/../env.php";
require_once __DIR__ . "/../" . DB_TOOLS_PATH;
require_once __DIR__ . "/../" . ENCRYPT_TOOLS_PATH;


/**
 * Modelo de `User` para interactuar con la base de datos
 */
class SessionsModel {
  /* Constantes */
    public const TABLE = "sessions";
    public const COL_ID = "id";
    public const COL_USER_ID = "user_id";
    public const COL_TOKEN = "token";
    public const COL_TOKEN_CREATED = "token_created_at";
    public const COL_TOKEN_EXPIRES = "token_expires_at";
    public const COL_REVOKED = "revoked";
    public const COL_USER_AGENT = "user_agent";


  /* Métodos */
    /**
     * Retorna una `Session` mediante el `id`
     *
     * @param string $id `id` de la `Session` a buscar
     *
     * @return ?array Si se encuntra una `Session`, retorna un `array` con la estructura
     * `["id" => string, "user_id" => string, "token" => string, "token_created_at" => int, "token_expires_at" => int, "revoked" => bool, "user_agent" => string]`, si no se encuentra, retorna `null`
     *
     * @throws \Exception Si se produce algún error
     */
    public static function getSessionById(string $id): ?array {
      $query = "SELECT " . self::COL_ID . ", " . self::COL_USER_ID . ", " . self::COL_TOKEN . ", UNIX_TIMESTAMP(" . self::COL_TOKEN_CREATED . ") as " . self::COL_TOKEN_CREATED . ", UNIX_TIMESTAMP(" . self::COL_TOKEN_EXPIRES . ") as " . self::COL_TOKEN_EXPIRES . ", " . self::COL_REVOKED . ", " . self::COL_USER_AGENT .
        " FROM " . self::TABLE .
        " WHERE " .
            self::COL_ID . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      // Obtener resultados
      $data = $db->select($query, [ $id ]);

      if ($data === false) throw new \Exception(DB::DB_GET_ERROR);

      if (count($data) === 0) return null;

      $data[0][self::COL_REVOKED] = $data[0][self::COL_REVOKED] === 1;
      return $data[0];
    }


    /**
     * Retorna una `Session` mediante el `token` y el `user_agent`
     *
     * @param ?string $token `token` de la `Session` a buscar
     * @param string $userAgent `id` de la `Session` a buscar
     *
     * @return ?array Si se encuntra una `Session` válida (no revocada y no expirada), retorna un `array` con la estructura con la estructura
     * `["id" => string, "user_id" => string]`, si no se encuentra, retorna `null`
     *
     * @throws \Exception Si se produce algún error
     */
    public static function getActiveSessionByTokenAndUserAgent(?string $token, string $userAgent): ?array {
      if ($token === null) return null;

      $query = "SELECT " . self::COL_ID . ", " . self::COL_USER_ID .
        " FROM " . self::TABLE .
        " WHERE " .
            self::COL_REVOKED . " = false AND " .
            self::COL_TOKEN_EXPIRES . " > CURRENT_TIMESTAMP AND " .
            self::COL_TOKEN . " = ? AND " .
            self::COL_USER_AGENT . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      // Obtener resultados
      $data = $db->select($query, [ $token, $userAgent ]);

      if ($data === false) throw new \Exception(DB::DB_GET_ERROR);

      return $data[0] ?? null;
    }


    /**
     * Crea y retorna una `Session`
     *
     * @param string $userId Id del `User` que está creando la `Session`
     * @param string $userAgent *User Agent* del dispositivo que está creando la `Session`
     * @param int $tokenExpiresAt Timestamp de expiración de la `Session`
     *
     * @return array Se retorna un array con la estructura `["id" => string, "user_id" => string, "token" => string, "token_created_at" => int, "token_expires_at" => int, "revoked" => bool, "user_agent" => string]`
     *
     * @throws \Exception Si se produce algún error
     */
    public static function create(string $userId, string $userAgent, int $tokenExpiresAt): array {
      $id = Encrypt::generateUUIDv4();
      $token = Encrypt::generateSessionToken();

      $query = "INSERT INTO " . self::TABLE . "
          (" . self::COL_ID . ", " . self::COL_USER_ID . ", " . self::COL_TOKEN . ", " . self::COL_USER_AGENT . ", " . self::COL_TOKEN_EXPIRES . ")
          VALUES (?, ?, ?, ?, FROM_UNIXTIME(?))";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      $db->addQuery($query, [
        $id,
        $userId,
        $token,
        $userAgent,
        $tokenExpiresAt
      ]);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_CREATE_ERROR);

      $newResource = self::getSessionById($id);

      if ($newResource === null) throw new \Exception(DB::DB_GET_ERROR);

      return $newResource;
    }


    /**
     * Actualiza una `Session` como revocada
     *
     * @param string $id `id` de la `Session` a actualizar
     *
     * @return bool `true` si se ha revocado con éxito, `false` si no
     *
     * @throws \Exception Si se produce algún error
     */
    public static function revokeSession(string $id): bool {
      $query = "UPDATE " . self::TABLE . " SET " . self::COL_REVOKED . " = true WHERE " . self::COL_ID . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      $db->addQuery($query, [ $id ]);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_UPDATE_ERROR);

      $resource = self::getSessionById($id);

      if ($resource === null) throw new \Exception(DB::DB_GET_ERROR);

      return $resource["revoked"];
    }
}
