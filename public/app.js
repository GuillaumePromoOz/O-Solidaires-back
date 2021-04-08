
const init = function() {
    console.log(document.cookie);
    if(document.cookie !== ''){
        let body = document.querySelector('body');
        body.className = document.cookie ;
    } 
    divItem = document.querySelector('.container-theme');
    //console.log(divItem);
    divItem.addEventListener('click', handleBodyColorSwitch);
};

const handleBodyColorSwitch = function() {
    console.log('click !!!');

    let bodyElement = document.querySelector('.theme');

    const themes = ["background1", "background2", "background3",];

    const random = Math.floor(Math.random() * themes.length);

    const randomTheme = themes[random];

    // bodyElement.classList.add('theme--' + themes[random]);
    // bodyElement.classList.remove('theme--background0');
    displayTheme(randomTheme);
};

const displayTheme = function(theme){
    //console.log ("thème sélectionné : " + theme);
    let body = document.querySelector('body');
    console.log (body);
    body.className = theme;
    //location.reload();
    document.cookie = theme;
    console.log (document.cookie);
};

document.addEventListener('DOMContentLoaded', init);

