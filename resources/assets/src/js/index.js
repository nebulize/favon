const dropdownTrigger = document.querySelector('.popup__trigger');
dropdownTrigger.addEventListener('click', (e) => {
    dropdownTrigger.classList.toggle('is-active');
    document.getElementById(dropdownTrigger.dataset.trigger).classList.toggle('is-active');
});

document.addEventListener('click', (e) => {
    if (e.target.closest('.popup, .popup__trigger') === null) {
        document.querySelectorAll('.popup.is-active').forEach(popup => {
            popup.classList.remove('is-active');
        });
        document.querySelectorAll('.popup__trigger.is-active').forEach(popup => {
            popup.classList.remove('is-active');
        });
    }
});

