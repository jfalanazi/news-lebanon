<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        $cityKeys = array_keys(Setting::cities());

        return [
            // اختيار المدينة بسهولة — يضبط الطقس والصلاة تلقائيًا
            Action::make('city')
                ->label('🏙️ المدينة: ' . Setting::get('city', 'بيروت'))
                ->icon('heroicon-o-map-pin')
                ->color('primary')
                ->fillForm(fn (): array => ['city' => Setting::get('city', 'بيروت')])
                ->schema([
                    Select::make('city')
                        ->label('اختر المدينة')
                        ->options(array_combine($cityKeys, $cityKeys))
                        ->required()
                        ->helperText('تُضبط مواقيت الصلاة والطقس تلقائيًا حسب المدينة.'),
                ])
                ->action(function (array $data): void {
                    Setting::set('city', $data['city']);
                    Notification::make()
                        ->title('تم تغيير المدينة إلى ' . $data['city'])
                        ->body('سيُطبَّق على الطقس ومواقيت الصلاة في الأعداد الجديدة.')
                        ->success()
                        ->send();
                }),

            CreateAction::make(),
        ];
    }
}
