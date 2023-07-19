export default class Filters {

    constructor() {
        this.isInited = false;
    }

    init() {
        if (this.isInited) return;
        this.bindEvents();
        this.correctPaginationLinks();
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

    correctPaginationLinks() {
        const links = document.querySelectorAll('.pagination a');
        let query = window.location.search.substring(1);
        if (links.length === 0 || query === '') return;
        if (query.indexOf('page') >= 0) {
            let arQueryParams = query.split('&');

            arQueryParams.forEach(function(item, index, object) {
                if (item.indexOf('page') >= 0) {
                    object.splice(index, 1);
                }
            });
            query = arQueryParams.join('&');
        }
        if (query === '') return;

        for (let link of links) {
            link.href += '&' + query;
        }
    }
}
