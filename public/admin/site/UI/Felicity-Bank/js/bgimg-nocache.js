$(document).ready(function callback() {
    const unsplash = "https://source.unsplash.com/user/erondu#";
    let rand = Math.random();

    console.log(unsplash + rand);

    var target = document.getElementsByTagName('BODY');
    target[0].style.setProperty('background-image', "url(" + unsplash + rand + ")");
})
