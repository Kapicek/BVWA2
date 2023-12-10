
function edit() {
    document.getElementById('user-form').submit();
}

function validateEmail(mail)
{
    return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail);
}

function validatePhoneNumber(input)
{
    let regex = /^\d{9}$/;
    return !!(input.match(regex));
}