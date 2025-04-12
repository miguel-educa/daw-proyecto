<?php
require_once __DIR__ . "/../env.php";


/**
 * Contiene herramientas para el cifrado, descifrado, generación aleatoria...
*/
class Encrypt {
  /**
   * Hashea una contraseña maestra sin que se pueda recuperar la cadena original
   *
   * @param string $masterPassword Contraseña a hashear
   *
   * @return string Hash de la contraseña
   */
  public static function hashMasterPassword(string $masterPassword): string {
    return password_hash($masterPassword, PASSWORD_BCRYPT);
  }


  /**
   * Comprueba si una cadena corresponde a la contraseña hasheada
   *
   * @param string $masterPasswordCheck Contraseña a comprobar
   * @param string $masterPasswordHash Hash de la contraseña
   *
   * @return bool `true` si coinciden, `false` si no
   */
  public static function checkMasterPassword(string $masterPasswordCheck, string $masterPasswordHash): bool {
    return password_verify($masterPasswordCheck, $masterPasswordHash);
  }


  /**
   * Encripta una contraseña, pero pudiéndose desencriptar
   *
   * @param string $password Contraseña a encriptar
   *
   * @return string Contraseña cifrada en Base 64
   */
  public static function encryptPassword(string $password): string {
    $ivLength = openssl_cipher_iv_length(CIPHER_ALGORITHM);
    $iv = openssl_random_pseudo_bytes($ivLength);

    $encryptedPassword = openssl_encrypt(
      $password,
      CIPHER_ALGORITHM,
      ENCRYPTION_PASSPHRASE,
      OPENSSL_RAW_DATA,
      $iv
    );

    return base64_encode("$encryptedPassword::$iv");
  }


  /**
   * Desencripta una contraseña
   *
   * @param string $encryptedPassword Contraseña encriptada
   *
   * @return string|bool Contraseña original. `false` si se produce algún error
   */
  public static function decryptPassword(string $encryptedPassword): string|bool {
    [ $encryptedData, $iv ] = explode(
      "::",
      base64_decode($encryptedPassword),
      2
    );

    return openssl_decrypt(
      $encryptedData,
      CIPHER_ALGORITHM,
      ENCRYPTION_PASSPHRASE,
      OPENSSL_RAW_DATA,
      $iv
    );
  }


  /**
   * Genera un Código de Recuperación
   *
   * @return string
   */
  public static function generateRecuperationCode(): string {
    $bytes = random_bytes(16);

    return vsprintf(
      "%s-%s-%s-%s",
      str_split(
        bin2hex($bytes),
        5
      )
    );
  }


  /**
   * Genera un Session Token
   *
   * @return string
   */
  public static function generateSessionToken(): string {
    return bin2hex(random_bytes(32));
  }


  /**
   * Genera un UUID v4
   *
   * @return string
   */
  public static function generateUUIDv4(): string {
    $bytes = random_bytes(16);

    // Versión 4
    $bytes[6] = chr(
      ord($bytes[6]) & 0x0f | 0x40
    );
    $bytes[8] = chr(
      ord($bytes[8]) & 0x3f | 0x80
    );

    return vsprintf(
      "%s%s-%s-%s-%s-%s%s%s",
      str_split(
        bin2hex($bytes),
        4
      )
    );
  }
}
