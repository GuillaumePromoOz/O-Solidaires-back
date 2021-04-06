navItem = document.querySelector('.container-dark');
//console.log(navItem);

const init = function() {

    navItem.addEventListener('click', handleBodyColorSwitch);
};

const handleBodyColorSwitch = function(event) {
    // récupération de du bouton validation (il a déclenché l'event)
    event.currentTarget.preventDefault;

    let bodyElement = document.querySelector('.theme');

    const themes = ["dark1", "dark2", "dark3",];

    const random = Math.floor(Math.random() * themes.length);

    bodyElement.classList.add('theme--' + themes[random]);
    bodyElement.classList.remove('theme--dark0');

};

document.addEventListener('DOMContentLoaded', init);

