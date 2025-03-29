<?php
require_once __DIR__ . "/../env.php";
require_once __DIR__ . "/../" . REQUEST_METHOD_PATH;
require_once __DIR__ . "/../" . RESPONSE_PATH;
require_once __DIR__ . "/../" . HTTP_CODE_PATH;


/**
 * Clase que permite obtener información de una petición HTTP
*/
class Request {
  /* Atributos */
    /** @var ?RequestMethod Método HTTP de la petición */
    private ?RequestMethod $method;

    /** @var array<RequestMethod> Array con los métodos HTTP permitidos en la solicitud */
    private array $allowedMethods;

    /** @var string *Body* de la solicitud */
    private string $body;

    /** @var string *User Agent* del dispositivo que realiza la solicitud */
    private string $userAgent;


  /* Constructor */
    /**
     * Instancia una petición HTTP
     *
     * @param array<RequestMethod> Array con los métodos HTTP permitidos en la solicitud. Default `[]`
     */
    public function __construct(array $allowedMethods = []) {
      $this->allowedMethods = $allowedMethods;
      $this->method = RequestMethod::tryFrom($_SERVER["REQUEST_METHOD"]);
      $this->body = file_get_contents("php://input");
      $this->userAgent = $_SERVER["HTTP_USER_AGENT"];
    }


  /* Métodos */
    /**
     * Retorna el método de solicitud HTTP
     *
     * @return ?RequestMethod
     */
    public function getMethod(): RequestMethod { return $this->method; }


    /**
     * Retorna el User Agent del dispositivo que ha realizado la solicitud
     *
     * @return string
     */
    public function getUserAgent(): string { return  substr($this->userAgent,0, 255); }


    /**
     * Retorna el valor de una Cookie mediante su nombre.
     * @param string $name
     *
     * @return ?string Valor de la Cookie o `null` si no existe
     */
    public function getCookie(string $name): ?string { return $_COOKIE[$name] ?? null; }


    /**
     * Retorna un array asociativo con los datos del cuerpo de la solicitud si tiene un formato JSON válido. Si no tiene un JSON válido, se detiene la ejecución y se muestra una Response con un error `400`, **deteniéndose** la ejecución
     *
     * @return array
     */
    public function getData(): array {
      $data = json_decode($this->body, true);

      if (is_null($data)) {
        $res = new Response();
        $res->addError(Response::ERROR_INVALID_JSON_BODY);
        $res->showResponseAndExit(HttpCode::BAD_REQUEST);
      }

      return $data;
    }


    /**
     * Comprueba si el método actual de la solicitud está permitido.
     * Si el método **no está permitido**, se muestra una Response con un error `405`, **deteniendo** la ejecución
     */
    public function checkRequestMethodAllowed(): void {
      if (!in_array($this->method, $this->allowedMethods)) {
        $res = new Response();
        $res->addError(Response::ERROR_METHOD_NOT_ALLOWED);
        $res->showResponseAndExit(HttpCode::METHOD_NOT_ALLOWED);
      }
    }
}
