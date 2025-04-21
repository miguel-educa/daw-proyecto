<?php
require_once __DIR__ . "/../" . "env.php";
require_once __DIR__ . "/../" . ACCOUNT_RECOVERY_CONTROLLER_PATH;
require_once __DIR__ . "/../" . ACCOUNT_RECOVERY_SCHEMA_PATH;
require_once __DIR__ . "/../" . ENCRYPT_TOOLS_PATH;
require_once __DIR__ . "/../" . SESSION_DURATION_PATH;
require_once __DIR__ . "/../" . SESSIONS_MODEL_PATH;
require_once __DIR__ . "/../" . USERS_MODEL_PATH;


/**
 * Controlador de la ruta `Session`
 */
class AccountRecoveryController {
  /**
   * Permite recuperar una cuenta mediante `username` y `recovery_code`.
   * Se genera un nuevo `recovery_code`, se cambia la `master_password` y se desactiva 2FA
   *
   * Si se crea correctamente se creará la Cookie de sesión
   *
   * @param Request $req
   * @param Response $res
   */
  public static function POST(Request $req, Response $res) {
    try {
      // Obtener datos
      $data = $req->getData();

      $result = AccountRecoverySchema::validate($data);

      if (count($result["errors"]) > 0) {
        $res->setErrors($result["errors"]);
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
      }

      // Obtener usuario
      $user = UsersModel::getUserByUsername($data[UsersModel::COL_USERNAME], confidentialData: true);

      if ($user === null || $result["data"][UsersModel::COL_REC_CODE] !== Encrypt::decrypt($user[UsersModel::COL_REC_CODE])) {
        $res->addError("El usuario no existe o el código de recuperación es incorrecto");
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Generar nuevo código de recuperación y actualizar datos
      $code = Encrypt::generateRecuperationCode();
      UsersModel::recoveryAccount($user["id"], $code, $result["data"]["master_password"]);

      // Crear sesión
      $tokenExpiresAt = time() + SessionDuration::ONE_HOUR->value;
      $newSession = SessionsModel::create(
        $user["id"],
        $req->getUserAgent(),
        $tokenExpiresAt
      );

      // Crear Cookie de sesión
      $res->setCookie(
        name: "session_token",
        value: $newSession["token"],
        expires: $tokenExpiresAt
      );

      $res->setData([ UsersModel::COL_REC_CODE => $code ]);
      $res->showResponseAndExit(HttpCode::OK);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }
}
