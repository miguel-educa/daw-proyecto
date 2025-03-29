<?php
require_once __DIR__ . "/../" . "env.php";
require_once __DIR__ . "/../" . USERS_MODEL_PATH;


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
}
