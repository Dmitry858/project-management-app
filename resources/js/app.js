require('./bootstrap');

import Alpine from 'alpinejs';

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

    // Add new comment
    const addCommentBtn = document.getElementById('add-comment-btn');
    if (addCommentBtn) {
        addCommentBtn.addEventListener('click', addNewComment);
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

// Add new comment
function addNewComment(event) {
    event.preventDefault();
    const commentsWrap = document.getElementById('comments-wrap'),
          comment = document.getElementById('comment'),
          error = document.getElementById('add-comment-error'),
          csrf = document.querySelector('input[name="_token"]');

    if (!commentsWrap || !comment || !error || !csrf) return;
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
            'X-CSRF-TOKEN': csrf.value,
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
                let commentNode = getCommentNode(result.result);
                commentsWrap.appendChild(commentNode);
                comment.value = '';
            }
            console.log(result);
        });
}

function getCommentNode(commentObj) {
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
