export default class Stages {

    constructor() {
        this.csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.isInited = false;
        this.stageSelect = null;
    }

    init() {
        if (this.isInited) return;
        this.bindEvents();
        this.isInited = true;
    }

    bindEvents() {
        if (this.isInited) return;

        const stageSelect = document.getElementById('stage_id');

        if (stageSelect) {
            this.stageSelect = stageSelect;
            stageSelect.addEventListener('change', this.changeStage.bind(this));
        }
    }

    changeStage(event) {
        event.preventDefault();

        const success = document.getElementById('change-stage-success-message'),
              error = document.getElementById('change-stage-error-message');

        if (!success || !error || !this.csrf) return;
        success.innerText = '';
        error.innerText = '';

        let value = this.stageSelect.value,
            taskId = this.stageSelect.dataset.taskId;

        if (!value || !taskId) return;

        let data = {
            'stage_id': value
        };

        fetch('/change-stage/' + taskId, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': this.csrf,
                'Content-Type': 'application/json;charset=utf-8'
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(result => {
                if (result.status && result.status === 'error') {
                    error.innerText = result.text;
                }

                if (result.status && result.status === 'success') {
                    success.innerText = result.text;
                }
            });
    }
}
