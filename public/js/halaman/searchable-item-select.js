/**
 * Searchable & Customizable Item Select Enhancer for Tokabe Invoice & SPB Forms
 * - Renders a floating portal dropdown on document.body (immune to table overflow clipping)
 * - Supports instant search filtering AND custom item input ("+ Gunakan item custom: ...")
 * - Automatically shows an inline custom input when "Customize" is selected or "Ketik Item Custom" is clicked
 * - Keeps original <select> and <option data-*> intact for full compatibility with tax, price & draft scripts
 */
(function () {
    'use strict';

    var TAX_BADGE_MAP = {
        ppn: { label: 'PPN', bg: 'var(--brand-subtle)', color: 'var(--brand)', border: 'var(--brand-border)' },
        pph: { label: 'PPh', bg: 'var(--warning-subtle)', color: 'var(--warning-text)', border: 'var(--warning-border)' },
        ppn_pph: { label: 'PPN & PPh', bg: 'var(--accent-cyan-subtle)', color: 'var(--accent-cyan)', border: 'var(--accent-cyan-border)' },
        tanpa_pajak: { label: 'Tanpa Pajak', bg: 'var(--bg-surface-3)', color: 'var(--text-secondary)', border: 'var(--border-subtle)' },
        custom: { label: 'Custom', bg: 'var(--success-subtle)', color: 'var(--success-text)', border: 'var(--success-border)' }
    };

    var activePortal = null;
    var activeSelect = null;
    var activeTrigger = null;
    var highlightedIndex = -1;

    function injectStyles() {
        if (document.getElementById('tokabe-searchable-select-styles')) return;
        var style = document.createElement('style');
        style.id = 'tokabe-searchable-select-styles';
        style.textContent = [
            '.tokabe-ss-wrapper { position: relative; width: 100%; }',
            '.tokabe-ss-native-hidden {',
            '  position: absolute !important; width: 1px !important; height: 1px !important;',
            '  padding: 0 !important; margin: -1px !important; overflow: hidden !important;',
            '  clip: rect(0, 0, 0, 0) !important; white-space: nowrap !important; border: 0 !important;',
            '  opacity: 0 !important; pointer-events: none !important;',
            '}',
            '.tokabe-ss-trigger {',
            '  width: 100%; min-height: 40px; padding: 8px 13px;',
            '  background-color: var(--bg-surface-2); border: 1px solid var(--border-default); border-radius: var(--radius-control, 10px);',
            '  color: var(--text-primary); font-size: 13.5px; font-family: inherit; text-align: left;',
            '  display: flex; align-items: center; justify-content: space-between; gap: 8px;',
            '  cursor: pointer; user-select: none;',
            '}',
            '.tokabe-ss-trigger:hover:not(:disabled) { border-color: var(--border-strong); background-color: var(--bg-surface-2); }',
            '.tokabe-ss-trigger:focus, .tokabe-ss-trigger.is-open {',
            '  outline: none; border-color: var(--brand) !important;',
            '  box-shadow: var(--focus-ring) !important;',
            '  background-color: var(--bg-input) !important;',
            '}',
            '.tokabe-ss-trigger:disabled { opacity: 0.65; cursor: not-allowed; }',
            '.tokabe-ss-trigger-label {',
            '  flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;',
            '  color: var(--text-primary); font-weight: 500; display: flex; align-items: center; gap: 6px;',
            '}',
            '.tokabe-ss-trigger-label.is-placeholder { color: var(--text-tertiary); font-weight: 400; }',
            '.tokabe-ss-trigger-arrow {',
            '  color: var(--text-tertiary); font-size: 16px; line-height: 1; transition: transform 160ms ease; flex-shrink: 0;',
            '}',
            '.tokabe-ss-trigger.is-open .tokabe-ss-trigger-arrow { transform: rotate(180deg); color: var(--brand); }',
            '.tokabe-ss-portal {',
            '  position: fixed; z-index: 100050;',
            '  background-color: var(--bg-surface); border: 1px solid var(--border-default); border-radius: 12px;',
            '  box-shadow: var(--shadow-dropdown);',
            '  display: flex; flex-direction: column; overflow: hidden;',
            '  font-family: var(--tkb-font, inherit);',
            '}',
            '.tokabe-ss-search-box {',
            '  padding: 9px 12px; background-color: var(--bg-surface-2); border-bottom: 1px solid var(--border-subtle);',
            '  display: flex; align-items: center; gap: 8px;',
            '}',
            '.tokabe-ss-search-icon { color: var(--text-tertiary); font-size: 15px; flex-shrink: 0; }',
            '.tokabe-ss-search-input {',
            '  width: 100%; background: transparent !important; border: none !important;',
            '  color: var(--text-primary) !important; font-size: 13px !important; padding: 2px 0 !important;',
            '  outline: none !important; box-shadow: none !important;',
            '}',
            '.tokabe-ss-search-input::placeholder { color: var(--text-tertiary) !important; }',
            '.tokabe-ss-search-clear {',
            '  background: none; border: none; color: var(--text-tertiary); cursor: pointer;',
            '  padding: 2px 4px; font-size: 14px; line-height: 1; border-radius: 4px; display: none;',
            '}',
            '.tokabe-ss-search-clear:hover { color: var(--text-primary); background: var(--bg-hover); }',
            '.tokabe-ss-list {',
            '  max-height: 240px; overflow-y: auto; padding: 6px; margin: 0; list-style: none;',
            '}',
            '.tokabe-ss-list::-webkit-scrollbar { width: 6px; }',
            '.tokabe-ss-list::-webkit-scrollbar-track { background: var(--bg-surface); }',
            '.tokabe-ss-list::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 3px; }',
            '.tokabe-ss-option {',
            '  padding: 8px 10px; border-radius: 8px; cursor: pointer;',
            '  display: flex; align-items: center; justify-content: space-between; gap: 8px;',
            '  color: var(--text-primary); font-size: 13px;',
            '}',
            '.tokabe-ss-option:hover, .tokabe-ss-option.is-highlighted {',
            '  background-color: var(--bg-surface-2); color: var(--text-primary);',
            '}',
            '.tokabe-ss-option.is-selected {',
            '  background-color: var(--brand-subtle); color: var(--brand); font-weight: 600;',
            '}',
            '.tokabe-ss-option.is-create-custom {',
            '  background-color: var(--brand-subtle); border: 1px dashed var(--brand-border);',
            '  color: var(--brand); font-weight: 600; margin-top: 2px;',
            '}',
            '.tokabe-ss-option.is-create-custom:hover, .tokabe-ss-option.is-create-custom.is-highlighted {',
            '  background-color: var(--brand); color: #FFFFFF; border-color: var(--brand);',
            '}',
            '.tokabe-ss-option-text { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }',
            '.tokabe-ss-option-meta { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }',
            '.tokabe-ss-tax-pill {',
            '  font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 999px;',
            '  letter-spacing: 0.01em; white-space: nowrap;',
            '}',
            '.tokabe-ss-footer {',
            '  padding: 8px; background-color: var(--bg-surface-2); border-top: 1px solid var(--border-subtle);',
            '  display: flex; align-items: center; justify-content: space-between;',
            '}',
            '.tokabe-ss-custom-btn {',
            '  width: 100%; background: var(--brand-subtle); border: 1px dashed var(--brand-border);',
            '  color: var(--brand); border-radius: 8px; padding: 7px 12px; font-size: 12.5px; font-weight: 600;',
            '  display: flex; align-items: center; justify-content: center; gap: 6px; cursor: pointer;',
            '}',
            '.tokabe-ss-custom-btn:hover {',
            '  background: var(--brand); color: #FFFFFF; border-color: var(--brand);',
            '}',
            '.tokabe-ss-inline-custom {',
            '  margin-top: 6px; display: none; align-items: center; gap: 6px;',
            '}',
            '.tokabe-ss-inline-custom.is-visible { display: flex; }',
            '.tokabe-ss-inline-input {',
            '  flex: 1; background-color: var(--bg-input) !important; border: 1px solid var(--brand) !important;',
            '  border-radius: 8px !important; color: var(--text-primary) !important; font-size: 13px !important;',
            '  padding: 7px 11px !important; outline: none !important;',
            '  box-shadow: var(--focus-ring) !important;',
            '}',
            '.tokabe-ss-inline-input::placeholder { color: var(--text-tertiary) !important; }',
            '.tokabe-ss-inline-close {',
            '  background: var(--bg-surface-2); border: 1px solid var(--border-default); color: var(--text-tertiary);',
            '  border-radius: 8px; width: 34px; height: 34px; display: inline-flex;',
            '  align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0;',
            '}',
            '.tokabe-ss-inline-close:hover { color: var(--danger-text); border-color: var(--danger-border); background: var(--danger-subtle); }'
        ].join('\n');
        document.head.appendChild(style);
    }

    function getSelectedOptionInfo(select) {
        var opt = select.selectedOptions && select.selectedOptions[0];
        if (!opt || !opt.value || opt.disabled) {
            var firstOpt = select.options && select.options[0];
            var placeholder = (firstOpt && (!firstOpt.value || firstOpt.disabled)) ? firstOpt.textContent.trim() : 'Pilih Item';
            return { text: placeholder || 'Pilih Item', isPlaceholder: true, isCustom: false };
        }
        var isCustom = (opt.dataset && opt.dataset.custom === 'true') || String(opt.value).indexOf('custom:') === 0;
        return { text: opt.textContent.trim(), isPlaceholder: false, isCustom: isCustom };
    }

    function syncTriggerFromSelect(select) {
        if (!select || !select._tokabeTrigger) return;
        var labelEl = select._tokabeTrigger.querySelector('.tokabe-ss-trigger-label');
        if (!labelEl) return;
        var info = getSelectedOptionInfo(select);
        labelEl.innerHTML = '';
        var textNode = document.createElement('span');
        textNode.style.overflow = 'hidden';
        textNode.style.textOverflow = 'ellipsis';
        textNode.style.whiteSpace = 'nowrap';
        textNode.textContent = info.text;
        labelEl.appendChild(textNode);

        if (info.isCustom) {
            var badge = document.createElement('span');
            badge.className = 'tokabe-ss-tax-pill';
            badge.style.backgroundColor = TAX_BADGE_MAP.custom.bg;
            badge.style.color = TAX_BADGE_MAP.custom.color;
            badge.style.border = '1px solid ' + TAX_BADGE_MAP.custom.border;
            badge.textContent = 'Custom';
            labelEl.appendChild(badge);
        }

        if (info.isPlaceholder) {
            labelEl.classList.add('is-placeholder');
        } else {
            labelEl.classList.remove('is-placeholder');
        }
        select._tokabeTrigger.disabled = !!select.disabled;

        // If selected option text is "Customize", show the inline custom input box automatically
        if (select._tokabeInlineBox && !info.isPlaceholder) {
            if (info.text.toLowerCase() === 'customize') {
                showInlineCustomInput(select, '');
            }
        }
    }

    function applyCustomItemValue(select, customText, keepInlineOpen) {
        var clean = (customText || '').trim();
        if (!clean) return;

        var customVal = 'custom:' + clean;

        // Check if an option with this exact text already exists
        var existingOpt = Array.from(select.options).find(function (o) {
            return !o.disabled && o.value !== '' &&
                o.textContent.trim().toLowerCase() === clean.toLowerCase() &&
                o.textContent.trim().toLowerCase() !== 'customize';
        });

        if (existingOpt) {
            selectOptionValue(select, existingOpt.value, keepInlineOpen);
            return;
        }

        // Reuse or create a dynamic custom option on this select
        var dynOpt = select.querySelector('option[data-custom-dynamic="true"]');
        if (!dynOpt) {
            dynOpt = document.createElement('option');
            dynOpt.setAttribute('data-custom-dynamic', 'true');
            dynOpt.setAttribute('data-custom', 'true');
            dynOpt.setAttribute('data-jenis-pajak', 'tanpa_pajak');
            select.appendChild(dynOpt);
        }
        dynOpt.value = customVal;
        dynOpt.textContent = clean;

        selectOptionValue(select, customVal, keepInlineOpen);
    }

    function showInlineCustomInput(select, initialText) {
        if (!select || !select._tokabeInlineBox) return;
        var box = select._tokabeInlineBox;
        var input = box.querySelector('.tokabe-ss-inline-input');
        box.classList.add('is-visible');
        if (input) {
            if (initialText !== undefined && initialText !== null) {
                input.value = initialText;
            }
            setTimeout(function () {
                input.focus();
                input.select();
            }, 20);
        }
    }

    function hideInlineCustomInput(select) {
        if (!select || !select._tokabeInlineBox) return;
        select._tokabeInlineBox.classList.remove('is-visible');
    }

    function positionPortal() {
        if (!activePortal || !activeTrigger) return;
        var rect = activeTrigger.getBoundingClientRect();
        var viewportHeight = window.innerHeight || document.documentElement.clientHeight;
        var viewportWidth = window.innerWidth || document.documentElement.clientWidth;

        var spaceBelow = viewportHeight - rect.bottom - 12;
        var spaceAbove = rect.top - 12;

        var width = Math.max(rect.width, 270);
        var left = rect.left;
        if (left + width > viewportWidth - 12) {
            left = Math.max(12, viewportWidth - width - 12);
        }

        activePortal.style.width = width + 'px';
        activePortal.style.left = left + 'px';

        var listEl = activePortal.querySelector('.tokabe-ss-list');
        if (spaceBelow >= 220 || spaceBelow >= spaceAbove) {
            var maxListH = Math.min(230, Math.max(130, spaceBelow - 90));
            if (listEl) listEl.style.maxHeight = maxListH + 'px';
            activePortal.style.top = (rect.bottom + 4) + 'px';
            activePortal.style.bottom = 'auto';
        } else {
            var maxListHUp = Math.min(230, Math.max(130, spaceAbove - 90));
            if (listEl) listEl.style.maxHeight = maxListHUp + 'px';
            activePortal.style.bottom = (viewportHeight - rect.top + 4) + 'px';
            activePortal.style.top = 'auto';
        }
    }

    function closePortal() {
        if (activePortal && activePortal.parentNode) {
            activePortal.parentNode.removeChild(activePortal);
        }
        if (activeTrigger) {
            activeTrigger.classList.remove('is-open');
        }
        activePortal = null;
        activeSelect = null;
        activeTrigger = null;
        highlightedIndex = -1;
    }

    function selectOptionValue(select, value, keepInlineOpen) {
        if (!select) return;
        var options = Array.from(select.options);
        options.forEach(function (opt) {
            opt.selected = (String(opt.value) === String(value) && !opt.disabled);
        });
        select.value = value;

        var selectedOpt = select.selectedOptions && select.selectedOptions[0];
        var selectedText = selectedOpt ? selectedOpt.textContent.trim() : '';

        if (!keepInlineOpen && selectedText.toLowerCase() !== 'customize') {
            hideInlineCustomInput(select);
        }

        syncTriggerFromSelect(select);

        var evt = new Event('change', { bubbles: true });
        select.dispatchEvent(evt);
        if (window.jQuery) {
            window.jQuery(select).trigger('change');
        }
        closePortal();
    }

    function openPortalForSelect(select, trigger) {
        if (activeSelect === select && activePortal) {
            closePortal();
            return;
        }
        closePortal();

        activeSelect = select;
        activeTrigger = trigger;
        trigger.classList.add('is-open');

        var portal = document.createElement('div');
        portal.className = 'tokabe-ss-portal';

        var searchBox = document.createElement('div');
        searchBox.className = 'tokabe-ss-search-box';
        searchBox.innerHTML = [
            '<i class="ri-search-line tokabe-ss-search-icon"></i>',
            '<input type="text" class="tokabe-ss-search-input" placeholder="Cari atau ketik item custom..." autocomplete="off" spellcheck="false">',
            '<button type="button" class="tokabe-ss-search-clear" title="Bersihkan pencarian">&times;</button>'
        ].join('');

        var list = document.createElement('ul');
        list.className = 'tokabe-ss-list';

        var footer = document.createElement('div');
        footer.className = 'tokabe-ss-footer';
        footer.innerHTML = '<button type="button" class="tokabe-ss-custom-btn"><i class="ri-edit-2-line"></i>Ketik Nama Item Custom...</button>';

        portal.appendChild(searchBox);
        portal.appendChild(list);
        portal.appendChild(footer);
        document.body.appendChild(portal);
        activePortal = portal;

        var searchInput = searchBox.querySelector('.tokabe-ss-search-input');
        var clearBtn = searchBox.querySelector('.tokabe-ss-search-clear');
        var customBtn = footer.querySelector('.tokabe-ss-custom-btn');

        customBtn.addEventListener('mousedown', function (e) {
            e.preventDefault();
            var typed = (searchInput.value || '').trim();
            closePortal();
            if (typed) {
                applyCustomItemValue(select, typed, true);
                showInlineCustomInput(select, typed);
            } else {
                var currentInfo = getSelectedOptionInfo(select);
                var initVal = (!currentInfo.isPlaceholder && currentInfo.text.toLowerCase() !== 'customize') ? currentInfo.text : '';
                showInlineCustomInput(select, initVal);
            }
        });

        var allOptions = Array.from(select.options).filter(function (opt) {
            return opt.value !== '' && !opt.disabled && opt.textContent.trim() !== 'Pilih Item';
        });

        var actionableEntries = [];

        function renderOptions(query) {
            var rawQuery = (query || '').trim();
            var q = rawQuery.toLowerCase();
            clearBtn.style.display = q.length > 0 ? 'inline-block' : 'none';

            var filtered = allOptions.filter(function (opt) {
                if (!q) return true;
                return opt.textContent.toLowerCase().indexOf(q) !== -1;
            });

            var exactMatch = allOptions.some(function (opt) {
                return opt.textContent.trim().toLowerCase() === q;
            });

            list.innerHTML = '';
            actionableEntries = [];
            highlightedIndex = -1;

            var currentVal = String(select.value || '');

            filtered.forEach(function (opt) {
                var idx = actionableEntries.length;
                actionableEntries.push({ type: 'option', opt: opt });

                var li = document.createElement('li');
                li.className = 'tokabe-ss-option';
                var isSelected = (String(opt.value) === currentVal && currentVal !== '');
                if (isSelected) {
                    li.classList.add('is-selected');
                    highlightedIndex = idx;
                }

                var textSpan = document.createElement('span');
                textSpan.className = 'tokabe-ss-option-text';
                textSpan.textContent = opt.textContent.trim();
                li.appendChild(textSpan);

                var metaSpan = document.createElement('span');
                metaSpan.className = 'tokabe-ss-option-meta';

                var isCustomOpt = (opt.dataset && opt.dataset.custom === 'true') || String(opt.value).indexOf('custom:') === 0;
                var taxType = isCustomOpt ? 'custom' : (opt.dataset ? opt.dataset.jenisPajak : '');
                if (taxType && TAX_BADGE_MAP[taxType]) {
                    var badgeInfo = TAX_BADGE_MAP[taxType];
                    var pill = document.createElement('span');
                    pill.className = 'tokabe-ss-tax-pill';
                    pill.style.backgroundColor = badgeInfo.bg;
                    pill.style.color = badgeInfo.color;
                    pill.style.border = '1px solid ' + badgeInfo.border;
                    pill.textContent = badgeInfo.label;
                    metaSpan.appendChild(pill);
                }

                if (isSelected) {
                    var checkIcon = document.createElement('i');
                    checkIcon.className = 'ri-check-line';
                    checkIcon.style.color = '#818CF8';
                    checkIcon.style.fontSize = '15px';
                    metaSpan.appendChild(checkIcon);
                }

                if (metaSpan.childNodes.length > 0) {
                    li.appendChild(metaSpan);
                }

                li.addEventListener('mouseenter', function () {
                    Array.from(list.querySelectorAll('.tokabe-ss-option')).forEach(function (el) {
                        el.classList.remove('is-highlighted');
                    });
                    li.classList.add('is-highlighted');
                    highlightedIndex = idx;
                });

                li.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    selectOptionValue(select, opt.value, false);
                });

                list.appendChild(li);
            });

            // If user typed something that isn't an exact match, show "+ Gunakan item custom: ..."
            if (rawQuery.length > 0 && !exactMatch) {
                var createIdx = actionableEntries.length;
                actionableEntries.push({ type: 'create', text: rawQuery });

                var createLi = document.createElement('li');
                createLi.className = 'tokabe-ss-option is-create-custom';
                createLi.innerHTML = [
                    '<span class="tokabe-ss-option-text"><i class="ri-add-circle-line me-1"></i>Gunakan item custom: <strong></strong></span>',
                    '<span class="tokabe-ss-option-meta"><span class="tokabe-ss-tax-pill" style="background:rgba(16,185,129,0.2);color:#6EE7B7;border:1px solid rgba(16,185,129,0.4);">Custom</span></span>'
                ].join('');
                createLi.querySelector('strong').textContent = '"' + rawQuery + '"';

                createLi.addEventListener('mouseenter', function () {
                    Array.from(list.querySelectorAll('.tokabe-ss-option')).forEach(function (el) {
                        el.classList.remove('is-highlighted');
                    });
                    createLi.classList.add('is-highlighted');
                    highlightedIndex = createIdx;
                });

                createLi.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    applyCustomItemValue(select, rawQuery, false);
                });

                list.appendChild(createLi);
            }

            if (highlightedIndex < 0 && actionableEntries.length > 0) {
                highlightedIndex = 0;
                var firstItem = list.querySelector('.tokabe-ss-option');
                if (firstItem) firstItem.classList.add('is-highlighted');
            }
        }

        function updateHighlightVisual() {
            var items = Array.from(list.querySelectorAll('.tokabe-ss-option'));
            items.forEach(function (el, i) {
                if (i === highlightedIndex) {
                    el.classList.add('is-highlighted');
                    el.scrollIntoView({ block: 'nearest' });
                } else {
                    el.classList.remove('is-highlighted');
                }
            });
        }

        searchInput.addEventListener('input', function () {
            renderOptions(searchInput.value);
            positionPortal();
        });

        clearBtn.addEventListener('mousedown', function (e) {
            e.preventDefault();
            searchInput.value = '';
            renderOptions('');
            searchInput.focus();
            positionPortal();
        });

        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (actionableEntries.length > 0) {
                    highlightedIndex = (highlightedIndex + 1) % actionableEntries.length;
                    updateHighlightVisual();
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (actionableEntries.length > 0) {
                    highlightedIndex = (highlightedIndex - 1 + actionableEntries.length) % actionableEntries.length;
                    updateHighlightVisual();
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (highlightedIndex >= 0 && actionableEntries[highlightedIndex]) {
                    var entry = actionableEntries[highlightedIndex];
                    if (entry.type === 'create') {
                        applyCustomItemValue(select, entry.text, false);
                    } else if (entry.opt) {
                        selectOptionValue(select, entry.opt.value, false);
                    }
                }
            } else if (e.key === 'Escape') {
                e.preventDefault();
                closePortal();
                trigger.focus();
            }
        });

        renderOptions('');
        positionPortal();

        var selectedLi = list.querySelector('.tokabe-ss-option.is-selected');
        if (selectedLi) {
            selectedLi.scrollIntoView({ block: 'nearest' });
        }

        setTimeout(function () {
            if (searchInput) searchInput.focus();
        }, 10);
    }

    function enhanceSelect(select) {
        if (!select || select._tokabeEnhanced) return;
        if (select.closest('.choices')) return;

        injectStyles();
        select._tokabeEnhanced = true;

        if (select.hasAttribute('required')) {
            select.removeAttribute('required');
            select.dataset.wasRequired = 'true';
        }

        var wrapper = document.createElement('div');
        wrapper.className = 'tokabe-ss-wrapper';
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
        select.classList.add('tokabe-ss-native-hidden');
        select.tabIndex = -1;

        var trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'tokabe-ss-trigger';
        trigger.innerHTML = [
            '<span class="tokabe-ss-trigger-label is-placeholder">Pilih Item</span>',
            '<i class="ri-arrow-down-s-line tokabe-ss-trigger-arrow"></i>'
        ].join('');

        // Inline custom item input box (for "Customize" or "Ketik Nama Item Custom...")
        var inlineBox = document.createElement('div');
        inlineBox.className = 'tokabe-ss-inline-custom';
        inlineBox.innerHTML = [
            '<input type="text" class="tokabe-ss-inline-input" placeholder="Ketik nama item custom di sini..." autocomplete="off">',
            '<button type="button" class="tokabe-ss-inline-close" title="Tutup input custom"><i class="ri-close-line"></i></button>'
        ].join('');

        wrapper.appendChild(trigger);
        wrapper.appendChild(inlineBox);

        select._tokabeTrigger = trigger;
        select._tokabeInlineBox = inlineBox;

        var inlineInput = inlineBox.querySelector('.tokabe-ss-inline-input');
        var inlineClose = inlineBox.querySelector('.tokabe-ss-inline-close');

        inlineInput.addEventListener('input', function () {
            var val = inlineInput.value.trim();
            if (val) {
                applyCustomItemValue(select, val, true);
            }
        });

        inlineInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                var val = inlineInput.value.trim();
                if (val) {
                    applyCustomItemValue(select, val, false);
                }
            } else if (e.key === 'Escape') {
                e.preventDefault();
                hideInlineCustomInput(select);
            }
        });

        inlineClose.addEventListener('click', function () {
            hideInlineCustomInput(select);
        });

        syncTriggerFromSelect(select);

        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            if (select.disabled) return;
            openPortalForSelect(select, trigger);
        });

        trigger.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
                e.preventDefault();
                if (!select.disabled) {
                    openPortalForSelect(select, trigger);
                }
            }
        });

        select.addEventListener('change', function () {
            syncTriggerFromSelect(select);
        });

        select.addEventListener('focus', function () {
            trigger.focus();
            openPortalForSelect(select, trigger);
        });
    }

    function enhanceAllItemSelects(root) {
        var container = root || document;
        var selects = container.querySelectorAll(
            'select[name^="item_id"], select[name="barang_id[]"], select.searchable-item-select'
        );
        selects.forEach(function (sel) {
            if (sel.closest('#template-options-barang') || sel.closest('#template-options-item') || sel.closest('#template-options-spb')) {
                return;
            }
            enhanceSelect(sel);
        });
    }

    document.addEventListener('mousedown', function (e) {
        if (!activePortal) return;
        if (activePortal.contains(e.target)) return;
        if (activeTrigger && activeTrigger.contains(e.target)) return;
        closePortal();
    });

    window.addEventListener('scroll', function () {
        if (activePortal) positionPortal();
    }, true);

    window.addEventListener('resize', function () {
        if (activePortal) positionPortal();
    });

    function initSearchableItemSelects() {
        enhanceAllItemSelects(document);

        var newlink = document.getElementById('newlink');
        if (newlink && typeof MutationObserver !== 'undefined') {
            var observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (m) {
                    m.addedNodes.forEach(function (node) {
                        if (node.nodeType === 1) {
                            enhanceAllItemSelects(node);
                        }
                    });
                });
            });
            observer.observe(newlink, { childList: true, subtree: true });
        }
    }

    window.initSearchableItemSelects = initSearchableItemSelects;
    window.enhanceSearchableSelect = enhanceSelect;
    window.syncSearchableSelect = syncTriggerFromSelect;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearchableItemSelects);
    } else {
        initSearchableItemSelects();
    }
})();
