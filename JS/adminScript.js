for (const container of document.getElementsByClassName("sectionContainer")) {
    container.addEventListener("click", e => {showSelection(container.parentNode)});
}


for (const textArea of document.getElementsByClassName('pageTextInput')){
    textArea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
}

function showSelection(element){
    const ARROW_ELEMENT = element.querySelector(".arrow");
    ARROW_ELEMENT.classList.toggle("down");
    ARROW_ELEMENT.classList.toggle("right");
    element.querySelector(".modificationContainer").classList.toggle("showModifications") //toggle aggiunge la classe se non c'è ma la elimina se c'è già
}

function setupLangInputValue(pageKey, lang, element){
    document.getElementById(pageKey + "EditLang").value = lang;
    element.form.submit();
}