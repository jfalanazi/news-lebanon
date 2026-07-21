<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap');
  @include('_fonts')

  :root{
    --ink:#0D5A33; --deep:#0B4A2B; --paper:#FAF7EF; --cedar:#146B3F; --gold:#9C8654; --goldSoft:#C9B47E;
    --red:#A8342B; --amber:#B8862F; --text:#20302A; --mut:#6B7469; --line:#E7E0CE;
    --display:"Tajawal","IBM Plex Sans Arabic",sans-serif;
    --body:"IBM Plex Sans Arabic","Tajawal",sans-serif;
  }
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:var(--paper);font-family:var(--body);color:var(--text)}
  .page{width:1080px;height:1350px;overflow:hidden;display:flex;flex-direction:column;background:var(--paper)}

  /* شريط السدو — لمسة سعودية تؤطّر النشرة أعلى وأسفل */
  .sadu{height:14px;flex:0 0 auto;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='14'%3E%3Crect width='40' height='14' fill='%230B4A2B'/%3E%3Cpath d='M0 7 L10 1.5 L20 7 L10 12.5 Z' fill='%23C8A24B'/%3E%3Cpath d='M20 7 L30 1.5 L40 7 L30 12.5 Z' fill='%23F3EAD3'/%3E%3Cpath d='M10 7 L12.5 5.5 L15 7 L12.5 8.5 Z' fill='%230B4A2B'/%3E%3Cpath d='M30 7 L32.5 5.5 L35 7 L32.5 8.5 Z' fill='%230B4A2B'/%3E%3C/svg%3E");background-repeat:repeat-x;background-size:40px 14px}

  /* ===== الترويسة ===== */
  .mast{position:relative;overflow:hidden;background:var(--ink);color:#fff;padding:26px 56px 24px;display:flex;justify-content:space-between;align-items:center;flex:0 0 auto}
  .mast-cedar{position:absolute;left:40%;bottom:-16px;width:180px;height:180px;opacity:.07;pointer-events:none}
  .mast-title{font-family:var(--display);font-weight:900;font-size:56px;line-height:1}
  .mast-sub{font-weight:500;font-size:19px;color:#CFE3D2;margin-top:8px}
  .mast-l{display:flex;align-items:center;gap:22px}
  .mast-dates{text-align:left}
  .mast-day{font-family:var(--display);font-weight:800;font-size:30px;line-height:1}
  .mast-greg{font-weight:500;font-size:18px;color:#EAF3EC;margin-top:6px}
  .mast-hijri{font-weight:400;font-size:14px;color:#A9C6B0;margin-top:3px}
  /* ختم العدد — طابع أرشيفي دائري */
  .seal{width:88px;height:88px;border:2px solid var(--goldSoft);border-radius:50%;display:flex;flex-direction:column;align-items:center;justify-content:center;flex:0 0 auto}
  .seal .s1{font-size:14px;font-weight:600;color:#D9C79A}
  .seal .s2{font-family:var(--display);font-weight:900;font-size:32px;line-height:1.1;color:#fff}
  .mrule{height:7px;flex:0 0 auto;background:var(--paper);border-top:3px solid var(--gold);border-bottom:1px solid var(--gold)}
  .mrule-b{height:7px;flex:0 0 auto;background:var(--paper);border-top:1px solid var(--gold);border-bottom:3px solid var(--gold)}

  /* ===== الجسم: تدفق طبيعي ليقيس سكربت الملاءمة الارتفاع الحقيقي ===== */
  .wrap{flex:1 1 auto;min-height:0;overflow:hidden;padding:28px 56px 18px}
  .sec-head{display:flex;align-items:center;gap:10px;border-bottom:2px solid var(--ink);padding-bottom:8px;margin-bottom:4px}
  .sec-head h2{font-family:var(--display);font-weight:800;font-size:25px;color:var(--ink)}
  .sec-head svg{width:22px;height:22px}

  /* الأخبار — الخبر الأول مانشيت */
  .news{margin-bottom:18px}
  .n-item{display:flex;gap:16px;padding:17px 0;border-bottom:1px solid var(--line)}
  .n-item:last-of-type{border-bottom:none}
  .n-item.b{border-right:3px solid var(--red);padding-right:16px}
  .n-item.m{border-right:3px solid var(--amber);padding-right:16px}
  .n-num{flex:0 0 auto;width:44px;text-align:center;font-family:var(--display);font-weight:800;font-size:26px;color:#8C7743;padding-top:2px}
  .n-body{flex:1;min-width:0}
  .n-meta{display:flex;align-items:center;gap:10px;margin-bottom:5px}
  .n-tag{font-family:var(--display);font-weight:800;font-size:14px;color:#fff;padding:2px 12px;border-radius:4px}
  .n-tag.b{background:var(--red)} .n-tag.m{background:var(--amber)}
  .n-cat{font-weight:600;font-size:16px;color:var(--gold);overflow-wrap:anywhere}
  .n-title{font-weight:700;font-size:27px;line-height:1.4;color:var(--text);overflow-wrap:anywhere;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
  .n-item.b .n-num{color:var(--red)}
  .n-excerpt{font-weight:400;font-size:19px;line-height:1.55;color:var(--mut);margin-top:6px;overflow-wrap:anywhere;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
  .n-item.lead .n-title{font-size:37px;line-height:1.32;-webkit-line-clamp:3}
  .n-item.lead .n-excerpt{font-size:21px}
  .n-item.lead .n-num{font-size:30px;padding-top:6px}
  .n-more{padding:12px 0 0;font-weight:600;font-size:17px;color:var(--mut)}
  .w-hi,.w-lo,.w-day .t,.p-time,.n-num{font-variant-numeric:tabular-nums}

  /* الأحزمة المعلوماتية — بطاقات أفقية ممتدة كثيفة */
  .band{background:#FFFFFF;border:1px solid var(--line);border-radius:12px;padding:16px 26px;margin-bottom:13px}
  .band-head{display:flex;align-items:center;gap:8px;margin-bottom:10px}
  .band-head h3{font-family:var(--display);font-weight:800;font-size:19px;color:var(--ink)}
  .band-head .note{font-size:13px;color:var(--mut);font-weight:500}
  .band-head svg{width:20px;height:20px}

  /* حزام الطقس: ملخص + توقعات في صف واحد */
  .w-row{display:flex;align-items:center;gap:26px}
  .w-sum{display:flex;align-items:center;gap:16px;flex:0 0 auto}
  .w-ico{width:60px;height:60px}
  .w-cond{font-weight:600;font-size:17px;color:var(--mut)}
  .chip-now{display:inline-block;margin-top:5px;border:1px solid var(--line);border-radius:999px;padding:2px 12px;font-size:14px;color:var(--mut);font-weight:600}
  .w-hilo{display:flex;flex-direction:column;gap:2px;flex:0 0 auto}
  .w-hi{font-family:var(--display);font-weight:800;font-size:24px;color:var(--red)}
  .w-lo{font-family:var(--display);font-weight:800;font-size:24px;color:var(--cedar)}
  .w-days{flex:1;display:flex;justify-content:space-around;border-right:1px solid var(--line);padding-right:26px;margin-right:4px}
  .w-day{text-align:center}
  .w-day .d{font-weight:700;font-size:15px}
  .w-day .ic{width:30px;height:30px;margin:2px auto}
  .w-day .t{font-weight:500;font-size:14px;color:var(--mut)}

  /* حزام الصلاة: 6 خلايا في صف واحد */
  .p-row{display:flex;justify-content:space-between}
  .p-cell{flex:1;text-align:center}
  .p-ic{width:24px;height:24px;margin:0 auto 3px}
  .p-name{font-weight:700;font-size:16px;color:var(--cedar)}
  .p-time{font-family:var(--display);font-weight:800;font-size:20px;color:var(--ink)}

  /* حزام المختارات: بطاقات أفقية للتوصيات والفعاليات معًا */
  .picks{display:flex;flex-wrap:wrap;gap:11px}
  .pick{flex:1 1 calc(50% - 11px);min-width:300px;display:flex;gap:12px;align-items:center;background:var(--paper);border:1px solid var(--line);border-radius:10px;padding:11px 16px}
  .pick svg{flex:0 0 auto;width:30px;height:30px}
  .pick-b{flex:1;min-width:0}
  .pick-label{font-weight:600;font-size:13px;color:var(--gold)}
  .pick-name{font-family:var(--display);font-weight:800;font-size:19px;color:var(--ink);line-height:1.25;overflow-wrap:anywhere}
  .pick-meta{font-weight:600;font-size:14px;color:var(--cedar);margin-top:1px}

  /* التذييل */
  .foot{flex:0 0 auto;padding:16px 56px;border-top:2px solid var(--ink);display:flex;align-items:center;gap:22px;background:var(--paper)}
  .qr-wrap{flex:0 0 auto;text-align:center}
  .foot .qr{width:96px;height:96px;padding:7px;border:1.5px solid var(--goldSoft);border-radius:8px;background:#fff;margin:0 auto}
  .foot .qr img{width:100%;height:100%;display:block}
  .qr-cap{font-size:12px;color:var(--mut);margin-top:5px;direction:ltr}
  .foot-t{flex:1;text-align:right}
  .foot-scan{font-weight:600;font-size:18px;color:var(--text)}
  .foot-brand{font-size:15px;color:var(--mut);margin-top:2px}
  .foot-quote{position:relative;font-weight:500;font-size:20px;color:var(--mut);margin-top:8px;line-height:1.5;padding-right:30px}
  .foot-quote::before{content:"«";position:absolute;right:0;top:-16px;font-family:var(--display);font-size:52px;font-weight:900;color:var(--gold);opacity:.3}
</style>
</head>
<body>
@include('_nashra_icons')
<div class="page">

  <div class="sadu"></div>

  <div class="mast">
    <svg class="mast-cedar" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" fill="#FFFFFF">
      <path d="M50 4 C44 13 36 18 27 21 L44 21 C38 29 30 34 20 37 L45 37 C38 45 29 50 16 54 L46 54 L46 66 L40 70 L60 70 L54 66 L54 54 L84 54 C71 50 62 45 55 37 L80 37 C70 34 62 29 56 21 L73 21 C64 18 56 13 50 4 Z"/>
    </svg>
    <div class="mast-r">
      <div class="mast-title">نشرة لبنان</div>
      <div class="mast-sub">الموجز اليومي · مختارات اليوم</div>
    </div>
    <div class="mast-l">
      <div class="mast-dates">
        <div class="mast-day">{{ $day }}</div>
        <div class="mast-greg">{{ $greg }}</div>
        @if($hijri)<div class="mast-hijri">{{ $hijri }}</div>@endif
      </div>
      <div class="seal"><div class="s1">العدد</div><div class="s2">{{ $issue }}</div></div>
    </div>
  </div>
  <div class="mrule"></div>

  <div class="wrap">

    {{-- الأخبار — الخبر الأول مانشيت أكبر --}}
    <div class="news">
      <div class="sec-head">
        <svg viewBox="0 0 24 24" fill="none" stroke="#9C8654" stroke-width="1.8"><rect x="3.5" y="5" width="14" height="14.5" rx="1.5"/><path d="M17.5 8.5 H19 a1.5 1.5 0 0 1 1.5 1.5 V17.5 a2 2 0 0 1 -2 2 H5.5"/><line x1="6.5" y1="9" x2="14.5" y2="9"/><line x1="6.5" y1="12.5" x2="14.5" y2="12.5"/><line x1="6.5" y1="16" x2="11.5" y2="16"/></svg>
        <h2>أهم الأخبار</h2>
      </div>
      @php $newsShow = collect($news)->take(6); $extraNews = max(count($news) - 6, 0); @endphp
      @foreach($newsShow as $i => $n)
        @php $pr = $n['priority'] ?? 'normal'; $cls = $pr==='breaking'?'b':($pr==='important'?'m':''); @endphp
        <div class="n-item {{ $cls }} {{ $i === 0 ? 'lead' : '' }}">
          <div class="n-num">{{ $i + 1 }}</div>
          <div class="n-body">
            <div class="n-meta">
              @if($pr==='breaking')<span class="n-tag b">عاجل</span>
              @elseif($pr==='important')<span class="n-tag m">مهم</span>@endif
              <span class="n-cat">{{ $n['category'] }}@if(!empty($n['source_name'])) — {{ $n['source_name'] }}@endif</span>
            </div>
            <div class="n-title">{{ $n['title'] }}</div>
            @if(!empty($n['excerpt']))<div class="n-excerpt">{{ $n['excerpt'] }}</div>@endif
          </div>
        </div>
      @endforeach
      @if($extraNews > 0)<div class="n-more">+ {{ $extraNews }} أخبار إضافية على صفحة العدد — امسح الباركود</div>@endif
    </div>

    {{-- حزام الطقس --}}
    <div class="band">
      <div class="band-head">
        <svg viewBox="0 0 48 48">{!! nashra_weather_icon('sun') !!}</svg>
        <h3>الطقس اليوم</h3>
      </div>
      <div class="w-row">
        <div class="w-sum">
          <svg class="w-ico" viewBox="0 0 48 48">{!! nashra_weather_icon($weather['icon']) !!}</svg>
          <div>
            <div class="w-cond">{{ $weather['cond'] }}</div>
            <span class="chip-now">الآن {{ $weather['now'] }}°</span>
          </div>
        </div>
        <div class="w-hilo">
          <div class="w-hi">العظمى {{ $weather['hi'] }}°</div>
          <div class="w-lo">الصغرى {{ $weather['lo'] }}°</div>
        </div>
        <div class="w-days">
          @foreach($weather['days'] as $d)
          <div class="w-day"><div class="d">{{ $d['d'] }}</div><svg class="ic" viewBox="0 0 48 48">{!! nashra_weather_icon($d['icon']) !!}</svg><div class="t" dir="ltr">{{ $d['hi'] }}° / {{ $d['lo'] }}°</div></div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- حزام الصلاة: صف واحد ممتد --}}
    <div class="band">
      <div class="band-head">
        <svg viewBox="0 0 30 30">{!! nashra_prayer_icon('الفجر') !!}</svg>
        <h3>مواقيت الصلاة</h3>
        <span class="note">بتوقيت دار الفتوى</span>
      </div>
      <div class="p-row">
        @foreach(['الفجر','الشروق','الظهر','العصر','المغرب','العشاء'] as $name)
        @if(!empty($prayers[$name]))
        <div class="p-cell"><svg class="p-ic" viewBox="0 0 30 30">{!! nashra_prayer_icon($name) !!}</svg><div class="p-name">{{ $name }}</div><div class="p-time">{{ $prayers[$name] }}</div></div>
        @endif
        @endforeach
      </div>
    </div>

    {{-- حزام المختارات: توصيات + فعاليات في بطاقات أفقية --}}
    @if(!empty($recos) || !empty($events))
    <div class="band">
      <div class="band-head">
        <svg viewBox="0 0 30 30">{!! nashra_reco_icon('other') !!}</svg>
        <h3>مختارات وفعاليات</h3>
      </div>
      <div class="picks">
        @foreach($recos as $r)
        <div class="pick">
          <svg viewBox="0 0 30 30">{!! nashra_reco_icon($r['type']) !!}</svg>
          <div class="pick-b">
            <div class="pick-label">{{ nashra_reco_label($r['type']) }}</div>
            <div class="pick-name">{{ $r['name'] }}</div>
            @if(!empty($r['area']))<div class="pick-meta">◍ {{ $r['area'] }}</div>@endif
          </div>
        </div>
        @endforeach
        @foreach($events as $e)
        <div class="pick">
          <svg viewBox="0 0 30 30">{!! nashra_reco_icon('event') !!}</svg>
          <div class="pick-b">
            <div class="pick-label">{{ $e['category'] ?: 'فعالية' }}</div>
            <div class="pick-name">{{ $e['title'] }}</div>
            @if(!empty($e['range']))<div class="pick-meta">🗓️ {{ $e['range'] }}</div>@endif
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @endif

  </div>

  <div class="foot">
    @if(!empty($qrUrl))
    <div class="qr-wrap">
      <div class="qr"><img src="{{ $qrUrl }}" alt="QR"></div>
      <div class="qr-cap">{{ str_replace(['https://', 'http://'], '', url('/n/' . $issue)) }}</div>
    </div>
    @endif
    <div class="foot-t">
      @if(!empty($qrUrl))<div class="foot-scan">امسح للتفاصيل والروابط</div><div class="foot-brand">نشرة لبنان اليومية</div>@endif
      @if(!empty($quote))<div class="foot-quote">{{ $quote }}</div>@endif
    </div>
  </div>
  <div class="mrule-b"></div>
  <div class="sadu"></div>

</div>
<script>
  // ملاءمة تلقائية: مقاس ثابت 1080×1350 — تصغير عند الفيض وتكبير حتى 1.3× عند الفراغ
  (function () {
    var wrap = document.querySelector('.wrap');
    if (!wrap) return;
    var fits = function () { return wrap.scrollHeight <= wrap.clientHeight + 2; };
    var z = 1, guard = 0;
    while (!fits() && guard++ < 30) { z -= 0.04; wrap.style.zoom = z.toFixed(3); }
    if (fits()) {
      guard = 0;
      while (guard++ < 14) {
        var t = Math.min(z + 0.04, 1.3);
        if (t <= z) break;
        wrap.style.zoom = t.toFixed(3);
        if (!fits()) { wrap.style.zoom = z.toFixed(3); break; }
        z = t;
      }
    }
  })();
</script>
</body>
</html>
