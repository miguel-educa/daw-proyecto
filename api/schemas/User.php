<?php
require_once __DIR__ . "/../env.php";
require_once __DIR__ . "/../" . USERS_MODEL_PATH;
require_once __DIR__ . "/../" . ENCRYPT_TOOLS_PATH;


/**
 * Clase que permite validar la data de un `User`
 */
class UserSchema {
  /* Constantes */
    public const USERNAME_REGEX = "/^[a-zA-Z][a-zA-Z0-9_]{0,29}$/";
    public const NAME_REGEX = "/^.{1,50}$/";


  /* Métodos */
    /**
     * Valida la data de un `User`. Retorna un **array asociativo** con un *array* con la **data validada** y otro *array* de **errores** (si se ha encontrado alguno):
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
      self::validateName($data, $result);
      self::validateMasterPassword($data, $result);

      return $result;
    }


    /**
     * Valida parcialmente la data de un `User`. Retorna un **array asociativo** con un *array* con la **data validada** y otro *array* de **errores** (si se ha encontrado alguno):
     * `["data" => [...], "errors" => [...]]`
     *
     * @param array<string, mixed> $newData Data a validar
     * @param array<string, mixed> $data Data existente
     *
     * @return array{data: array, errors: array}
     */
    public static function partialValidate(array $newData, array $data): array {
      $result = [
        "data" => [],
        "errors" => []
      ];

      if (isset($newData[UsersModel::COL_NAME]) && $newData[UsersModel::COL_NAME] !== $data[UsersModel::COL_NAME]) {
        self::validateName($newData, $result);
      }

      if (isset($newData[UsersModel::COL_REC_CODE]) && $newData[UsersModel::COL_REC_CODE] === true) {
        $result["data"][UsersModel::COL_REC_CODE] = Encrypt::generateRecuperationCode();
      }

      if (isset($newData[UsersModel::COL_M_PASSWORD])) {
        self::validateMasterPassword($newData, $result);
      }

      return $result;
    }


    /**
     * Valida el campo `username`:
     * - Debe existir
     * - Longitud entre `1` y `30` caracteres
     * - Sólo se admiten letras del alfabeto inglés (minúsculas y mayúsculas) y `_`
     * - No puede comenzar por `_`
     * - No puede ser utilizado por otro `User`
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

      // Comprobar formato
      if (!preg_match(self::USERNAME_REGEX, $username)) {
      $result["errors"][] = "'" . UsersModel::COL_USERNAME . "' debe tener una longitud entre 1 y 30 caracteres (ambos incluidos). Sólo se admiten letras del alfabeto inglés, tanto minúsculas como mayúsculas, y barra baja '_'. No puede comenzar directamente con una barra baja. No se diferencian las mayúsculas de minúsculas";
        return;
      }

      // Comprobar si está en uso
      $checkUser = UsersModel::getUserByUsername($data[UsersModel::COL_USERNAME]);

      if ($checkUser !== null) {
        $result["errors"][] = "'" . UsersModel::COL_USERNAME . "' no disponible";
        return;
      }

      $result["data"]["username"] = $username;
    }


    /**
     * Valida el campo `name`:
     * - Debe existir
     * - Longitud entre `1` y `50` caracteres
     * - Se admite cualquier carácter
     *
     * En `$result` se agrega la data validada o el mensaje de error correspondiente
     *
     * @param array<string, mixed> $data Data a validar
     * @param array{data: array, errors: array} $result *pasado por referencia
     */
    private static function validateName(array $data, array &$result): void {
      $name = isset($data[UsersModel::COL_NAME]) ? trim($data[UsersModel::COL_NAME]) : null;

      // Comprobar que existe el campo
      if ($name === null) {
        $result["errors"][] = "'" . UsersModel::COL_NAME . "' es obligatorio";
        return;
      }

      // Comprobar formato
      if (!preg_match(self::NAME_REGEX, $name)
      ) {
        $result["errors"][] = "'" . UsersModel::COL_NAME . "' debe tener una longitud entre 1 y 50 caracteres (ambos incluidos). Se admite cualquier carácter. Los espacios sobrantes al principio y final del '" . UsersModel::COL_NAME . "' son eliminados";
        return;
      }

      $result["data"]["name"] = $name;
    }


    /**
     * Valida el campo `master_password`:
     * - Longitud entre 8 y 50 caracteres
     * - No puede contener caracteres no válidos
     *     - Se aceptan letras del alfabeto inglés (mayúsculas y minúsculas), números y los siguientes caracteres especiales `_-,;!.@*&#%+$/`
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
    $hasInvalidChars = preg_match("/[^a-zA-Z0-9_\-,;!.@*&#%+$\/]/", $masterPassword);
    $hasLower = preg_match("/[a-z]/", $masterPassword);
    $hasUpper = preg_match("/[A-Z]/", $masterPassword);
    $hasNumber = preg_match("/[0-9]/", $masterPassword);
    $hasSpecial = preg_match("/[_\-,;!.@*&#%+$\/]/", $masterPassword);

    if (
      $length < 8 ||
      $length > 50 ||
      $hasInvalidChars ||
      !$hasLower ||
      !$hasUpper ||
      !$hasNumber ||
      !$hasSpecial
    ) {
      $result["errors"][] = "'" . UsersModel::COL_M_PASSWORD . "' debe tener una longitud entre 8 y 50 caracteres. Debe contener al menos una letra minúscula y una letra mayúscula (alfabeto inglés), un número y alguno de los siguientes símbolos especiales '_-,;!.@*&#%+$/'. No se admiten otros caracteres";
      return;
    }

    $result["data"]["master_password"] = $masterPassword;
  }
}
