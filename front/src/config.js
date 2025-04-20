// Endpoints API
const API_URL = import.meta.env.VITE_API_URL

export const api = {
  userEndpoint: `${API_URL}/user.php`,
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
      label: 'Ajustes',
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
}

export default config
