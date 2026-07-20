@php
    $imgUrl = '/storage/newsletters/edition-' . $issue . '.png?t=' . $ts;
    $liveUrl = route('edition.preview', $editionId, absolute: false);
@endphp
<div style="display:flex;flex-direction:column;gap:16px;padding:6px 0">

    {{-- المعاينة الحيّة: مبدّل بين الصورة (البوستر) وصفحة الويب --}}
    <div style="display:flex;flex-direction:column;gap:8px">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap">
            <div style="display:flex;gap:6px;background:#EFEADF;padding:3px;border-radius:10px">
                <button type="button" id="nashra-tab-poster-{{ $editionId }}"
                    onclick="nashraView('{{ $editionId }}','poster')"
                    style="border:none;padding:6px 14px;border-radius:8px;font-weight:700;font-size:13px;cursor:pointer;background:#146B3F;color:#fff">الصورة</button>
                <button type="button" id="nashra-tab-web-{{ $editionId }}"
                    onclick="nashraView('{{ $editionId }}','web')"
                    style="border:none;padding:6px 14px;border-radius:8px;font-weight:700;font-size:13px;cursor:pointer;background:transparent;color:#20302A">صفحة الويب</button>
            </div>
            <button type="button"
                onclick="nashraReload('{{ $editionId }}')"
                style="background:#EFEADF;color:#20302A;border:none;padding:7px 14px;border-radius:9px;font-weight:700;font-size:13px;cursor:pointer">↻ تحديث</button>
        </div>
        <div style="width:346px;max-width:100%;height:480px;overflow:hidden;border:1px solid #E3DAC4;border-radius:12px;background:#fff;box-shadow:0 6px 20px rgba(0,0,0,.08)">
            <iframe id="nashra-poster-{{ $editionId }}" src="{{ $liveUrl }}" loading="lazy"
                style="width:1080px;height:1500px;border:0;transform:scale(0.32);transform-origin:top right;display:block"></iframe>
            <iframe id="nashra-web-{{ $editionId }}" src="/n/{{ $issue }}" loading="lazy"
                style="width:346px;height:480px;border:0;display:none"></iframe>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
            <a href="/n/{{ $issue }}" target="_blank"
               style="font-size:12px;color:#146B3F;text-decoration:none;font-weight:600">↗ افتح صفحة العدد</a>
            <span style="font-size:12px;color:#66705F">— عدّل ثم اضغط «تحديث».</span>
        </div>
    </div>
    <script>
        function nashraView(id, view){
            var pv=document.getElementById('nashra-poster-'+id), wb=document.getElementById('nashra-web-'+id);
            var tp=document.getElementById('nashra-tab-poster-'+id), tw=document.getElementById('nashra-tab-web-'+id);
            var on='background:#146B3F;color:#fff', off='background:transparent;color:#20302A';
            if(view==='web'){ pv.style.display='none'; wb.style.display='block';
                tw.style.cssText='border:none;padding:6px 14px;border-radius:8px;font-weight:700;font-size:13px;cursor:pointer;'+on;
                tp.style.cssText='border:none;padding:6px 14px;border-radius:8px;font-weight:700;font-size:13px;cursor:pointer;'+off;
            } else { wb.style.display='none'; pv.style.display='block';
                tp.style.cssText='border:none;padding:6px 14px;border-radius:8px;font-weight:700;font-size:13px;cursor:pointer;'+on;
                tw.style.cssText='border:none;padding:6px 14px;border-radius:8px;font-weight:700;font-size:13px;cursor:pointer;'+off;
            }
        }
        function nashraReload(id){
            var pv=document.getElementById('nashra-poster-'+id), wb=document.getElementById('nashra-web-'+id);
            pv.src=pv.src; wb.src=wb.src;
        }
    </script>

    {{-- الصورة النهائية (PNG): أزرار فقط بدون تكرار عرض الصورة --}}
    @if ($hasImage)
        <div style="display:flex;flex-direction:column;gap:10px;border-top:1px solid #E3DAC4;padding-top:14px">
            <div style="font-weight:700;color:#146B3F">الصورة النهائية (PNG) — للطباعة والمشاركة</div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <a href="{{ $imgUrl }}" download="نشرة-لبنان-{{ $issue }}.png"
                   style="display:inline-flex;align-items:center;gap:6px;background:#146B3F;color:#fff;padding:9px 16px;border-radius:10px;text-decoration:none;font-weight:700;font-size:14px">
                    ⬇ تحميل الصورة
                </a>
                <a href="{{ $imgUrl }}" target="_blank"
                   style="display:inline-flex;align-items:center;gap:6px;background:#EFEADF;color:#20302A;padding:9px 16px;border-radius:10px;text-decoration:none;font-weight:700;font-size:14px">
                    ↗ فتح في تبويب
                </a>
            </div>
            <div style="font-size:12px;color:#66705F;line-height:1.7">
                للنشر في واتساب: حمّل الصورة، أرفقها <b>كـ«مستند»</b> (لتفادي الضغط)، ثم الصق التعليق أدناه.
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:8px">
            <label style="font-weight:700;font-size:13px;color:#20302A">تعليق واتساب</label>
            <textarea id="nashra-caption-{{ $issue }}" readonly rows="4"
                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #E3DAC4;background:#FBF9F2;font-family:inherit;font-size:14px;line-height:1.7;direction:rtl;resize:vertical">{{ $caption }}</textarea>
            <button type="button"
                onclick="const t=document.getElementById('nashra-caption-{{ $issue }}');t.select();navigator.clipboard.writeText(t.value);this.textContent='✓ تم النسخ';setTimeout(()=>this.textContent='نسخ التعليق',1500)"
                style="align-self:flex-start;background:#C1A45C;color:#33290F;border:none;padding:9px 18px;border-radius:10px;font-weight:700;font-size:14px;cursor:pointer">
                نسخ التعليق
            </button>
        </div>
    @endif
</div>
