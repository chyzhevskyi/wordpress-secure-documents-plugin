document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('tda_btn_generate');
    if (!btn) return;

    btn.addEventListener('click', function() {
        const postId = this.getAttribute('data-post-id');
        const nonce = this.getAttribute('data-nonce');
        const input = document.getElementById('tda_link_input');
        const spinner = document.getElementById('tda_spinner');

        spinner.classList.add('is-active');
        this.disabled = true;

        const formData = new FormData();
        formData.append('action', tdaData.action);
        formData.append('post_id', postId);
        formData.append('nonce', nonce);

        fetch(tdaData.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(res => {
            spinner.classList.remove('is-active');
            btn.disabled = false;
            
            if (res.success) {
                input.value = res.data.link;
                input.select();
                document.execCommand('copy');
                alert('Link generated and copied to clipboard');
            } else {
                alert('Error: ' + res.data);
            }
        })
        .catch(err => {
            spinner.classList.remove('is-active');
            btn.disabled = false;
            console.error(err);
        });
    });
});
