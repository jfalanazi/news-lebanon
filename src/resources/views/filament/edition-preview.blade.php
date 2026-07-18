@php
    $imgUrl = '/storage/newsletters/edition-' . $issue . '.png?t=' . $ts;
@endphp
<div style="display:flex;flex-direction:column;gap:14px;padding:6px 0">
    <div style="display:flex;gap:18px;flex-wrap:wrap;align-items:flex-start">
        <a href="{{ $imgUrl }}" target="_blank" style="flex:0 0 auto">
            <img src="{{ $imgUrl }}" alt="معاينة النشرة"
                 style="width:220px;border-radius:12px;border:1px solid #E3DAC4;box-shadow:0 6px 20px rgba(0,0,0,.10)">
        </a>
        <div style="flex:1;min-width:240px;display:flex;flex-direction:column;gap:10px">
            <div style="font-weight:700;color:#146B3F">الصورة جاهزة</div>
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
</div>
