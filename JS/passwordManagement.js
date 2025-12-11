function togglePasswordVisibility(id) {
    var x = document.getElementById(id);
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

function passwordConfirmation(idForm, namePsw1, namePsw2) {
    const FORM = document.getElementById(idForm);
    
    FORM.addEventListener("submit", e => {comparePasswords(e, namePsw1, namePsw2, FORM)});
}

function comparePasswords(e, namePsw1, namePsw2, form){
    const FORM_DATA = new FormData(form);
    if (!(FORM_DATA.get(namePsw1) === FORM_DATA.get(namePsw2) && FORM_DATA.get(namePsw1) !== "")){
        e.preventDefault();
        alert("Le password non coincidono!");
    }
}