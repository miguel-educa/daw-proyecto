// Endpoints API
const API_URL = import.meta.env.VITE_API_URL

export const api = {
  foldersEndpoint: `${API_URL}/folders.php`,
  paswordsEndpoint: `${API_URL}/passwords.php`,
  accountRecoveryEndpoint: `${API_URL}/account-recovery.php`,
  sessionEndpoint: `${API_URL}/session.php`,
  userEndpoint: `${API_URL}/user.php`,
  usersEndpoint: `${API_URL}/users.php`,
}

// Generador de contraseñas
export const passGenerator = {
  config: {
    passwordLength: 8,
    lowerCharCount: 1,
    upperCharCount: 1,
    numberCharCount: 1,
    specialCharCount: 1,
  },
  limits: {
    minPasswordLength: 5,
    maxPasswordLength: 50,
    maxNumberMinimumChars: 10,
  },
  availableChars: {
    lower: 'abcdefghijklmnopqrstuvwxyz'.split(''),
    upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split(''),
    number: '0123456789'.split(''),
    special: '_-,;!.@*&#%+$/'.split(''),
  },
  history: {
    maxHistory: 50,
  },
}

// Menú de navegación
export const navMenuOptions = {
  userLogin: [
    {
      label: 'Baúl',
      children: [
        { label: 'Personal', path: '/vault' },
        { label: 'Compartido', path: '/shared-vault' },
      ],
    },
    { label: 'Generador', path: '/pass-generator' },
    { label: 'Auditoría', path: '/pass-audit' },
    {
      label: 'Ajustes de cuenta',
      path: '/settings',
    },
    {
      label: 'Cerrar sesión',
      path: '/logout',
    },
  ],
  anonymousUser: [
    { label: 'Generador', path: '/pass-generator' },
    { label: 'Registrarse', path: '/register' },
    { label: 'Iniciar sesión', path: '/login' },
  ],
}

export const sessionDuration = [
  {
    label: '1 hora',
    value: 3600,
  },
  {
    label: '1 día',
    value: 86400,
  },
  {
    label: '1 semana',
    value: 604800,
  },
  {
    label: '1 mes',
    value: 2592000,
  },
  {
    label: '3 meses',
    value: 7776000,
  },
]

// Configuración global
const config = {
  api,
  navMenuOptions,
  passGenerator,
  sessionDuration,
}

export default config
