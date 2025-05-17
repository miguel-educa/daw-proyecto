<?php
/**
 * Enum que contiene las distintas duraciones (en segundos) de una `Session`
 * - `FIFTEEN_MINUTES`: `900` segundos (15 minutos)
 * - `THIRTY_MINUTES`: `1800` segundos (30 minutos)
 * - `ONE_HOUR`: `3600` segundos (1 hora)
 * - `FOUR_HOURS`: `14400` segundos (4 horas)
 * - `EIGHT_HOURS`: `28800` segundos (8 horas)
 * - `ONE_DAY`: `86400` segundos (1 día)
 * - `SEVEN_DAYS`: `604800` segundos (7 días)
 * - `THIRTY_DAYS`: `2592000` segundos (30 días)
 * - `NINETY_DAYS`: `7776000` segundos (90 días)
 */
enum SessionDuration: int {
  case FIFTEEN_MINUTES = 900;
  case THIRTY_MINUTES = 1800;
  case ONE_HOUR = 3600;
  case FOUR_HOURS = 14400;
  case EIGHT_HOURS = 28800;
  case ONE_DAY = 86400;
  case SEVEN_DAYS = 604800;
  case THIRTY_DAYS = 2592000;
  case NINETY_DAYS = 7776000;
}
