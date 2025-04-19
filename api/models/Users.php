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
    public const COL_ID = "id";
    public const COL_USERNAME = "username";
    public const COL_NAME = "name";
    public const COL_M_PASSWORD = "master_password";
    public const COL_M_PASSWORD_EDITED = "master_password_edited_at";
    public const COL_REC_CODE = "recuperation_code";
    public const COL_REC_CODE_EDITED = "recuperation_code_edited_at";
    public const COL_TOTP_2FA_SECRET = "totp_2fa_secret";


  /* Métodos */
    /**
     * Retorna un `User` mediante su `id`
     *
     * @param string $id `id` a buscar. *Case insensitive*
     * @param bool $confidentialData Especifica se se deben retornar datos sensibles del usuario. Default: `false`
     *
     * @return ?array Si se encuntra un `User`, `null` si no
     * - `$confidentialData = false`: Se retorna un array con la estructura `["username" => string, "name" => string]`
     * - `$confidentialData = true`: Se retorna un array con la estructura `["id" => string, "username" => string, "name" => string, "master_password_edited_at" => int, "recuperation_code" => string, "recuperation_code_edited_at" => int]`
     *
     * @throws \Exception Si se produce algún error
     */
    public static function getUserById(string $id, bool $confidentialData = false): ?array {
        $query = $confidentialData
          ? "SELECT " . self::COL_ID . ", " . self::COL_USERNAME . ", " . self::COL_NAME . ", UNIX_TIMESTAMP(" . self::COL_M_PASSWORD_EDITED . ") as " . self::COL_M_PASSWORD_EDITED . ", " . self::COL_REC_CODE . ", UNIX_TIMESTAMP(" . self::COL_REC_CODE_EDITED . ") as " . self::COL_REC_CODE_EDITED . ", " . self::COL_TOTP_2FA_SECRET .
            " FROM " . self::TABLE .
            " WHERE " . self::COL_ID . " = ?"
          : "SELECT " . self::COL_ID . ", " . self::COL_USERNAME . ", " . self::COL_NAME .
            " FROM " . self::TABLE .
            " WHERE " . self::COL_ID . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      // Obtener resultados
      $data = $db->select($query, [ $id ]);

      if ($data === false) throw new \Exception(DB::DB_GET_ERROR);

      return $data[0] ?? null;
    }


    /**
     * Retorna un `User` mediante su `username`
     *
     * @param string $username `username` a buscar. *Case insensitive*
     *
     * @return ?array Si se encuentra un `User`, se retorna un array con la estructura `["username" => string, "name" => string]`. Retorna `null` si no se enecuntra un `User`
     *
     * @throws \Exception Si se produce algún error
     */
    public static function getUserByUsername(string $username, bool $confidentialData = false): ?array {
      $query = "SELECT " . self::COL_USERNAME . ", " . self::COL_NAME . ($confidentialData ? ", " . self::COL_M_PASSWORD . ", " . self::COL_ID . ", " . self::COL_TOTP_2FA_SECRET : "") .
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
     * @return array Si se encuentra uno o varios `User`, se retorna un array con arrys con la estructura `["name" => string, "name" => string]` por cada `User` encontrado. Si no se encuntra ningún `User`, se retorna un array vacío
     *
     * @throws \Exception Si se produce algún error
     */
    public static function getUsersByName(string $name): array {
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


    /**
     * Crea y retorna un `User`
     *
     * @param array $data Array asociativo con los deatos del `User` a crear. Debe contener:
     * - `username`: Nombre del `User`
     * - `name`: Nombre del `User`
     * - `master_password`: Contraseña del `User`
     *
     * @return array Se retorna un array con la estructura `["id" => string, "username" => string, "name" => string, "master_password_edited_at" => int, "recuperation_code" => string, "recuperation_code_edited_at" => int]`
     *
     * @throws \Exception Si se produce algún error
     */
    public static function create(array $data): array {
      // Generar campos
      $id = Encrypt::generateUUIDv4();
      $masterPasswordHash = Encrypt::hashMasterPassword($data[self::COL_M_PASSWORD]);
      $recuperationCode = Encrypt::generateRecuperationCode();

      $query = "INSERT INTO " . self::TABLE . "
         (" . self::COL_ID . ", " . self::COL_USERNAME . ", " . self::COL_NAME . ", " . self::COL_M_PASSWORD . ", " . self::COL_REC_CODE . ")
         VALUES (?, ?, ?, ?, ?)";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      $db->addQuery($query, [
        $id,
        $data[self::COL_USERNAME],
        $data[self::COL_NAME],
        $masterPasswordHash,
        $recuperationCode
      ]);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_CREATE_ERROR);

      $newResource = self::getUserById($id, confidentialData: true);

      if ($newResource === null) throw new \Exception(DB::DB_GET_ERROR);

      return $newResource;
    }


    public static function create2FA(string $id, string $secret): bool {
      $query = "UPDATE " . self::TABLE . " SET " . self::COL_TOTP_2FA_SECRET . " = ? WHERE " . self::COL_ID . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      $db->addQuery($query, [
        $secret,
        $id
      ]);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_CREATE_ERROR);

      return true;
    }

    public static function delete2FA(string $id): bool {
      $query = "UPDATE " . self::TABLE . " SET " . self::COL_TOTP_2FA_SECRET . " = NULL WHERE " . self::COL_ID . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      $db->addQuery($query, [
        $id
      ]);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_CREATE_ERROR);

      return true;
    }
}
