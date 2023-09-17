function myFunction(element) {
    var x = element.parentElement.getElementsByTagName('input')[0];
    console.log('INPUT', x)
    if (x.type === "password") {
        element.classList.remove("uil-eye")
        element.classList.add("uil-eye-slash")
        x.type = "text";
    } else {
        element.classList.remove("uil-eye-slash")
        element.classList.add("uil-eye")
        x.type = "password";
    }
}