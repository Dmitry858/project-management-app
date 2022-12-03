export default class Filters {

    constructor() {
        this.isInited = false;
    }

    init() {
        if (this.isInited) return;
        this.bindEvents();
        this.isInited = true;
    }

    bindEvents() {
        if (this.isInited) return;

        const applyBtn = document.querySelector('.filter .filter-apply-btn');

        if (!applyBtn) return;
        applyBtn.addEventListener('click', this.applyFilter.bind(this));
    }

    applyFilter(event) {
        event.preventDefault();

        let formData = new FormData(event.currentTarget.parentElement.parentElement),
            queryString = '';

        for (let arr of formData.entries()) {
            if (arr[0] === '_token' || arr[1] === '') continue;
            let firstSymbol = queryString === '' ? '?' : '&';
            queryString += firstSymbol + arr[0] + '=' + arr[1];
        }

        let href = window.location.origin + window.location.pathname;
        if (queryString !== '') {
            href += queryString;
        }
        window.location.href = href;
    }
}
