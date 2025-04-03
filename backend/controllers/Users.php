<?php
require_once __DIR__ . "/../" . "env.php";
require_once __DIR__ . "/../" . USERS_MODEL_PATH;
require_once __DIR__ . "/../" . USER_SCHEMA_PATH;
require_once __DIR__ . "/../" . SESSIONS_MODEL_PATH;


/**
 * Controlador de la ruta `Users`
 */
class UsersController {
  /**
   * Recuperar `User`
   * Es obligatorio uno de los siguientes `query params`:
   * - `username`: Retorna un `User` que coincida
   * - `name`: Retorna varios `User` que contengan el valor del filtro
   *
   * @param Request $req
   * @param Response $res
   */
  public static function GET(Request $req, Response $res) {
    try {
      // Comprobar filtros
      $allowedFilters = [
        UsersModel::COL_USERNAME,
        UsersModel::COL_NAME
      ];

      $filters = $req::getQueryParams($allowedFilters);

      if (count($filters) === 0) {
        $res->addError(Response::ERROR_INVALID_FILTER);
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
      }

      // Obtener data
      $filter = $filters[0];
      $value = $req->getQueryParamValue($filter);

      $data = match ($filter) {
        UsersModel::COL_USERNAME => UsersModel::getUserByUsername($value),
        UsersModel::COL_NAME => UsersModel::getUsersByName($value)
      };

      // Comprobar que se hayan encontrado resultados
      if ($data === null || count($data) === 0) {
        $res->addError(Response::ERROR_NOT_FOUND);
        $res->showResponseAndExit(HttpCode::NOT_FOUND);
      }

      $res->setData($data);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }


  /**
   * Crea un `User`. Si el cuerpo de la petición no contiene los parámetros
   * requeridos con el formato correcto, se mostrará un error
   *
   * Si se crea correctamente, se devolverá el objeto creado y se creará la Cookie de sesión
   *
   * @param Request $req
   * @param Response $res
   */
  public static function POST(Request $req, Response $res) {
    try {
      // Obtener datos
      $data = $req->getData();

      $result = UserSchema::validate($data);

      if (count($result["errors"]) > 0) {
        $res->setErrors($result["errors"]);
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
      }

      // Crear usuario y sesión
      $tokenExpiresAt = time() + 60 * 60;
      $newUser = UsersModel::create($result["data"]);
      $newSession = SessionsModel::create(
        $newUser["id"],
        $req->getUserAgent(),
        $tokenExpiresAt
      );

      // Crear Cookie de sesión
      $res->setCookie(
        name: "session_token",
        value: $newSession["token"],
        expires: $tokenExpiresAt,
        httponly: true,
        secure: true
      );
      $res->setData($newUser);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }
}
