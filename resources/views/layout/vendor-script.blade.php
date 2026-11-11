<script type="module" src="{{asset('js/notif.js')}}"></script>
<script src="{{asset('libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script>
    (function () {
        function configureTableDropdown(toggleEl) {
            if (!window.bootstrap || !bootstrap.Dropdown || !toggleEl) return;
            var existing = bootstrap.Dropdown.getInstance(toggleEl);
            if (existing && existing._fixedStrategyApplied) return;
            if (existing) {
                existing.dispose();
            }
            var instance = new bootstrap.Dropdown(toggleEl, {
                boundary: 'viewport',
                popperConfig: function (defaultBsPopperConfig) {
                    return Object.assign({}, defaultBsPopperConfig || {}, {
                        strategy: 'fixed'
                    });
                }
            });
            instance._fixedStrategyApplied = true;
        }

        function initAllTableDropdowns() {
            document.querySelectorAll('.table-responsive [data-bs-toggle="dropdown"]').forEach(configureTableDropdown);
        }

        document.addEventListener('mousedown', function (e) {
            var toggle = e.target.closest('.table-responsive [data-bs-toggle="dropdown"]');
            if (toggle) configureTableDropdown(toggle);
        }, true);

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initAllTableDropdowns);
        } else {
            initAllTableDropdowns();
        }
    })();
</script>
<script src="{{asset('libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('libs/node-waves/waves.min.js')}}"></script>
<script src="{{asset('libs/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('js/plugins.js')}}"></script>

@yield('plugins')
<!-- App js -->
<script src="{{asset('js/app.js')}}"></script>

