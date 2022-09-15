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
        node.className = 'bg-white p-4 mb-3 border border-gray-300 rounded comment-wrap';
        let header = document.createElement('p');
        header.className = 'text-sm text-blue-600';
        header.innerText = commentObj.full_name + ' ';
        let date = document.createElement('span');
        date.className = 'text-xs text-gray-400 ml-1';
        date.innerText = commentObj.datetime;
        header.appendChild(date);
        if (commentObj.editable) {
            header.insertAdjacentHTML('beforeend', this.getEditBtn());
        }
        if (commentObj.deletable) {
            header.insertAdjacentHTML('beforeend', this.getDeleteBtn());
        }
        let error = document.createElement('span');
        error.className = 'error text-red-500 ml-1';
        header.appendChild(error);
        node.appendChild(header);
        let comment = document.createElement('p');
        comment.className = 'comment-text';
        comment.innerText = commentObj.comment_text;
        node.appendChild(comment);

        return node;
    }

    getEditBtn() {
        return ' <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-pencil inline-block text-gray-400 mx-1 -mt-1 cursor-pointer edit-comment-btn" viewBox="0 0 16 16"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg>';
    }

    getDeleteBtn() {
        return ' <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash inline-block text-gray-400 -mt-1 cursor-pointer delete-comment-btn" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>';
    }

}
