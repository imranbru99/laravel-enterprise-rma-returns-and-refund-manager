<x-filament-panels::page>
    <div class="mb-6 p-4 rounded-xl border border-indigo-100 bg-indigo-50/50 dark:bg-gray-800 dark:border-gray-700">
        <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <x-filament::icon icon="heroicon-o-adjustments-horizontal" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
            Configurable Enterprise SaaS Modules
        </h3>
        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
            Toggle features on or off according to your operational model (Retail, E-commerce, 3PL, Electronics Repair, Manufacturer).
            Disabling a module safely suspends its workflows while keeping historical records and financial ledgers 100% intact.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($modules as $key => $module)
            <div class="rounded-xl border p-5 flex flex-col justify-between transition duration-200
                {{ $module['is_enabled'] ? 'bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 shadow-sm' : 'bg-gray-50/60 dark:bg-gray-950/40 border-gray-200 dark:border-gray-800 opacity-80' }}">
                <div>
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="p-2 rounded-lg {{ $module['is_enabled'] ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/60 dark:text-indigo-400' : 'bg-gray-200 text-gray-500 dark:bg-gray-800 dark:text-gray-400' }}">
                                <x-filament::icon :icon="$module['icon'] ?? 'heroicon-o-cube'" class="w-5 h-5" />
                            </span>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white leading-tight">
                                    {{ $module['name'] }}
                                </h4>
                                <span class="text-[10px] uppercase font-semibold text-gray-500 tracking-wider">
                                    {{ $module['category'] }}
                                </span>
                            </div>
                        </div>

                        @if(!empty($module['is_core']))
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">
                                CORE
                            </span>
                        @elseif($module['is_enabled'])
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">
                                ACTIVE
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                DISABLED
                            </span>
                        @endif
                    </div>

                    <p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-3 mb-4">
                        {{ $module['description'] }}
                    </p>

                    @if(!empty($module['dependencies']))
                        <div class="mb-4">
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Requires:</span>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($module['dependencies'] as $dep)
                                    <span class="px-1.5 py-0.5 text-[10px] rounded bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 font-mono">
                                        {{ $dep }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <span class="text-xs text-gray-500 font-mono">{{ $key }}</span>

                    @if(empty($module['is_core']))
                        <button
                            type="button"
                            wire:click="toggleModule('{{ $key }}')"
                            wire:loading.attr="disabled"
                            class="px-3 py-1.5 text-xs font-semibold rounded-lg shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2
                                {{ $module['is_enabled'] ? 'bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-950/40 dark:text-red-400 focus:ring-red-500' : 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500' }}">
                            {{ $module['is_enabled'] ? 'Disable Module' : 'Enable Module' }}
                        </button>
                    @else
                        <span class="text-xs text-gray-400 italic">Always Active</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
