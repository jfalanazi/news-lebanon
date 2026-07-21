<x-filament-widgets::widget>
    <x-filament::section>
        <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
            {{-- مصغّرة آخر بوستر --}}
            <div style="flex:0 0 auto;width:96px;height:130px;border:1px solid #E3DAC4;border-radius:8px;overflow:hidden;background:#FAF7EF">
                @if($img)
                    <img src="{{ $img }}" alt="آخر نشرة" style="width:100%;height:100%;object-fit:cover;object-position:top">
                @else
                    <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#9C8654;font-size:11px;text-align:center;padding:6px">لا صورة بعد</div>
                @endif
            </div>

            <div style="flex:1;min-width:220px">
                @if($latest)
                    <div style="font-weight:800;font-size:18px;color:#0D5A33">العدد {{ $latest->issue_number }} — {{ \App\Support\ArabicDate::full($latest->edition_date) }}</div>
                    <div style="margin-top:4px;font-size:13px;color:#66705F">
                        {{ $latest->status === 'published' ? '✅ منشور' : '📝 مسودة' }} · {{ $latest->news()->count() }} أخبار
                    </div>
                @else
                    <div style="font-weight:700;font-size:16px;color:#66705F">لا توجد أعداد بعد — ابدأ بنشرة اليوم.</div>
                @endif

                {{-- زر ممتلئ واحد: نشرة اليوم إن لم يوجد عدد اليوم، وإلا متابعة التحرير --}}
                <div style="display:flex;gap:8px;margin-top:12px;flex-wrap:wrap">
                    @if(! $hasToday)
                        <x-filament::button wire:click="today" wire:confirm="سيتصل بالذكاء (تكلفة بسيطة جدًا). متابعة؟" icon="heroicon-o-bolt">نشرة اليوم</x-filament::button>
                        @if($editUrl)
                            <x-filament::button tag="a" href="{{ $editUrl }}" outlined icon="heroicon-o-pencil-square">متابعة آخر عدد</x-filament::button>
                        @endif
                    @else
                        @if($editUrl)
                            <x-filament::button tag="a" href="{{ $editUrl }}" icon="heroicon-o-pencil-square">متابعة التحرير</x-filament::button>
                        @endif
                        <x-filament::button wire:click="today" wire:confirm="سيتصل بالذكاء (تكلفة بسيطة جدًا). متابعة؟" outlined icon="heroicon-o-bolt">تحديث نشرة اليوم</x-filament::button>
                    @endif
                    <x-filament::button tag="a" href="{{ $listUrl }}" color="gray" outlined>كل الأعداد</x-filament::button>
                    @if($latest)
                        <x-filament::button tag="a" href="{{ url('/n/' . $latest->issue_number) }}" target="_blank" color="gray" outlined icon="heroicon-o-globe-alt">صفحة العدد</x-filament::button>
                    @endif
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
