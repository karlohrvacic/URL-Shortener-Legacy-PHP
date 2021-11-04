let regExUrl = 'https?:\\/\\/(www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b([-a-zA-Z0-9()@:%_\\+.~#?&//=]*)';
let regExShortUrl = '/^[A-Za-z0-9-]+$/';

function isFormValid(){
    let fullUrl = document.getElementById('url_longURL');
    let shortUrl = document.getElementById('url_shortURL');
    let isValid = true;
    if (!new RegExp(regExUrl, 'g').test(fullUrl.value)){
        fullUrl.classList.add('error');
        isValid = false;
    }
    else{
        fullUrl.classList.remove('error');
    }
    if (!new RegExp(regExShortUrl, 'g').test(shortUrl.value) && shortUrl.value.length !== 0){
        shortUrl.classList.add('error');
        isValid = false;
    }
    else{
        shortUrl.classList.remove('error');
    }
    return isValid;
}

document.getElementById('url_form').addEventListener('submit', (event) => {
    event.preventDefault();
    if (!isFormValid()){
        return;
    }
    const data = {
        longUrl: document.getElementById('url_longURL').value,
        shortUrl: document.getElementById('url_shortURL').value
    };

    fetch('/api/urls.json', {
        method: 'POST',
        headers: {
            'Content-type':'application/json; charset=utf-8',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data),
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('main-message').innerHTML = 'New Generated URL is:';
            document.getElementById('link').value = data;
            document.getElementById('answer').classList.remove('invisible');
        })
        .catch((error) => {
            console.error('Error has occurred:', error);
        });

});

document.getElementById('url_longURL').addEventListener("keyup", isFormValid);
document.getElementById('url_shortURL').addEventListener("keyup", isFormValid);

function copyToClipboard() {
    let copyText = document.querySelector('#link');
    copyText.select();
    document.execCommand('copy');
}
