function togglePassword(element) {
    let textfield = document.querySelector(element)
    textfield.type = textfield.type == 'password' ? 'text' : 'password'
}