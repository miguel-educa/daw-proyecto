// Endpoints API
const API_URL = import.meta.env.VITE_API_URL

export const api = {
  userEndpoint: `${API_URL}/user.php`,
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
      path: '/logut',
    },
  ],
  anonymousUser: [
    { label: 'Generador', path: '/pass-generator' },
    { label: 'Registrarse', path: '/register' },
    { label: 'Iniciar sesión', path: '/login' },
  ],
}

// Configuración global
const config = {
  api,
  navMenuOptions,
  passGenerator,
}

export default config
