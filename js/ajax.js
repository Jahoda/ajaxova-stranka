var Stranka = function () {
    var ajax = function (url) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                prejit(eval('(' + xhr.responseText + ')'), url);
            }
        }
        xhr.open("GET", url + "?js");
        xhr.send("");
    };

    var nastavitStranku = function (data) {
        document.title = data.titulek;
        document.getElementById("obsah").innerHTML = data.obsah;
    };

    var prejit = function (data, url) {
        nastavitStranku(data);
        window.history.pushState(data, data.titulek, url);
        pripojitOdkazy(document.getElementById("obsah"));
    }

    var pripojitOdkazy = function (context) {
        if (!document.querySelector || !history.pushState) {
            return;
        }

        history.replaceState({
            "titulek": document.title,
            "obsah": document.getElementById("obsah").innerHTML
        }, document.title, window.location);

        var interniOdkazy = context.querySelectorAll("a.interni");

        for (var i = 0; i < interniOdkazy.length; i++) {
            interniOdkazy[i].onclick = function () {
                ajax(this.href);
                return false;
            };
        }
        ;
    };

    window.onpopstate = function (event) {
        if (event.state) {
            nastavitStranku(event.state);
        }
    };

    return {
        pripojitOdkazy: pripojitOdkazy
    };
}();
