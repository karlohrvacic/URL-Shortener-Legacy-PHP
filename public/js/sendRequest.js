$("#url_form").submit( function(form){
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
});