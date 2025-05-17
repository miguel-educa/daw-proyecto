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
    public const COL_TOTP_2FA_ACTIVATED = "totp_2fa_activated";
    public const COL_TOTP_2FA_ACTIVATED_AT = "totp_2fa_activated_at";


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
          ? "SELECT " . self::COL_ID . ", " . self::COL_USERNAME . ", " . self::COL_NAME . ", UNIX_TIMESTAMP(" . self::COL_M_PASSWORD_EDITED . ") as " . self::COL_M_PASSWORD_EDITED . ", " . self::COL_REC_CODE . ", UNIX_TIMESTAMP(" . self::COL_REC_CODE_EDITED . ") as " . self::COL_REC_CODE_EDITED . ", " . self::COL_TOTP_2FA_SECRET . ", " . self::COL_TOTP_2FA_ACTIVATED . ", " . "UNIX_TIMESTAMP(" . self::COL_TOTP_2FA_ACTIVATED_AT . ") as " . self::COL_TOTP_2FA_ACTIVATED_AT .
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

      // Transformar datos
      if (isset($data[0][self::COL_TOTP_2FA_ACTIVATED]))
        $data[0][self::COL_TOTP_2FA_ACTIVATED] = $data[0][self::COL_TOTP_2FA_ACTIVATED] === 1;

      if (isset($data[0][self::COL_REC_CODE]))
        $data[0][self::COL_REC_CODE] = Encrypt::decrypt($data[0][self::COL_REC_CODE]);

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
      $query = $confidentialData
          ? "SELECT " . self::COL_ID . ", " . self::COL_USERNAME . ", " . self::COL_NAME . ", " . self::COL_M_PASSWORD . ", UNIX_TIMESTAMP(" . self::COL_M_PASSWORD_EDITED . ") as " . self::COL_M_PASSWORD_EDITED . ", " . self::COL_REC_CODE . ", UNIX_TIMESTAMP(" . self::COL_REC_CODE_EDITED . ") as " . self::COL_REC_CODE_EDITED . ", " . self::COL_TOTP_2FA_SECRET . ", " . self::COL_TOTP_2FA_ACTIVATED . ", " . "UNIX_TIMESTAMP(" . self::COL_TOTP_2FA_ACTIVATED_AT . ") as " . self::COL_TOTP_2FA_ACTIVATED_AT .
            " FROM " . self::TABLE .
            " WHERE " . self::COL_USERNAME . " = ?"
          : "SELECT " . self::COL_USERNAME . ", " . self::COL_NAME .
            " FROM " . self::TABLE .
            " WHERE " . self::COL_USERNAME . " = ?";


      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      // Obtener resultados
      $data = $db->select($query, [ $username ]);

      if ($data === false) throw new \Exception(DB::DB_GET_ERROR);

      if (isset($data[0]["totp_2fa_activated"]))
        $data[0]["totp_2fa_activated"] = $data[0]["totp_2fa_activated"] === 1;

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
     * Retorna un `User` mediante su `name`
     *
     * @param string $username `name` a buscar. Se busca que *contenga* y es *Case insensitive*
     *
     * @return array Si se encuentra uno o varios `User`, se retorna un array con arrys con la estructura `["name" => string, "name" => string]` por cada `User` encontrado. Si no se encuntra ningún `User`, se retorna un array vacío
     *
     * @throws \Exception Si se produce algún error
     */
    public static function getUsersByUsername(string $username): array {
      $query = "SELECT " . self::COL_USERNAME . ", " . self::COL_NAME .
        " FROM " . self::TABLE .
        " WHERE " . self::COL_USERNAME . " like ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      // Obtener resultados
      $data = $db->select($query, [ "%$username%" ]);

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
      $masterPasswordHash = Encrypt::hash($data[self::COL_M_PASSWORD]);
      $recuperationCode = Encrypt::generateRecuperationCode();
      $recuperationCodeEnc = Encrypt::encrypt($recuperationCode);

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
        $recuperationCodeEnc
      ]);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_CREATE_ERROR);

      $newResource = self::getUserById($id, confidentialData: true);

      if ($newResource === null) throw new \Exception(DB::DB_GET_ERROR);

      return $newResource;
    }


    /**
     * Crea el secret de la autenticación de doble factor
     *
     * @param string $id Id del `User`
     * @param string $secret Secreto generado
     *
     * @return bool `true` si se crea con éxito
     *
     * @throws \Exception Si se produce algún error
     */
    public static function create2FA(string $id, string $secret): bool {
      $secretEnc = Encrypt::encrypt($secret);

      $query = "UPDATE " . self::TABLE . " SET " . self::COL_TOTP_2FA_SECRET . " = ?, " . self::COL_TOTP_2FA_ACTIVATED . " = FALSE, " . self::COL_TOTP_2FA_ACTIVATED_AT . " = NULL WHERE " . self::COL_ID . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      $db->addQuery($query, [
        $secretEnc,
        $id
      ]);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_CREATE_ERROR);

      return true;
    }


    /**
     * Activa la autenticación de doble factor
     *
     * @param string $id Id del `User`
     *
     * @return bool `true` si se activa con éxito
     *
     * @throws \Exception Si se produce algún error
     */
    public static function activate2FA(string $id): bool {
      $query = "UPDATE " . self::TABLE . " SET " . self::COL_TOTP_2FA_ACTIVATED . " = TRUE, " . self::COL_TOTP_2FA_ACTIVATED_AT . " = CURRENT_TIMESTAMP WHERE " . self::COL_ID . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      $db->addQuery($query, [ $id ]);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_CREATE_ERROR);

      return true;
    }


    /**
     * Desactiva y elimina la autenticación de doble factor
     *
     * @param string $id Id del `User`
     *
     * @return bool `true` si se desactiva con éxito
     *
     * @throws \Exception Si se produce algún error
     */
    public static function delete2FA(string $id): bool {
      $query = "UPDATE " . self::TABLE . " SET " . self::COL_TOTP_2FA_SECRET . " = NULL, " . self::COL_TOTP_2FA_ACTIVATED . " = FALSE, " . self::COL_TOTP_2FA_ACTIVATED_AT . " = NULL WHERE " . self::COL_ID . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      $db->addQuery($query, [ $id ]);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_CREATE_ERROR);

      return true;
    }


    /**
     * Actualiza los datos de un usuario que desea recuperar la cuenta
     *
     * @param string $id `id` del usuario
     * @param string $recuperationCode Código de recuperación nuevo
     * @param string $masterPassword Contraseña maestra nueva
     *
     * @return bool `true` si se actualiza con éxito
     *
     * @throws \Exception Si se produce algún error
     */
    public static function recoveryAccount(string $id, string $recuperationCode, string $masterPassword ): bool {
      $masterPasswordHash = Encrypt::hash($masterPassword);
      $recuperationCodeEnc = Encrypt::encrypt($recuperationCode);

      $query = "UPDATE " . self::TABLE . " SET " . self::COL_M_PASSWORD . " = ?, " . self::COL_M_PASSWORD_EDITED . " = CURRENT_TIMESTAMP, " . self::COL_REC_CODE . " = ?, " . self::COL_REC_CODE_EDITED . " = CURRENT_TIMESTAMP, " . self::COL_TOTP_2FA_SECRET . " = NULL, " . self::COL_TOTP_2FA_ACTIVATED . " = FALSE, " . self::COL_TOTP_2FA_ACTIVATED_AT . " = NULL WHERE " . self::COL_ID . " = ?";

      // Conectar DB
      $db = new DB();

      if (!$db->isConnected()) throw new \Exception(DB::DB_CONNECTION_ERROR);

      $db->addQuery($query, [
        $masterPasswordHash,
        $recuperationCodeEnc,
        $id
      ]);

      if ($db->executeTransaction() === false) throw new \Exception(DB::DB_UPDATE_ERROR);

      return true;
    }
}
