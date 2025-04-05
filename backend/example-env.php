<?php
/* Variables de entorno */
const SERVICE_NAME = "Pass Warriors";


// Base de Datos
const DB_HOST = "<db_host>"; // Editar
const DB_USER = "<db_user>"; // Editar
const DB_PASSWORD = "<db_password>"; // Editar
const DB_NAME = "<db_name>"; // Editar


// Encriptación
const CIPHER_ALGORITHM = "AES-256-CTR";
const ENCRYPTION_PASSPHRASE = "<secure_encryption_key>"; // Editar


// Controllers
const CONTROLLERS_PATH = "controllers/";

const SESSION_CONTROLLER_PATH = CONTROLLERS_PATH . "Session.php";
const USER_CONTROLLER_PATH = CONTROLLERS_PATH . "User.php";
const USERS_CONTROLLER_PATH = CONTROLLERS_PATH . "Users.php";


// Models
const MODELS_PATH = "models/";

const SESSIONS_MODEL_PATH = MODELS_PATH . "Sessions.php";
const USERS_MODEL_PATH = MODELS_PATH . "Users.php";


// Schemas
const SCHEMAS_PATH = "schemes/";

const SESSION_SCHEMA_PATH = SCHEMAS_PATH . "Session.php";
const USER_SCHEMA_PATH = SCHEMAS_PATH . "User.php";


// Tools
const TOOLS_PATH = "tools/";

const DB_TOOLS_PATH = TOOLS_PATH . "DB.php";
const ENCRYPT_TOOLS_PATH = TOOLS_PATH . "Encrypt.php";
const HTTP_CODE_PATH = TOOLS_PATH . "HttpCode.php";
const REQUEST_METHOD_PATH = TOOLS_PATH . "RequestMethod.php";
const REQUEST_PATH = TOOLS_PATH . "Request.php";
const RESPONSE_PATH = TOOLS_PATH . "Response.php";
const SESSION_DURATION_PATH = TOOLS_PATH . "SessionDuration.php";
