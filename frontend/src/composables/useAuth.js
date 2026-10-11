import { ref } from 'vue'
import authService from '@/api/auth'

// Estado de autenticación compartido sin Pinia (composable + módulo singleton).
const user = ref(authService.getUser())

async function login(email, password) {
  const loggedUser = await authService.login(email, password)
  user.value = loggedUser
  return loggedUser
}

async function logout() {
  await authService.logout()
  user.value = null
}

async function checkSession() {
  if (!authService.isAuthenticated()) {
    user.value = null
    return false
  }
  try {
    user.value = await authService.me()
    return true
  } catch {
    user.value = null
    return false
  }
}

export function useAuth() {
  return {
    user,
    isAuthenticated: () => !!user.value,
    login,
    logout,
    checkSession,
  }
}
