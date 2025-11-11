BX.ready(() => {
    document.querySelectorAll('.js-my-user-card-avatar-show-in-new-page').forEach((item) => {
        BX.Event.bind(item, 'click', (e) => {
            window.open(item.src, '_blank');
        });
    });
});
