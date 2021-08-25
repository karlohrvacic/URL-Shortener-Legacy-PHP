window.onload = function () {
    document.getElementById("url_form").onsubmit = function onSubmit(form) {
        const Url = window.location.href + "api/urls.json";
        console.log(Url);

        let data = {
            longUrl: document.getElementById("url_longURL").value,
            shortUrl: document.getElementById("url_shortURL").value,
        };

        let headers = {
            "Content-type":"application/json; charset=UTF-8",
            "Accept": "application/json"
        };
        $.ajax({
            url : Url,
            type: "POST",
            headers: headers,
            data : data,
            async : false,
            success: function(response, textStatus, jqXHR) {
                console.log(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
        form.preventDefault();
    }
}