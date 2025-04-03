<?php
/**
 * Enum que contiene métodos HTTP
 * - `DELETE`: Eliminar
 * - `GET`: Obtener
 * - `OPTIONS`: Consulta
 * - `PATCH`: Modificación parcial
 * - `POST`: Crear
 * - `PUT`: Modificación completa o crear
 */
enum RequestMethod: string {
  case DELETE = "DELETE";
  case GET = "GET";
  case OPTIONS = "OPTIONS";
  case PATCH = "PATCH";
  case POST = "POST";
  case PUT = "PUT";
}
