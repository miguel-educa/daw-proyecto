<?php
require_once __DIR__ . "/../" . "env.php";
require_once __DIR__ . "/../" . FOLDERS_MODEL_PATH;
require_once __DIR__ . "/../" . FOLDER_SCHEMA_PATH;
require_once __DIR__ . "/../" . SESSIONS_MODEL_PATH;


/**
 * Controlador de la ruta `Folders`
 */
class FoldersController {
  /**
   * Recuperar las `Folder` del usuario autenticado
   *
   * @param Request $req
   * @param Response $res
   */
  public static function GET(Request $req, Response $res) {
    try {
      // Obtener usuario mediante sesión
      $session = SessionsModel::getSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

      if ($session === null) {
        $res->addError(Response::ERROR_UNAUTHORIZED);
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Obtener data
      $user = FoldersModel::getFoldersByUserId($session["user_id"]);

      // Comprobar que se hayan encontrado resultados
      if ($user === null) {
        $res->addError(Response::ERROR_NOT_FOUND);
        $res->showResponseAndExit(HttpCode::NOT_FOUND);
      }

      $res->setData($user);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }


  /**
   * Crea un `Folder`. Si el cuerpo de la petición no contiene los parámetros
   * requeridos con el formato correcto, se mostrará un error
   *
   * Si se crea correctamente, se devolverá el objeto creado
   *
   * @param Request $req
   * @param Response $res
   */
  public static function POST(Request $req, Response $res) {
    try {
      // Obtener usuario mediante sesión
      $session = SessionsModel::getSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

      if ($session === null) {
        $res->addError(Response::ERROR_UNAUTHORIZED);
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Obtener datos
      $data = $req->getData();
      $data["owner_user_id"] = $session["user_id"];

      $result = FolderSchema::validate($data);

      if (count($result["errors"]) > 0) {
        $res->setErrors($result["errors"]);
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
      }

      // Crear
      $newFolder = FoldersModel::create($result["data"]);

      $res->setData($newFolder);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }


  /**
   * Modifica un `Folder`. Si el cuerpo de la petición no contiene los parámetros
   * requeridos con el formato correcto, se mostrará un error
   *
   * Si se modifica correctamente, se devolverá el objeto modificado
   *
   * @param Request $req
   * @param Response $res
   */
  public static function PATCH(Request $req, Response $res) {
    try {
      // Obtener usuario mediante sesión
      $session = SessionsModel::getSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

      if ($session === null) {
        $res->addError(Response::ERROR_UNAUTHORIZED);
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Obtener datos
      $data = $req->getData();
      $data[FoldersModel::COL_OWNER_ID] = $session["user_id"];

      // Obtener folder a actualizar
      $folder = FoldersModel::getFolderByUserIdAndId($session["user_id"], $data[FoldersModel::COL_ID] ?? "");

      if ($folder === null) {
        $res->addError(Response::ERROR_NOT_FOUND);
        $res->showResponseAndExit(HttpCode::NOT_FOUND);
      }

      // Validar datos
      $result = FolderSchema::partialValidate($data, $folder);

      if (count($result["errors"]) > 0) {
        $res->setErrors($result["errors"]);
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
      }

      // Actualizar folder
      $updatedFolder = FoldersModel::update($result["data"], $data[FoldersModel::COL_ID], $session["user_id"]);

      $res->setData($updatedFolder);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }


  /**
   * Elimina un `Folder`
   *
   * @param Request $req
   * @param Response $res
   */
  public static function DELETE(Request $req, Response $res) {
    try {
      // Obtener usuario mediante sesión
      $session = SessionsModel::getSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

      if ($session === null) {
        $res->addError(Response::ERROR_UNAUTHORIZED);
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Obtener datos
      $data = $req->getData();
      $data[FoldersModel::COL_OWNER_ID] = $session["user_id"];

      // Obtener folder a eliminar
      $folder = FoldersModel::getFolderByUserIdAndId($session["user_id"], $data[FoldersModel::COL_ID] ?? "");

      if ($folder === null) {
        $res->addError(Response::ERROR_NOT_FOUND);
        $res->showResponseAndExit(HttpCode::NOT_FOUND);
      }

      // Eliminar folder
      FoldersModel::delete($data[FoldersModel::COL_ID]);

      $res->setData([ "folder_deleted" => true ]);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }
}
