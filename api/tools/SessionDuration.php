<?php
/**
 * Enum que contiene las distintas duraciones (en segundos) de una `Session`
 * - `ONE_HOUR`: `3600` segundos (1 hora)
 * - `ONE_DAY`: `86400` segundos (1 día)
 * - `SEVEN_DAYS`: `604800` segundos (7 días)
 * - `THIRTY_DAYS`: `2592000` segundos (30 días)
 * - `NINETY_DAYS`: `7776000` segundos (90 días)
 */
enum SessionDuration: int {
  case ONE_HOUR = 3600;
  case ONE_DAY = 86400;
  case SEVEN_DAYS = 604800;
  case THIRTY_DAYS = 2592000;
  case NINETY_DAYS = 7776000;
}
