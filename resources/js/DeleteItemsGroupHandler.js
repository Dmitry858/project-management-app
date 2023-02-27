export default class DeleteItemsGroupHandler {

    constructor() {
        this.csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.isInited = false;
        this.mainMark = null;
        this.marks = [];
        this.deleteItemsLink = null;
    }

    init() {
        if (this.isInited) return;
        this.mainMark = document.querySelector('input[name="mark-deleted-all"]');
        this.marks = document.querySelectorAll('input[name="mark-deleted"]');
        this.deleteItemsLink = document.getElementById('delete-items-link');
        this.bindEvents();
        this.inactivateAllCheckboxes();
        this.isInited = true;
    }

    bindEvents() {
        if (this.isInited || this.marks.length === 0) return;

        if (this.mainMark) {
            this.mainMark.addEventListener('change', this.markAllItems.bind(this));
        }

        if (this.deleteItemsLink) {
            this.deleteItemsLink.addEventListener('click', this.deleteItems.bind(this));
        }

        for (let mark of this.marks) {
            mark.addEventListener('click', this.controlLinkVisibility.bind(this));
        }
    }

    markAllItems(event) {
        for (let mark of this.marks) {
            mark.checked = event.currentTarget.checked;
        }

        this.controlLinkVisibility();
    }

    deleteItems(event) {
        event.preventDefault();
        let confirmed = confirm('Подтвердите удаление');

        if (!confirmed || !this.csrf) return;

        let entity = this.getEntity(),
            data = {
                'ids': this.getItemsIds()
            };

        if (!entity || data.ids.length === 0) return;

        fetch('/' + entity + '/delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': this.csrf,
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(result => {
                window.location.reload();
            })
            .catch((e) => {
                console.log(e.message);
            });
    }

    inactivateAllCheckboxes() {
        if (this.mainMark && this.mainMark.checked) {
            this.mainMark.checked = false;
        }
        if (this.marks.length > 0) {
            for (let mark of this.marks) {
                if (mark.checked) mark.checked = false;
            }
        }
    }

    controlLinkVisibility() {
        if (this.marks.length === 0 || !this.deleteItemsLink) return;

        let isChecked = false;
        for (let mark of this.marks) {
            if (mark.checked) isChecked = true;
        }

        if (isChecked && this.deleteItemsLink.classList.contains('hidden')) {
            this.deleteItemsLink.classList.remove('hidden');
        } else if (!isChecked && !this.deleteItemsLink.classList.contains('hidden')) {
            this.deleteItemsLink.classList.add('hidden');
        }
    }

    getEntity() {
        let tr = document.querySelector('tbody tr');
        return tr.dataset.entity;
    }

    getItemsIds() {
        let ids = [];

        for (let mark of this.marks) {
            if (mark.checked) {
                ids.push(Number(mark.parentElement.parentElement.parentElement.dataset.id));
            }
        }

        return ids;
    }
}
