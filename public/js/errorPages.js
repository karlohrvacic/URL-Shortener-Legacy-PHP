
fetch('https://uselessfacts.jsph.pl/random.json?language=en')
    .then((data)=>{
    return data.json();
}).then((data)=>{
    document.getElementById('random-fact').innerText = data.text;
});

let duration = 4
let timer = setInterval(() => {
    if(duration <= 0){
        window.location.href = "/";
        clearInterval(timer)
    }
    document.getElementById("number").innerHTML = "Redirecting to homepage in " + duration;
    duration--;
    }, 1000);
