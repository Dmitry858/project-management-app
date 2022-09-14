export default class Comments {

    constructor() {
        this.csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.isInited = false;
    }

    init() {
        if (this.isInited) return;
        this.bindEvents();
        this.isInited = true;
    }

    bindEvents() {
        if (this.isInited) return;
        const addCommentBtn = document.getElementById('add-comment-btn');
        if (addCommentBtn) {
            addCommentBtn.addEventListener('click', this.addNewComment.bind(this));
        }
    }

    addNewComment(event) {
        event.preventDefault();
        const commentsWrap = document.getElementById('comments-wrap'),
            comment = document.getElementById('comment'),
            error = document.getElementById('add-comment-error');

        if (!commentsWrap || !comment || !error || !this.csrf) return;
        error.innerText = '';
        let value = comment.value.trim();
        if (!value) {
            error.innerText = 'Пожалуйста, добавьте комментарий';
            return;
        }

        let data = {
            'id': comment.dataset.taskId,
            'comment': value
        };

        fetch('/comments/create', {
            method: 'POST',
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
                    let commentNode = this.getCommentNode(result.result);
                    commentsWrap.appendChild(commentNode);
                    comment.value = '';
                }
            });
    }

    getCommentNode(commentObj) {
        let node = document.createElement('div');
        node.className = 'bg-white p-4 mb-3 border border-gray-300 rounded';
        let header = document.createElement('p');
        header.className = 'text-sm text-blue-600';
        header.innerText = commentObj.full_name + ' ';
        let date = document.createElement('span');
        date.className = 'text-xs text-gray-400 ml-1';
        date.innerText = commentObj.datetime;
        header.appendChild(date);
        node.appendChild(header);
        let comment = document.createElement('p');
        comment.innerText = commentObj.comment_text;
        node.appendChild(comment);

        return node;
    }

}
