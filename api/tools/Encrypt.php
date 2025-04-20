<?php
require_once __DIR__ . "/../env.php";


/**
 * Contiene herramientas para el cifrado, descifrado, generación aleatoria...
*/
class Encrypt {
  /**
   * Hashea una contraseña maestra sin que se pueda recuperar la cadena original
   *
   * @param string $string Cadena a hashear
   *
   * @return string Hash de la cadena
   */
  public static function hash(string $string): string {
    return password_hash($string, PASSWORD_BCRYPT);
  }


  /**
   * Comprueba si una cadena corresponde a la cadena hasheada
   *
   * @param string $string Cadena a comprobar
   * @param string $hash Hash de la cadena
   *
   * @return bool `true` si coinciden, `false` si no
   */
  public static function checkHash(string $string, string $hash): bool {
    return password_verify($string, $hash);
  }


  /**
   * Encripta una cadena, pero pudiéndose desencriptar
   *
   * @param string $string Cadena a encriptar
   *
   * @return string Cadena cifrada en Base 64
   */
  public static function encrypt(string $string): string {
    $ivLength = openssl_cipher_iv_length(CIPHER_ALGORITHM);
    $iv = openssl_random_pseudo_bytes($ivLength);

    $encryptedString = openssl_encrypt(
      $string,
      CIPHER_ALGORITHM,
      ENCRYPTION_PASSPHRASE,
      OPENSSL_RAW_DATA,
      $iv
    );

    return base64_encode("$encryptedString::$iv");
  }


  /**
   * Desencripta una cadena
   *
   * @param string $encryptedString Cadena encriptada
   *
   * @return string|bool Cadena original. `false` si se produce algún error
   */
  public static function decrypt(string $encryptedString): string|bool {
    [ $encryptedData, $iv ] = explode(
      "::",
      base64_decode($encryptedString),
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
    $bytes = random_bytes(32);

    return vsprintf(
      "%s-%s-%s-%s-%s-%s-%s-%s",
      str_split(
        bin2hex($bytes),
        8
      )
    );
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


  /**
   * Hashea una cadena usando SHA-256
   *
   * @param string $string
   * @return string
   */
  public static function sha256(string $string): string {
    return hash("sha256", $string);
  }
}
