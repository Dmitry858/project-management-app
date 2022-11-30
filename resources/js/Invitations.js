export default class Invitations {

    constructor() {
        this.csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.success = document.getElementById('toast-success');
        this.error = document.getElementById('toast-error');
        this.isInited = false;
    }

    init() {
        if (this.isInited) return;
        this.bindEvents();
        this.isInited = true;
    }

    bindEvents() {
        if (this.isInited) return;

        const sendBtns = document.getElementsByClassName('send-invitation-btn'),
              closeBtns = document.getElementsByClassName('close-button');

        if (sendBtns.length > 0) {
            for (let btn of sendBtns) {
                btn.addEventListener('click', this.sendInvitation.bind(this));
            }
        }

        if (closeBtns.length > 0) {
            for (let btn of closeBtns) {
                btn.addEventListener('click', this.closeToast.bind(this));
            }
        }
    }

    sendInvitation(event) {
        event.preventDefault();

        if (!this.csrf) return;

        let linkTag = event.currentTarget;
        let url = linkTag.href;
        if (!url) return;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': this.csrf,
                'Content-Type': 'application/json;charset=utf-8'
            },
        })
            .then(response => response.json())
            .then(result => {
                if (result.status && result.status === 'error' && this.error) {
                    this.showToast(this.error, result.text);
                }

                if (result.status && result.status === 'success' && this.success) {
                    this.showToast(this.success, result.text);
                    linkTag.classList.add('disabled');
                    let id = linkTag.parentElement.parentElement.dataset.id;
                    let td = document.getElementById('status-'+id);
                    if (td) td.innerText = 'Да';
                }
            })
            .catch((e) => {
                this.showToast(this.error, 'Ошибка: ' + e.message);
            });
    }

    showToast(node, text) {
        node.querySelector('.toast-text').innerText = text;
        node.classList.remove('hidden');
        setTimeout(() => {
            node.classList.remove('opacity-0');
        }, 50);

        setTimeout(() => {
            node.classList.add('opacity-0');
        }, 2000);

        setTimeout(() => {
            node.classList.add('hidden');
            node.querySelector('.toast-text').innerText = '';
        }, 2150);
    }

    closeToast(event) {
        let node = event.currentTarget.parentElement;

        node.classList.add('opacity-0');
        setTimeout(() => {
            node.classList.add('hidden');
            node.querySelector('.toast-text').innerText = '';
        }, 150);
    }
}
