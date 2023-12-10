const values = new Map();

setTimeout(function() {
    var element = document.getElementById('usernameId');
    if (element) {
        element.style.color = 'black';
        element.innerHTML = 'Uživatelské jméno:';
        window.location.search = '';
    }
}, 2000)

function toggleEditMode() {
    let userCard = document.getElementById('user-card');
    let inputs = userCard.querySelectorAll('.form-control');
    let editButton = document.getElementById('editButton');
    let saveChangesButton = document.getElementById('saveChangesButton');
    let cancelButton = document.getElementById('cancelButton');

    userCard.removeAttribute('readonly');
    inputs.forEach(input => {
        input.removeAttribute('readonly');
        values.set(input.name, input.value);
    });
    editButton.style.display = 'none';
    saveChangesButton.style.display = 'block';
    cancelButton.style.display = 'block';
    console.log(values);
}

function saveChanges() {
    let mail = document.getElementById('emailInput').value;
    let phone = document.getElementById('phoneInput').value;

    if(!validateEmail(mail)) {
        alert("Email ma spatny format");
        return;
    }
    if(!validatePhoneNumber(phone)) {
        alert("Telefon ma spatny format");
        return;
    }

    document.getElementById('user-card').submit();
}

function cancelChanges() {
    let userCard = document.getElementById('user-card');
    let inputs = userCard.querySelectorAll('.form-control');
    let editButton = document.getElementById('editButton');
    let saveChangesButton = document.getElementById('saveChangesButton');
    let cancelButton = document.getElementById('cancelButton');

    inputs.forEach(input => {
        input.value = values.get(input.name);
        input.setAttribute('readonly', 'true');
    });
    userCard.setAttribute('readonly', 'true');
    editButton.style.display = 'block';
    saveChangesButton.style.display = 'none';
    cancelButton.style.display = 'none';
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