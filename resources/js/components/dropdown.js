import { listen, $, enter, leave } from '../util';

listen('click', '[data-dropdown-trigger]', ({ target }) => {
    const dropdownList = $('[data-dropdown-list]', target.closest('[data-dropdown]'));

    if (!dropdownList.classList.contains('hidden')) {
        return;
    }

    enter(dropdownList, 'fade');
    target.classList.add('dropdown-trigger-open');

    function handleClick(event) {
        if (dropdownList.contains(event.target)) {
            return;
        }

        leave(dropdownList, 'fade');
        target.classList.remove('dropdown-trigger-open');

        window.removeEventListener('click', handleClick);
    }

    setTimeout(() => {
        window.addEventListener('click', handleClick);
    });
});
