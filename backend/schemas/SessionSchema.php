<?php
require_once __DIR__ . "/../env.php";
require_once __DIR__ . "/../" . USERS_MODEL_PATH;
require_once __DIR__ . "/../" . SESSION_DURATION_PATH;


/**
 * Clase que permite validar la data de un `User`
 */
class SessionSchema {
  /* Métodos */
    /**
     * Valida la data de una `Session`. Retorna un **array asociativo** con un *array* con la **data validada** y otro *array* de **errores** (si se ha encontrado alguno):
     * `["data" => [...], "errors" => [...]]`
     *
     * @param array<string, mixed> $data Data a validar
     *
     * @return array{data: array, errors: array}
     */
    public static function validate(array $data): array {
      $result = [
        "data" => [],
        "errors" => []
      ];

      self::validateUsername($data, $result);
      self::validateSessionDuration($data, $result);
      self::validateMasterPassword($data, $result);

      return $result;
    }


    /**
     * Valida el campo `username`:
     * - Debe existir
     *
     * En `$result` se agrega la data validada o el mensaje de error correspondiente
     *
     * @param array<string, mixed> $data Data a validar
     * @param array{data: array, errors: array} $result *pasado por referencia
     */
    public static function validateUsername(array $data, array &$result): void {
      $username = isset($data[UsersModel::COL_USERNAME]) ? trim($data[UsersModel::COL_USERNAME]) : null;

      // Comprobar que existe el campo
      if ($username === null) {
        $result["errors"][] = "'" . UsersModel::COL_USERNAME . "' es obligatorio";
        return;
      }

      $result["data"]["username"] = $username;
    }


    /**
     * Valida el campo `name`:
     * - Si existe y es una duración permitida, se establece ese valor
     * - Si no existe o no es una duración permitida, se establece `SessionDuration::ONE_HOUR`
     *
     * @param array<string, mixed> $data Data a validar
     * @param array{data: array, errors: array} $result *pasado por referencia
     */
    private static function validateSessionDuration(array $data, array &$result): void {
      $duration = isset($data["session_duration"]) ? trim($data["session_duration"]) : null;

      $result["data"]["session_duration"] = is_numeric($duration)
        ? SessionDuration::tryFrom($duration) ?? SessionDuration::ONE_HOUR
        : SessionDuration::ONE_HOUR;
    }


    /**
     * Valida el campo `master_password`:
     * - Que exista
     *
     * En `$result` se agrega la data validada o el mensaje de error correspondiente
     *
     * @param array<string, mixed> $data Data a validar
     * @param array{data: array, errors: array} $result *pasado por referencia
    */
    public static function validateMasterPassword($data, &$result): void {
    $masterPassword = isset($data[UsersModel::COL_M_PASSWORD]) ? trim($data[UsersModel::COL_M_PASSWORD]) : null;

    // Comprobar que existe el campo
    if ($masterPassword === null) {
      $result["errors"][] = "'" . UsersModel::COL_M_PASSWORD . "' es obligatorio";
      return;
    }

    $result["data"]["master_password"] = $masterPassword;
  }
}
