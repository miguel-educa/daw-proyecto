<?php
require_once __DIR__ . "/../env.php";
require_once __DIR__ . "/../" . USERS_MODEL_PATH;


/**
 * Clase que permite validar la data de un `User`
 */
class AccountRecoverySchema {
  /* Métodos */
    /**
     * Valida la data de para recuperar una cuenta. Retorna un **array asociativo** con un *array* con la **data validada** y otro *array* de **errores** (si se ha encontrado alguno):
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

      self::validateMasterPassword($data, $result);
      $result["data"][UsersModel::COL_REC_CODE] = $data[UsersModel::COL_REC_CODE] ?? "";
      $result["data"][UsersModel::COL_USERNAME] = $data[UsersModel::COL_USERNAME] ?? "";

      return $result;
    }


    /**
     * Valida el campo `master_password`:
     * - Longitud entre 8 y 50 caracteres
     * - No puede contener caracteres no válidos
     *     - Se aceptan letras del alfabeto inglés (mayúsculas y minúsculas), números y los siguientes caracteres especiales `_-,;!.@*&#%+$/\`
     * - Debe contener al menos:
     *     - Una letra minúscula
     *     - Una letra mayúscula
     *     - Un número
     *     - Un carácter especial
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

      // Comprobar formato y requisitos
      $length = strlen($masterPassword);
      $hasInvalidChars = preg_match("/[^a-zA-Z0-9_\-,;!.@*&#%+$\/\\\]/", $masterPassword);
      $hasLower = preg_match("/[a-z]/", $masterPassword);
      $hasUpper = preg_match("/[A-Z]/", $masterPassword);
      $hasNumber = preg_match("/[0-9]/", $masterPassword);
      $hasSpecial = preg_match("/[_\-,;!.@*&#%+$\/\\\]/", $masterPassword);

      if (
        $length < 8 ||
        $length > 50 ||
        $hasInvalidChars ||
        !$hasLower ||
        !$hasUpper ||
        !$hasNumber ||
        !$hasSpecial
      ) {
        $result["errors"][] = "'" . UsersModel::COL_M_PASSWORD . "' debe tener una longitud entre 8 y 50 caracteres. Debe contener al menos una letra minúscula y una letra mayúscula (alfabeto inglés), un número y alguno de los siguientes símbolos especiales '_-,;!.@*&#%+$/\'. No se admiten otros caracteres";
        return;
      }

      $result["data"]["master_password"] = $masterPassword;
    }
}
