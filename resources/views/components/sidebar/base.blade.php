@php
    $url = parse_url(url()->current())['path'];
    $arrayUrl = explode('/', $url);
    $menuName = $arrayUrl[1];
@endphp

<aside x-cloak
    class="h-full bg-white shadow-sm fixed top-16 z-20 -translate-x-[250px] lg:translate-x-0 lg:block border-t lg:border-t-0 transition-all"
    aria-label="Sidebar" id="menu" data-menu="false" x-bind:class="show ? 'w-56' : ''">

    <div class="sticky px-3 py-4 h-[85vh] overflow-x-visible rounded" :class="show && 'w-56'">

        <div :class="show ? 'justify-between' : 'justify-center'" class="flex px-2 py-5 items-center mb-4">
            <p x-show="show" class="text-coolgray-900 font-bold text-base">Minimize</p>
            <button @click="show = !show" class="flex justify-center">
                <span :class="show ? '' : 'rotate-180'">
                    <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.707 16.207C15.5195 16.3945 15.2652 16.4998 15 16.4998C14.7349 16.4998 14.4806 16.3945 14.293 16.207L9.29303 11.207C9.10556 11.0195 9.00024 10.7652 9.00024 10.5C9.00024 10.2348 9.10556 9.98052 9.29303 9.79299L14.293 4.79299C14.3853 4.69748 14.4956 4.6213 14.6176 4.56889C14.7396 4.51648 14.8709 4.48889 15.0036 4.48774C15.1364 4.48659 15.2681 4.51189 15.391 4.56217C15.5139 4.61245 15.6255 4.6867 15.7194 4.78059C15.8133 4.87449 15.8876 4.98614 15.9379 5.10904C15.9881 5.23193 16.0134 5.36361 16.0123 5.49639C16.0111 5.62917 15.9835 5.76039 15.9311 5.88239C15.8787 6.0044 15.8025 6.11474 15.707 6.20699L11.414 10.5L15.707 14.793C15.8945 14.9805 15.9998 15.2348 15.9998 15.5C15.9998 15.7652 15.8945 16.0195 15.707 16.207ZM9.70703 16.207C9.5195 16.3945 9.26519 16.4998 9.00003 16.4998C8.73487 16.4998 8.48056 16.3945 8.29303 16.207L3.29303 11.207C3.10556 11.0195 3.00024 10.7652 3.00024 10.5C3.00024 10.2348 3.10556 9.98052 3.29303 9.79299L8.29303 4.79299C8.48163 4.61083 8.73423 4.51004 8.99643 4.51232C9.25863 4.51459 9.50944 4.61976 9.69485 4.80517C9.88026 4.99058 9.98543 5.24139 9.9877 5.50359C9.98998 5.76579 9.88919 6.01839 9.70703 6.20699L5.41403 10.5L9.70703 14.793C9.8945 14.9805 9.99982 15.2348 9.99982 15.5C9.99982 15.7652 9.8945 16.0195 9.70703 16.207Z" fill="currentColor"/>
                    </svg>                    
                </span>
            </button>
        </div>

        {{-- List item --}}
        <ul class="space-y-4 list-none">
            @can('is-admin')
                <li class="list-none">
                    <x-sidebar.item title="Dashboard" menuName='{{$menuName}}' active='dashboard-admin' route="admin.dashboard">
                        <i class="text-xl text-[#700018] fas fa-chart-simple"></i>
                    </x-sidebar.item>
                </li>
                <li class="list-none">
                    <x-sidebar.item title="Manage Cashflow" menuName='{{$menuName}}' active='manage-cashflow' route="admin.cashflow">
                        <i class="text-lg text-[#700018] fas fa-money-check-dollar"></i>
                    </x-sidebar.item>
                </li>
                <li class="list-none">
                    <x-sidebar.item title="Manage Investment" menuName='{{$menuName}}' active='manage-investment' route="admin.investment">
                        <i class="text-xl text-[#700018] fas fa-money-bill-trend-up"></i>
                    </x-sidebar.item>
                </li>
                <li class="list-none">
                    <x-sidebar.item title="Manage Users" menuName='{{$menuName}}' active='manage-user' route="admin.user">
                        <i class="text-xl text-[#700018] fas fa-users-gear"></i>
                    </x-sidebar.item>
                </li>
            @endcan

            @can('is-user')
                <li class="list-none">
                    <x-sidebar.item title="Dashboard" menuName='{{$menuName}}' active='dashboard' route="dashboard">
                        <i class="text-xl text-[#700018] fas fa-chart-simple"></i>
                    </x-sidebar.item>
                </li>
                <li class="list-none">
                    <x-sidebar.item title="Cashflow Data" menuName='{{$menuName}}' active='cashflow' route="cashflow">
                        <i class="text-lg text-[#700018] fas fa-money-check dollar"></i>
                    </x-sidebar.item>
                </li>
                <li class="list-none">
                    <x-sidebar.item title="Import Cashflow" menuName='{{$menuName}}' active='import' route="import">
                        <i class="text-xl text-[#700018] fas fa-upload"></i>
                    </x-sidebar.item>
                </li>
                <li class="list-none">
                    <x-sidebar.item title="Investment" menuName='{{$menuName}}' active='investment' route="investment">
                        <i class="text-xl text-[#700018] fas fa-money-bill-trend-up"></i>
                    </x-sidebar.item>
                </li>
            @endcan
        </ul>
    </div>
</aside>
