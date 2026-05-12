<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">{{__('messages.Home')}}</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('admin.logout') }}" class="nav-link">{{__('messages.Logout')}}</a>
      </li>
      @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <a class="nav-link" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
            {{ $properties['native'] }}
        </a>
      @endforeach
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown" id="notif-dropdown">
        <a class="nav-link" href="#" id="notif-bell" data-toggle="dropdown" aria-expanded="false">
          <i class="far fa-bell"></i>
          <span class="badge badge-danger navbar-badge" id="notif-count" style="display:none;">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notif-menu"
             style="min-width:340px; max-height:440px; overflow-y:auto;">
          <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
            <strong>{{ __('messages.notifications') }}</strong>
            <a href="#" id="notif-mark-all" class="text-sm text-muted">{{ __('messages.Mark All Read') }}</a>
          </div>
          <div id="notif-list">
            <span class="dropdown-item text-muted text-center py-3">{{ __('messages.No_data') }}</span>
          </div>
        </div>
      </li>
    </ul>
</nav>

<style>
.notif-item {
    white-space: normal;
    font-size: .84rem;
    padding: 10px 14px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    display: flex;
    align-items: flex-start;
    gap: 8px;
}
.notif-item:hover  { background: #f8f9fa; }
.notif-item.unread { background: #fff8e1; }
.notif-item .notif-icon { font-size: 1.1rem; margin-top: 2px; flex-shrink: 0; }
.notif-item .notif-body { flex: 1; }
.notif-item .notif-time { font-size: .72rem; color: #999; display: block; margin-top: 3px; }
.notif-icon-new      { color: #28a745; }
.notif-icon-unexec   { color: #fd7e14; }
.notif-icon-unret    { color: #dc3545; }
.notif-icon-conflict { color: #dc3545; }
</style>

<script>
(function () {
    /* ── URL injection from Blade ─────────────────────────── */
    var NOTIF_INDEX_URL  = '{{ route("admin.notifications.index") }}';
    var NOTIF_STREAM_URL = '{{ route("admin.notifications.stream") }}';
    var NOTIF_MARK_ALL   = '{{ route("admin.notifications.mark-all-read") }}';
    var CSRF             = '{{ csrf_token() }}';
    var BASE_ORDERS_URL  = '{{ url("") }}/{{ LaravelLocalization::getCurrentLocale() }}/admin/orders/';

    /* ── Icon helpers ─────────────────────────────────────── */
    var TYPE_ICONS = {
        new_order:        { fa: 'fa-plus-circle',        css: 'notif-icon-new'      },
        unexecuted_order: { fa: 'fa-clock',              css: 'notif-icon-unexec'   },
        unreturned_order: { fa: 'fa-undo',               css: 'notif-icon-unret'    },
        character_conflict:{ fa: 'fa-exclamation-triangle', css: 'notif-icon-conflict' },
    };

    function iconHtml(type) {
        var t = TYPE_ICONS[type] || { fa: 'fa-bell', css: '' };
        return '<i class="fas ' + t.fa + ' notif-icon ' + t.css + '"></i>';
    }

    /* ── Audio beep (Web Audio API — no file needed) ─────── */
    function beep() {
        try {
            var ctx  = new (window.AudioContext || window.webkitAudioContext)();
            var osc  = ctx.createOscillator();
            var gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type            = 'sine';
            osc.frequency.value = 880;
            gain.gain.setValueAtTime(0.25, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.6);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.6);
        } catch (e) {}
    }

    /* ── Browser / OS notification ───────────────────────── */
    function requestPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }

    function showBrowserNotif(n) {
        if (!('Notification' in window) || Notification.permission !== 'granted') return;

        var popup = new Notification('إشعار جديد', {
            body: n.data.message,
            icon: '/favicon.ico',
            dir:  'rtl',
            lang: 'ar',
            tag:  'order-' + (n.data.order_id || Date.now()),
            renotify: true,
        });

        popup.onclick = function () {
            window.focus();
            if (n.data.order_id) {
                window.location.href = BASE_ORDERS_URL + n.data.order_id;
            }
            popup.close();
        };

        setTimeout(function () { popup.close(); }, 9000);
    }

    /* ── Refresh bell dropdown content ───────────────────── */
    function loadNotifications() {
        $.getJSON(NOTIF_INDEX_URL, function (res) {
            var count = res.unread_count;
            if (count > 0) {
                $('#notif-count').text(count).show();
            } else {
                $('#notif-count').hide();
            }

            if (res.notifications.length === 0) {
                $('#notif-list').html(
                    '<span class="dropdown-item text-muted text-center py-3">{{ __("messages.No_data") }}</span>'
                );
                return;
            }

            var html = '';
            res.notifications.forEach(function (n) {
                var unread = n.read_at ? '' : 'unread';
                html += '<div class="notif-item ' + unread + '" data-id="' + n.id + '" data-order="' + (n.data.order_id || '') + '">'
                    + iconHtml(n.data.type)
                    + '<div class="notif-body">'
                    + n.data.message
                    + '<span class="notif-time">' + n.created_at + '</span>'
                    + '</div></div>';
            });
            $('#notif-list').html(html);
        });
    }

    /* ── SSE connection ───────────────────────────────────── */
    var sseSource = null;

    function connectSSE() {
        if (sseSource) { sseSource.close(); sseSource = null; }

        sseSource = new EventSource(NOTIF_STREAM_URL);

        sseSource.onmessage = function (event) {
            var raw = event.data;
            if (!raw || raw.trim() === '') return;

            var parsed;
            try { parsed = JSON.parse(raw); } catch (e) { return; }

            // Server signals end-of-stream → reconnect immediately
            if (parsed.reconnect) {
                sseSource.close();
                sseSource = null;
                setTimeout(connectSSE, 500);
                return;
            }

            // Real notification arrived
            beep();
            showBrowserNotif(parsed);
            loadNotifications();
        };

        sseSource.onerror = function () {
            sseSource.close();
            sseSource = null;
            // Reconnect after 5 s on network error
            setTimeout(connectSSE, 5000);
        };
    }

    /* ── Click handlers ───────────────────────────────────── */
    $(document).ready(function () {
        requestPermission();
        loadNotifications();
        connectSSE();

        // Click on individual notification → mark read + open order
        $('#notif-list').on('click', '.notif-item', function () {
            var id    = $(this).data('id');
            var order = $(this).data('order');
            $.post(
                '{{ url("") }}/{{ LaravelLocalization::getCurrentLocale() }}/admin/notifications/' + id + '/mark-read',
                { _token: CSRF }
            );
            $(this).removeClass('unread');
            loadNotifications();
            if (order) { window.location.href = BASE_ORDERS_URL + order; }
        });

        // Mark all as read
        $('#notif-mark-all').on('click', function (e) {
            e.preventDefault();
            $.post(NOTIF_MARK_ALL, { _token: CSRF }, function () { loadNotifications(); });
        });

        // Refresh count when bell is opened
        $('#notif-bell').on('click', function (e) {
            e.preventDefault();
            loadNotifications();
        });
    });
})();
</script>
