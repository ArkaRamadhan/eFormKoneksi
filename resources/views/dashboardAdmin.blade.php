<div>
    NANTI DASHBOARD BARUNYA DISINI COBA ARAHIN SEMUA INDEX KE SINI ARKHA BAU
</div>

<form method="POST" action="{{ route('logout') }}">
    @csrf

    <x-responsive-nav-link :href="route('logout')"
            onclick="event.preventDefault();
                        this.closest('form').submit();">
        {{ __('Log Out') }}
    </x-responsive-nav-link>
</form>

<a href="{{ route('admin.tableKoneksi') }}">Ke form aria</a>
<a href="{{ route('adminEmail.tableEmail') }}">Ke form arka</a>
