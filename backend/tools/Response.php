<?php
require_once __DIR__ . "/../" . "env.php";
require_once __DIR__ . "/../" . HTTP_CODE_PATH;


/**
 * Clase que permite crear una respuesta HTTP mateniendo un formato consistente
 */
class Response {
  /* Constantes */
    public const ERROR_FORBIDDEN = "No tiene permisos suficientes para acceder a este recurso";
    public const ERROR_INVALID_FILTER = "No se ha encontrado un parámetro de filtro válido";
    public const ERROR_INVALID_JSON_BODY = "El body de la petición no tiene un JSON válido";
    public const ERROR_METHOD_NOT_ALLOWED = "El método HTTP utilizado en esta ruta no está permitido";
    public const ERROR_NOT_FOUND = "No se encontraron resultados";
    public const ERROR_UNAUTHORIZED = "Necesita autenticarse para acceder a este recurso";


  /* Atributos */
    /** @var array Datos a devolver en la respuesta */
    private array $data;

    /** @var array Errores a devolver en la respuesta */
    private array $errors;


  /* Métodos */
    /**
     * Instancia una respuesta HTTP
     *
     * @param HttpCode $httpCode Código de respuesta HTTP. Default `HttpCode::OK`
     */
    public function __construct() {
      $this->data = [];
      $this->errors = [];
    }


  /* Getter/Setters */
    /**
     * Establece los datos de la respuesta
     *
     * @param array $data Array con los datos a devolver
     */
    public function setData(array $data): void { $this->data = $data; }


    /**
     * Añade un dato a la respuesta
     *
     * @param mixed $data Dato a añadir
     */
    public function addData(mixed $data): void { $this->data[] = $data; }


    /**
     * Establece los errores de la respuesta
     *
     * @param array $errors Array con los errores a devolver
     */
    public function setErrors(array $errors): void { $this->errors = $errors; }


    /**
     * Añade un error a la respuesta
     *
     * @param string $error Mensaje de error a añadir
     */
    public function addError(string $error): void { $this->errors[] = $error; }


    /**
     * Crea una Cookie con los datos proporcionados
     *
     * @param string $name Nombre de la Cookie
     * @param string $value Valor de la Cookie
     * @param int $expires Tiempo en segundos de duración de la cookie. Formato `time() + <tiempo_expiracion>`. `0` establece la duración a la sesión. Default `0`
     * @param bool $httponly `false` para permitir acceso a la cookie mediante JavaScript. Default `true`
     * @param bool $secure `false` para permitir crear la Cookie sin HTTPs. Default `true`
     *
     * @return bool `true` si se ha creado con éxito, `false` si no
     */
    public function setCookie(string $name, string $value = "", int $expires = 0, bool $httponly = true, $secure = true): bool {
      $options = [
        "expires" => $expires,
        "path" => "/",
        "secure" => $secure,
        "httponly" => $httponly,
        "samesite" => "None"
      ];

      return setcookie($name, $value, $options);
    }


    /**
     * Elimina una Cookie
     *
     * @param string $name Nombre de la Cookie
     *
     * @return bool `true` si se ha eliminado con éxito, `false` si no
     */
    public function deleteCookie(string $name): bool {
      return self::setCookie($name, "", time() - 1);
    }


    /**
     * Muestra la respuesta HTTP, estableciendo el código HTTP, los encabezados y el cuerpo de la respuesta. Detiene la ejecución del código
     *
     * - HTTP Code: `$httpCode`
     * - Headers:
     *   - `X-Powered-By:`
     *   - `Content-Type: application/json; charset=UTF-8`
     *   - `Access-Control-Allow-Origin: *`
     *   - `Cache-Control: no-store, no-cache, must-revalidate`
     * - Body en formato JSON:
     *   - `service_name`: `SERVICE_NAME` del archivo `env.php`
     *   - `success`: `true` i no hay errores o `false` si hay algún error
     *   - `data`: Objeto o array con los datos (objetos). `null` si hay algún error almacenado
     *   - `errors`: Array con los mensajes de error. `null` si no hay ningún error almacenado
     *
     * @param HttpCode $httpCode Código HTTP de la respuesta
     */
    public function showResponseAndExit(HttpCode $httpCode): void {
      http_response_code($httpCode->value);

      header("X-Powered-By:");
      header("Content-Type: application/json; charset=UTF-8");
      header('Access-Control-Allow-Credentials: true');
      header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
      header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, DELETE");
      header("Access-Control-Allow-Origin: " . ($_SERVER["HTTP_ORIGIN"] ?? "*"));
      header("Cache-Control: no-store, no-cache, must-revalidate");
      header("X-Content-Type-Options: nosniff");
      header("Content-Security-Policy: default-src 'none';");
      header("X-Frame-Options: DENY");

      $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

      echo json_encode(
        [
          "service_name" => SERVICE_NAME,
          "success" => count($this->errors) === 0,
          "data" => count($this->errors) === 0 ? $this->data : null,
          "errors" => count($this->errors) > 0 ? $this->errors : null
        ],
        $flags
      );
      exit;
    }
}
