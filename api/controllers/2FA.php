<?php
require_once __DIR__ . "/../" . "env.php";
require_once __DIR__ . "/../" . USERS_MODEL_PATH;
require_once __DIR__ . "/../" . SESSIONS_MODEL_PATH;
require_once __DIR__ . "/../" . GOOGLE_AUTHENTICATOR_PATH;


/**
 * Controlador de la ruta `2fa`
 */
class twoFAController {
  /**
   * Crea el 2FA de un `User`
   * - Primera petición: Genración del secret
   * - Segunda petición: Verificación del código
   *
   * @param Request $req
   * @param Response $res
   */
  public static function CREATE(Request $req, Response $res) {
    try {
      $data = $req->getData();

      // Obtener usuario mediante sesión
      $session = SessionsModel::getActiveSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

      if ($session === null) {
        $res->addError(Response::ERROR_UNAUTHORIZED);
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Obtener usuario
      $user = UsersModel::getUserById($session["user_id"], true);

      // Comprobar que se hayan encontrado resultados
      if ($user === null) {
        $res->addError(Response::ERROR_NOT_FOUND);
        $res->showResponseAndExit(HttpCode::NOT_FOUND);
      }

      // Comprobar si ya ha sido habilitado
      if ($user[UsersModel::COL_TOTP_2FA_SECRET] !== null) {
        $res->addError("Para configurar un nuevo doble factor de autenticación mediante código temporal, debe eliminar el actual");
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
      }

      // Obtener data
      $secret = $data["secret"] ?? null;
      $code = $data["code"] ?? null;

      $ga = new PHPGangsta_GoogleAuthenticator();

      // Generar secreto y URL del código QR
      if ($secret === null && $code === null) {
        $secret = $ga->createSecret(32);
        $qrUrl = $ga->getQRCodeGoogleUrl('Pass Warriors', $secret);

        $res->setData([ "secret" => $secret, "qr_code_url" => $qrUrl ]);
        $res->showResponseAndExit(HttpCode::OK);
      }

      // Verificar código
      if ($ga->verifyCode($secret, $code) === false) {
        $res->addError("El código de la autenticación de doble factor es incorrecto");
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Crear 2FA
      UsersModel::create2FA($user["id"], $secret);

      $res->setData([ "two_fa_created" => true ]);
      $res->showResponseAndExit(HttpCode::CREATED);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }


  /**
   * Elimina el 2FA de un `User`
   *
   * @param Request $req
   * @param Response $res
   */
  public static function DELETE(Request $req, Response $res) {
    try {
      // Obtener usuario mediante sesión
      $session = SessionsModel::getActiveSessionByTokenAndUserAgent($req->getCookie("session_token"), $req->getUserAgent());

      if ($session === null) {
        $res->addError(Response::ERROR_UNAUTHORIZED);
        $res->showResponseAndExit(HttpCode::UNAUTHORIZED);
      }

      // Obtener usuario
      $user = UsersModel::getUserById($session["user_id"], true);

      // Comprobar que se hayan encontrado resultados
      if ($user === null) {
        $res->addError(Response::ERROR_NOT_FOUND);
        $res->showResponseAndExit(HttpCode::NOT_FOUND);
      }

      // Comprobar que exista 2fa
      if ($user[UsersModel::COL_TOTP_2FA_SECRET] === null) {
        $res->addError("No hay ninguna autenticación de doble factor configurada");
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
      }

      UsersModel::delete2FA($user["id"]);

      $res->setData([ "two_fa_deleted" => true ]);
      $res->showResponseAndExit(HttpCode::CREATED);
    } catch (\Exception $e) {
      $res->addError($e->getMessage());
      $res->showResponseAndExit(HttpCode::INTERNAL_SERVER_ERROR);
    }
  }
}
