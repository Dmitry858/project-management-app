import Comments from './Comments';
import Stages from './Stages';
import Invitations from './Invitations';
import Filters from './Filters';
import DeleteItemsGroupHandler from './DeleteItemsGroupHandler';

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

    // Delete user photo
    const deletePhotoBtn = document.getElementById('delete-photo-btn');
    if (deletePhotoBtn) {
        deletePhotoBtn.addEventListener('click', function (e) {
            e.currentTarget.parentElement.remove();
        });
    }

    // Delete current attachments
    const deleteFileBtns = document.getElementsByClassName('delete-file-btn'),
          deletedFilesInput = document.getElementById('deleted_attachments');

    if (deleteFileBtns.length > 0 && deletedFilesInput) {
        for (let btn of deleteFileBtns) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                let value = deletedFilesInput.value,
                    fileId = e.currentTarget.dataset.id;

                if (value === '') {
                    value = fileId;
                } else {
                    value += ', ' + fileId;
                }
                deletedFilesInput.value = value;
                e.currentTarget.parentElement.remove();
            });
        }
    }

    // Toggle isAllday select
    const isAlldaySelect = document.getElementById('is_allday');
    if (isAlldaySelect) {
        isAlldaySelect.addEventListener('change', function (e) {
            const dateInputs = document.getElementsByClassName('date-input');
            if (dateInputs.length === 0) return;

            let type = e.currentTarget.value === '1' ? 'date' : 'datetime-local';
            for (let input of dateInputs) {
                input.setAttribute('type', type);
            }
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

    // Init DeleteItemsGroupHandler class
    let handler = new DeleteItemsGroupHandler();
    handler.init();

    // Tooltip handler
    const tooltipsWrap = document.getElementsByClassName('has-tooltip'),
          tooltips = document.getElementsByClassName('tooltip');
    if (tooltipsWrap.length > 0 && tooltips.length > 0 && window.innerWidth < 768) {
        for (let item of tooltipsWrap) {
            item.addEventListener('click', () => {
                for (let tooltip of tooltips) {
                    tooltip.classList.remove('show');
                }
                let tooltipChild = item.querySelector('.tooltip');
                if (tooltipChild && !tooltipChild.classList.contains('show')) {
                    tooltipChild.classList.add('show');
                }
            });
        }

        for (let tooltip of tooltips) {
            tooltip.addEventListener('click', (event) => {
                event.stopPropagation();
                if (event.currentTarget.classList.contains('show')) {
                    event.currentTarget.classList.remove('show');
                }
            });
        }
    }
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

