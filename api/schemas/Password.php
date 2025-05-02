<?php
require_once __DIR__ . "/../env.php";
require_once __DIR__ . "/../" . PASSWORDS_MODEL_PATH;
require_once __DIR__ . "/../" . FOLDERS_MODEL_PATH;


/**
 * Clase que permite validar la data de un `User`
 */
class PasswordSchema {
  /* Constantes */
  public const NAME_REGEX = "/^.{1,50}$/";
    public const USERNAME_REGEX = "/^.{1,50}$/";
    public const PASSWORD_REGEX = "/^.{1,50}$/";
    public const NOTES_REGEX = "/^.{1,65535}$/";


  /* Métodos */
    /**
     * Valida la data de una `Password`. Retorna un **array asociativo** con un *array* con la **data validada** y otro *array* de **errores** (si se ha encontrado alguno):
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

      self::validateName($data, $result);
      self::validateFolder($data, $result);
      self::validatePassword($data, $result);
      self::validateUsername($data, $result);
      self::validateUrls($data, $result);
      self::validateNotes($data, $result);
      $result["data"]["owner_user_id"] = $data["owner_user_id"];

      return $result;
    }


    /**
     * Valida parcialmente la data de una `Password`. Retorna un **array asociativo** con un *array* con la **data validada** y otro *array* de **errores** (si se ha encontrado alguno):
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

      if (isset($newData[PasswordsModel::COL_NAME]) && $newData[PasswordsModel::COL_NAME] !== $data[PasswordsModel::COL_NAME]) {
        self::validateName($newData, $result);
      }

      if (array_key_exists(PasswordsModel::COL_FOLDER_ID, $newData) && $newData[PasswordsModel::COL_FOLDER_ID] !== $data[PasswordsModel::COL_FOLDER_ID]) {
        self::validateFolder($newData, $result);
      }

      if (isset($newData[PasswordsModel::COL_PASSWORD]) && $newData[PasswordsModel::COL_PASSWORD] !== $data[PasswordsModel::COL_PASSWORD]) {
        self::validatePassword($newData, $result);
      }

      if (isset($newData[PasswordsModel::COL_USERNAME]) && $newData[PasswordsModel::COL_USERNAME] !== $data[PasswordsModel::COL_USERNAME]) {
        self::validateUsername($newData, $result);
      }

      if (array_key_exists(PasswordsModel::COL_URLS, $newData) && $newData[PasswordsModel::COL_URLS] !== $data[PasswordsModel::COL_URLS]) {
        self::validateUrls($newData, $result);
      }

      if (isset($newData[PasswordsModel::COL_NOTES]) && $newData[PasswordsModel::COL_NOTES] !== $data[PasswordsModel::COL_NOTES]) {
        self::validateNotes($newData, $result);
      }

      unset($data[FoldersModel::COL_OWNER_ID]);

      return $result;
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
      $name = isset($data[PasswordsModel::COL_NAME]) ? trim($data[PasswordsModel::COL_NAME]) : null;

      // Comprobar que existe el campo
      if ($name === null) {
        $result["errors"][] = "'" . PasswordsModel::COL_NAME . "' es obligatorio";
        return;
      }

      // Comprobar formato
      if (!preg_match(self::NAME_REGEX, $name)
      ) {
        $result["errors"][] = "'" . PasswordsModel::COL_NAME . "' debe tener una longitud entre 1 y 50 caracteres (ambos incluidos). Se admite cualquier carácter. Los espacios sobrantes al principio y final del '" . PasswordsModel::COL_NAME . "' son eliminados";
        return;
      }

      $result["data"]["name"] = $name;
    }


    /**
     * Valida el campo `folder_id`:
     * - Es opcional (`null`)
     * - Si se especifica, debe existir la `Folder`
     *
     * En `$result` se agrega la data validada o el mensaje de error correspondiente
     *
     * @param array<string, mixed> $data Data a validar
     * @param array{data: array, errors: array} $result *pasado por referencia
     */
    private static function validateFolder(array $data, array &$result): void {
      $folder = isset($data[PasswordsModel::COL_FOLDER_ID]) ? trim($data[PasswordsModel::COL_FOLDER_ID]) : null;

      if ($folder === null) {
        $result["data"]["folder_id"] = null;
        return;
      }

      $checkFolder = FoldersModel::getFolderByUserIdAndId($data[FoldersModel::COL_OWNER_ID], $data[PasswordsModel::COL_FOLDER_ID]);

      if ($checkFolder === null) {
        $result["errors"][] = "'" . PasswordsModel::COL_FOLDER_ID . "' no existe o no es válido";
        return;
      }

      $result["data"]["folder_id"] = $folder;
    }


