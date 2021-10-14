$.validator.addMethod('shortUrlRegex', function (value) {
    return (value ?  /^[A-Za-z0-9-]+$/.test(value) : true);
}, 'Short Url can have letters, numbers and -');

$("#url_form").validate(
    {
        submitHandler: function() {
            const Url = window.location.href + "api/urls.json";
            let data = {
                longUrl: document.getElementById("url_longURL").value,
                shortUrl: document.getElementById("url_shortURL").value,
            };

            let headers = {
                "Content-type":"application/json; charset=utf-8",
                "Accept": "application/json"
            };

            $.ajax({
                url : Url,
                method: "POST",
                headers: headers,
                data : JSON.stringify(data),
                success: function(response) {
                    console.log(response);
                    $('#main-message').html('New Generated URL is:')
                    $('#link').val(response);
                    $('#answer').removeClass('invisible')

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
            return false;
        },
        rules: {
            'url[longURL]': {
                required: true,
                url: true
            },
            'url[shortURL]': {
                shortUrlRegex: true,
                required: false,
            }
        },
    }
);

function copyToClipboard() {
    let copyText = document.querySelector("#link");
    copyText.select();
    document.execCommand("copy");
}
