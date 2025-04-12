<?php
/**
 * Enum que contiene códigos de respuesta HTTP
 * - `OK`
 * - `CREATED`
 * - `BAD_REQUEST`
 * - `UNAUTHORIZED`
 * - `FORBIDDEN`
 * - `NOT_FOUND`
 * - `METHOD_NOT_ALLOWED`
 * - `INTERNAL_SERVER_ERROR`
 * - `SERVICE_UNAVAILABLE`
 */
enum HttpCode: int {
  case OK = 200;
  case CREATED = 201;
  case BAD_REQUEST = 400;
  case UNAUTHORIZED = 401;
  case FORBIDDEN = 403;
  case NOT_FOUND = 404;
  case METHOD_NOT_ALLOWED = 405;
  case INTERNAL_SERVER_ERROR = 500;
  case SERVICE_UNAVAILABLE = 503;
}
