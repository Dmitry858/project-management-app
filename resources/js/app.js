require('./bootstrap');

import Alpine from 'alpinejs';
import Comments from './Comments';
import Stages from './Stages';
import Invitations from './Invitations';
import Filters from './Filters';

window.Alpine = Alpine;

Alpine.start();

window.addEventListener('load', function (e) {
    // Toggle dropdown list
    const dropdownTrigger = document.getElementById('user-dropdown-trigger');
    if (dropdownTrigger) {
        dropdownTrigger.addEventListener('click', function (e) {
            document.getElementById('user-dropdown').classList.toggle('invisible');
        });
    }

    // Delete current logo
    const deleteLogoBtn = document.getElementById('delete-logo-btn');
    if (deleteLogoBtn) {
        deleteLogoBtn.addEventListener('click', function (e) {
            e.currentTarget.parentElement.remove();
        });
    }

    // Init Comments class
    let comments = new Comments();
    comments.init();

    // Init Stages class
    let stages = new Stages();
    stages.init();

    // Init Invitations class
    let invitations = new Invitations();
    invitations.init();

    // Init Filters class
    let filters = new Filters();
    filters.init();
});

// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.drop-button') && !event.target.matches('.drop-search')) {
        var dropdowns = document.getElementsByClassName("dropdownlist");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (!openDropdown.classList.contains('invisible')) {
                openDropdown.classList.add('invisible');
            }
        }
    }
}

