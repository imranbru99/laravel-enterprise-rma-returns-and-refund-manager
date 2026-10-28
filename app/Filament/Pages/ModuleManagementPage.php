<?php

namespace App\Filament\Pages;

use App\Services\ModuleManager;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ModuleManagementPage extends Page
{
    protected static string|\UnitEnum|null $navigationGroup = 'Settings & Modules';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPuzzlePiece;

    protected string $view = 'filament.pages.module-management';

    protected static ?string $title = 'Business Module Catalog & Toggles';

    protected static ?int $navigationSort = 10;

    public array $modules = [];

    public function mount(ModuleManager $moduleManager): void
    {
        $this->loadModules($moduleManager);
    }

    public function loadModules(ModuleManager $moduleManager): void
    {
        $catalog = $moduleManager->getCatalog();
        $this->modules = [];

        foreach ($catalog as $key => $mod) {
            $this->modules[$key] = array_merge($mod, [
                'key' => $key,
                'is_enabled' => $moduleManager->isEnabled($key),
            ]);
        }
    }

    public function toggleModule(string $key, ModuleManager $moduleManager): void
    {
        $catalog = $moduleManager->getCatalog();
        if (! empty($catalog[$key]['is_core'])) {
            Notification::make()
                ->title('Core Module Required')
                ->body('Core operational modules cannot be disabled.')
                ->warning()
                ->send();

            return;
        }

        $isCurrentlyEnabled = $moduleManager->isEnabled($key);

        if ($isCurrentlyEnabled) {
            $moduleManager->disable($key);
            Notification::make()
                ->title("Module Disabled: {$catalog[$key]['name']}")
                ->body('All menus hidden. Historical records, ledgers, and transactions remain completely preserved.')
                ->info()
                ->send();
        } else {
            // Check dependencies
            $deps = $catalog[$key]['dependencies'] ?? [];
            foreach ($deps as $dep) {
                if (! $moduleManager->isEnabled($dep)) {
                    $depName = $catalog[$dep]['name'] ?? $dep;
                    Notification::make()
                        ->title('Missing Dependency')
                        ->body("Please enable {$depName} before activating {$catalog[$key]['name']}.")
                        ->danger()
                        ->send();

                    return;
                }
            }

            $moduleManager->enable($key);
            Notification::make()
                ->title("Module Activated: {$catalog[$key]['name']}")
                ->body('Features and operational interfaces are now enabled.')
                ->success()
                ->send();
        }

        $this->loadModules($moduleManager);
    }
}
