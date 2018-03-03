const dropdownTrigger = document.querySelector('.popup__trigger');
dropdownTrigger.addEventListener('click', (e) => {
    document.getElementById(dropdownTrigger.dataset.trigger).classList.toggle('active');
});