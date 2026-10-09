function initializeDeviceDatalists() {
    document.querySelectorAll('input[list]').forEach((input) => {
        const datalist = document.getElementById(input.getAttribute('list'));
        if (!datalist || input.dataset.deviceCombobox === 'ready') return;

        input.dataset.deviceCombobox = 'ready';
        input.setAttribute('autocomplete', 'off');
        input.removeAttribute('list');

        const wrapper = document.createElement('div');
        wrapper.className = 'device-combobox';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);

        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'device-combobox__toggle';
        toggle.setAttribute('aria-label', 'Tampilkan pilihan');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.innerHTML = '<svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="m4 6 4 4 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';

        const menu = document.createElement('div');
        menu.className = 'device-combobox__menu';
        menu.setAttribute('role', 'listbox');
        wrapper.append(toggle);
        document.body.appendChild(menu);

        let activeIndex = -1;

        const getOptions = () => [...datalist.querySelectorAll('option')]
            .map((option) => ({
                value: option.value || option.textContent.trim(),
                label: option.label || option.textContent.trim() || option.value,
            }))
            .filter((option) => option.value)
            .filter((option, index, all) => all.findIndex((item) => item.value.toLowerCase() === option.value.toLowerCase()) === index);

        const close = () => {
            wrapper.classList.remove('is-open');
            menu.classList.remove('is-visible');
            toggle.setAttribute('aria-expanded', 'false');
            activeIndex = -1;
        };

        const positionMenu = () => {
            const rect = input.getBoundingClientRect();
            const gap = 6;
            const spaceBelow = window.innerHeight - rect.bottom - gap - 8;
            const spaceAbove = rect.top - gap - 8;

            menu.style.left = `${Math.max(8, rect.left)}px`;
            menu.style.width = `${Math.min(rect.width, window.innerWidth - 16)}px`;
            menu.style.maxHeight = `${Math.max(80, Math.min(240, spaceBelow))}px`;
            menu.style.top = `${rect.bottom + gap}px`;
            menu.style.bottom = 'auto';

            if (spaceBelow < 160 && spaceAbove > spaceBelow) {
                menu.style.maxHeight = `${Math.max(80, Math.min(240, spaceAbove))}px`;
                menu.style.top = 'auto';
                menu.style.bottom = `${window.innerHeight - rect.top + gap}px`;
            }
        };

        const selectOption = (value) => {
            input.value = value;
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
            close();
            input.focus();
        };

        const render = (showAll = false) => {
            const query = showAll ? '' : input.value.trim().toLocaleLowerCase();
            const matches = getOptions().filter((option) => option.label.toLocaleLowerCase().includes(query)
                || option.value.toLocaleLowerCase().includes(query));
            menu.replaceChildren();
            activeIndex = -1;

            if (!matches.length) {
                const empty = document.createElement('div');
                empty.className = 'device-combobox__empty';
                empty.textContent = 'Tidak ada pilihan yang cocok';
                menu.appendChild(empty);
            } else {
                matches.forEach(({ value, label }) => {
                    const option = document.createElement('button');
                    option.type = 'button';
                    option.className = 'device-combobox__option';
                    option.setAttribute('role', 'option');
                    option.dataset.value = value;
                    option.textContent = label;
                    option.addEventListener('mousedown', (event) => event.preventDefault());
                    option.addEventListener('click', () => selectOption(value));
                    menu.appendChild(option);
                });
            }
            wrapper.classList.add('is-open');
            menu.classList.add('is-visible');
            positionMenu();
            toggle.setAttribute('aria-expanded', 'true');
        };

        // Show the complete suggestion list on focus; typing narrows it down.
        // This matters for fields like battery capacity that start prefilled (e.g. 100 AH).
        input.addEventListener('focus', () => render(true));
        input.addEventListener('input', () => render());
        toggle.addEventListener('click', () => {
            if (wrapper.classList.contains('is-open')) {
                close();
            } else {
                input.focus();
                render(true);
            }
        });

        input.addEventListener('keydown', (event) => {
            const options = [...menu.querySelectorAll('.device-combobox__option')];
            if (event.key === 'Escape') {
                close();
            } else if (event.key === 'ArrowDown' && options.length) {
                event.preventDefault();
                activeIndex = Math.min(activeIndex + 1, options.length - 1);
                options.forEach((option, index) => option.classList.toggle('is-active', index === activeIndex));
                options[activeIndex].scrollIntoView({ block: 'nearest' });
            } else if (event.key === 'ArrowUp' && options.length) {
                event.preventDefault();
                activeIndex = Math.max(activeIndex - 1, 0);
                options.forEach((option, index) => option.classList.toggle('is-active', index === activeIndex));
                options[activeIndex].scrollIntoView({ block: 'nearest' });
            } else if (event.key === 'Enter' && wrapper.classList.contains('is-open') && activeIndex >= 0) {
                event.preventDefault();
                selectOption(options[activeIndex].dataset.value);
            }
        });

        window.addEventListener('resize', () => {
            if (wrapper.classList.contains('is-open')) positionMenu();
        });
        window.addEventListener('scroll', () => {
            if (wrapper.classList.contains('is-open')) positionMenu();
        }, true);

        document.addEventListener('mousedown', (event) => {
            if (!wrapper.contains(event.target) && !menu.contains(event.target)) close();
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeDeviceDatalists);
} else {
    initializeDeviceDatalists();
}
