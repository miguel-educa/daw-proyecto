[Regresar](./README.md)

**Contenidos**

- [1. Métodos de encriptación](#1-métodos-de-encriptación)
    - [1.1. Master Password](#11-master-password)
    - [1.2. Passwords](#12-passwords)


# 1. Métodos de encriptación
Se han utilizado distintos **métodos** de **encriptación** dependiendo de la **finalidad** de la contraseña. En ***Pass Warriors***, existen dos tipos de contraseñas


## 1.1. Master Password
Es la **contraseña principal** que permite al usuario **acceder** a la **aplicación**. Se debe almacenar mediante un algoritmo que **no se pueda descifrar**, es decir, que de la contraseña cifrada no se pueda obtener la contraseña original. Para ello, PHP ofrece una función nativa (`password_hash()`) que cifra la contraseña mediante un algoritmo de cifrado específico. Es **irreversible** y genera **distintas salidas** para una misma entrada, fundamental para proporcionar privacidad a las contraseñas. En este caso, se ha utilizado el algoritmo `PASSWORD_BCRYPT`. Es un algoritmo de cifrado de **alta velocidad** y de **alto** nivel de **seguridad**. En la base de datos, se **almacena** la **contraseña cifrada**, la contraseña orignal nunca debe ser almacenada

Cómo no se puede descifrar la contraseña cifrada, para **comprobar** que una contraseña coincida con la *Master Password* cifrada o no, se utiliza la función `password_verify(<password_comprobar>, <master_password_cifrada>)`, retornando `true` si la contraseña es correcta y `false` en caso contrario


## 1.2. Passwords
Son las **contraseñas** que el usuario **almacena** para **poder utilizar** cuando desee, por tanto, no se puede utilizar el método anterior ya que se necesita desencriptar la contraseña. Para ello, se ha decidido utilizar `openssl_encrypt` y `openssl_decrypt`. Estas funciones permiten **cifrar** y **descifrar** las contraseñas de manera segura

Como algoritmo de cifrado, se ha decidido utilizar `AES-256-CTR`. Es un algoritmo de **cifrado simétrico** y utiliza una clave de 256 bits. Utiliza el modo CTR, tomando un vector de inicialización (IV) de 128 bits

Para un **cifrado seguro**, es requerido una `Passphrase`. Esta cadena debe ser **larga** y **aleatoria** y **no** puede ser **pública**. Permite derivar la clave de cifrado y el IV de manera segura


---

[Regresar](./README.md)
