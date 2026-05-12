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
      <!-- Notifications Bell -->
      <li class="nav-item dropdown" id="notif-dropdown">
        <a class="nav-link" href="#" id="notif-bell" data-toggle="dropdown" aria-expanded="false">
          <i class="far fa-bell"></i>
          <span class="badge badge-danger navbar-badge" id="notif-count" style="display:none;">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notif-menu" style="min-width:320px;max-height:400px;overflow-y:auto;">
          <span class="dropdown-item dropdown-header" id="notif-header">{{ __('messages.notifications') }}</span>
          <div class="dropdown-divider"></div>
          <div id="notif-list">
            <span class="dropdown-item text-muted text-center">{{ __('messages.No_data') }}</span>
          </div>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer text-center" id="notif-mark-all">
            {{ __('messages.Mark All Read') }}
          </a>
        </div>
      </li>
    </ul>
  </nav>

<style>
.notif-item { white-space:normal; font-size:.85rem; padding:8px 12px; border-bottom:1px solid #f0f0f0; cursor:pointer; }
.notif-item:hover { background:#f8f9fa; }
.notif-item.unread { background:#fff8e1; font-weight:600; }
.notif-time { font-size:.75rem; color:#6c757d; display:block; margin-top:2px; }
.notif-icon-new { color:#28a745; }
.notif-icon-unexecuted { color:#fd7e14; }
.notif-icon-unreturned { color:#dc3545; }
.notif-icon-conflict { color:#dc3545; }
</style>

<script>
(function () {
    function iconClass(type) {
        return { new_order:'notif-icon-new', unexecuted_order:'notif-icon-unexecuted', unreturned_order:'notif-icon-unreturned', character_conflict:'notif-icon-conflict' }[type] || '';
    }
    function iconTag(type) {
        const icons = { new_order:'fa-plus-circle', unexecuted_order:'fa-clock', unreturned_order:'fa-undo', character_conflict:'fa-exclamation-triangle' };
        return `<i class="fas ${icons[type]||'fa-bell'} ${iconClass(type)} mr-2"></i>`;
    }

    function loadNotifications() {
        $.getJSON('{{ route("admin.notifications.index") }}', function (res) {
            var count = res.unread_count;
            if (count > 0) {
                $('#notif-count').text(count).show();
            } else {
                $('#notif-count').hide();
            }

            var html = '';
            if (res.notifications.length === 0) {
                html = '<span class="dropdown-item text-muted text-center">{{ __("messages.No_data") }}</span>';
            } else {
                res.notifications.forEach(function (n) {
                    var unread = !n.read_at ? 'unread' : '';
                    var orderId = n.data.order_id || '';
                    html += `<div class="notif-item ${unread}" data-id="${n.id}" data-order="${orderId}">
                        ${iconTag(n.data.type)}${n.data.message}
                        <span class="notif-time">${n.created_at}</span>
                    </div>`;
                });
            }
            $('#notif-list').html(html);

            // Click notification → mark read + go to order
            $('#notif-list').off('click', '.notif-item').on('click', '.notif-item', function () {
                var id    = $(this).data('id');
                var order = $(this).data('order');
                $.post('{{ url("") }}/{{ LaravelLocalization::getCurrentLocale() }}/admin/notifications/' + id + '/mark-read', { _token: '{{ csrf_token() }}' });
                $(this).removeClass('unread');
                if (order) {
                    window.location.href = '{{ url("") }}/{{ LaravelLocalization::getCurrentLocale() }}/admin/orders/' + order;
                }
                loadNotifications();
            });
        });
    }

    $(document).ready(function () {
        loadNotifications();
        // Refresh every 60 seconds
        setInterval(loadNotifications, 60000);

        $('#notif-bell').on('click', function (e) {
            e.preventDefault();
            loadNotifications();
        });

        $('#notif-mark-all').on('click', function (e) {
            e.preventDefault();
            $.post('{{ route("admin.notifications.mark-all-read") }}', { _token: '{{ csrf_token() }}' }, function () {
                loadNotifications();
            });
        });
    });
})();
</script>
