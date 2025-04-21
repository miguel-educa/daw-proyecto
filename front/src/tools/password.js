class PasswordTools {
  static checkMasterPassword(mPass) {
    const length = mPass.length
    const hasInvalidChars = /[^a-zA-Z0-9_\-,;!.@*&#%+$/]/.test(mPass)
    const hasLower = /[a-z]/.test(mPass)
    const hasUpper = /[A-Z]/.test(mPass)
    const hasNumber = /[0-9]/.test(mPass)
    const hasSpecial = /[_\-,;!.@*&#%+$/]/.test(mPass)

    return length >= 8 && length <= 50 && !hasInvalidChars && hasLower && hasUpper && hasNumber && hasSpecial
  }
}


export default PasswordTools