    /**
     * Valida el campo `password`:
     * - Es opcional (`null`)
     * - Longitud entre `1` y `50` caracteres
     * - Se admite cualquier carácter
     *
     * En `$result` se agrega la data validada o el mensaje de error correspondiente
     *
     * @param array<string, mixed> $data Data a validar
     * @param array{data: array, errors: array} $result *pasado por referencia
     */
    private static function validatePassword(array $data, array &$result): void {
      $password = isset($data[PasswordsModel::COL_PASSWORD]) ? trim($data[PasswordsModel::COL_PASSWORD]) : null;

      if ($password !== null && !preg_match(self::PASSWORD_REGEX, $password)) {
        $result["errors"][] = "'" . PasswordsModel::COL_PASSWORD . "' debe tener una longitud entre 1 y 50 caracteres (ambos incluidos). Se admite cualquier carácter. Los espacios sobrantes al principio y final del '" . PasswordsModel::COL_PASSWORD . "' son eliminados";
        return;
      }

      $result["data"]["password"] = $password;
    }


    /**
     * Valida el campo `username`:
     * - Es opcional (`null`)
     * - Longitud entre `1` y `50` caracteres
     * - Se admite cualquier carácter
     *
     * En `$result` se agrega la data validada o el mensaje de error correspondiente
     *
     * @param array<string, mixed> $data Data a validar
     * @param array{data: array, errors: array} $result *pasado por referencia
     */
    private static function validateUsername(array $data, array &$result): void {
      $username = isset($data[PasswordsModel::COL_USERNAME]) ? trim($data[PasswordsModel::COL_USERNAME]) : null;

      if ($username !== null && !preg_match(self::USERNAME_REGEX, $username)) {
        $result["errors"][] = "'" . PasswordsModel::COL_USERNAME . "' debe tener una longitud entre 1 y 50 caracteres (ambos incluidos). Se admite cualquier carácter. Los espacios sobrantes al principio y final del '" . PasswordsModel::COL_USERNAME . "' son eliminados";
        return;

      }

      $result["data"]["username"] = $username;
    }


    /**
     * Valida el campo `urls`:
     * - Es opcional (`null`)
     * - Array de 1 hasta 5 *Strings*
     *
     * En `$result` se agrega la data validada o el mensaje de error correspondiente
     *
     * @param array<string, mixed> $data Data a validar
     * @param array{data: array, errors: array} $result *pasado por referencia
     */
    private static function validateUrls(array $data, array &$result): void {
      $urls = $data[PasswordsModel::COL_URLS] ?? null;

      if ($urls !== null && (count($data[PasswordsModel::COL_URLS]) < 1 || count($data[PasswordsModel::COL_URLS]) > 5)) {
        $result["errors"][] = "'" . PasswordsModel::COL_URLS . "' se pueden almacenar de 1 hasta 5 URLs";
        return;
      }

      $result["data"]["urls"] = $urls === null ? $urls : json_encode(
        $data[PasswordsModel::COL_URLS],
        flags: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
      );
    }


    /**
     * Valida el campo `notes`:
     * - Es opcional (`null`)
     * - Longitud entre `1` y `65535` caracteres
     * - Se admite cualquier carácter
     *
     * En `$result` se agrega la data validada o el mensaje de error correspondiente
     *
     * @param array<string, mixed> $data Data a validar
     * @param array{data: array, errors: array} $result *pasado por referencia
     */
    private static function validateNotes(array $data, array &$result): void {
      $notes = isset($data[PasswordsModel::COL_NOTES]) ? trim($data[PasswordsModel::COL_NOTES]) : null;

      if ($notes !== null && !preg_match(self::NOTES_REGEX, $notes)) {
        $result["errors"][] = "'" . PasswordsModel::COL_NOTES . "' debe tener una longitud entre 1 y 65535 caracteres (ambos incluidos). Se admite cualquier carácter. Los espacios sobrantes al principio y final del '" . PasswordsModel::COL_NOTES . "' son eliminados";
        return;
      }

      $result["data"]["notes"] = $notes;
    }
}
