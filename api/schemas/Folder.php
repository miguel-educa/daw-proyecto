<?php
require_once __DIR__ . "/../env.php";
require_once __DIR__ . "/../" . FOLDERS_MODEL_PATH;


/**
 * Clase que permite validar la data de un `Folder`
 */
class FolderSchema {
  /* Constantes */
    public const USERNAME_REGEX = "/^[a-zA-Z][a-zA-Z0-9_]{0,29}$/";
    public const NAME_REGEX = "/^.{1,50}$/";


  /* Métodos */
    /**
     * Valida la data de un `Folder`. Retorna un **array asociativo** con un *array* con la **data validada** y otro *array* de **errores** (si se ha encontrado alguno):
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
      $result["data"]["owner_user_id"] = $data["owner_user_id"];

      return $result;
    }


    /**
     * Valida parcialmente la data de un `Folder`. Retorna un **array asociativo** con un *array* con la **data validada** y otro *array* de **errores** (si se ha encontrado alguno):
     * `["data" => [...], "errors" => [...]]`
     *
     * @param array<string, mixed> $newData Data a validar
     *
     * @return array{data: array, errors: array}
     */
    public static function partialValidate(array $newData, array $data): array {
      $result = [
        "data" => [],
        "errors" => []
      ];

      if (isset($newData[FoldersModel::COL_NAME]) && $newData[FoldersModel::COL_NAME] !== $data[FoldersModel::COL_NAME]) {
        self::validateName($newData, $result);
      }

      return $result;
    }


    /**
     * Valida el campo `name`:
     * - Debe existir
     * - Longitud entre `1` y `50` caracteres
     * - Se admite cualquier carácter
     * - Un `User` no puede tener dos `Folder` con el mismo nombre
     *
     * En `$result` se agrega la data validada o el mensaje de error correspondiente
     *
     * @param array<string, mixed> $data Data a validar
     * @param array{data: array, errors: array} $result *pasado por referencia
     */
    private static function validateName(array $data, array &$result): void {
      $name = isset($data[FoldersModel::COL_NAME]) ? trim($data[FoldersModel::COL_NAME]) : null;

      // Comprobar que existe el campo
      if ($name === null) {
        $result["errors"][] = "'" . FoldersModel::COL_NAME . "' es obligatorio";
        return;
      }

      // Comprobar formato
      if (!preg_match(self::NAME_REGEX, $name)
      ) {
        $result["errors"][] = "'" . FoldersModel::COL_NAME . "' debe tener una longitud entre 1 y 50 caracteres (ambos incluidos). Se admite cualquier carácter. Los espacios sobrantes al principio y final del '" . FoldersModel::COL_NAME . "' son eliminados";
        return;
      }

      // Comprobar si está en uso
      $checkFolder = FoldersModel::getFolderByUserIdAndName($data[FoldersModel::COL_OWNER_ID], $name);

      if ($checkFolder !== null) {
        $result["errors"][] = "'" . FoldersModel::COL_NAME . "' no disponible";
        return;
      }

      $result["data"]["name"] = $name;
    }
}
