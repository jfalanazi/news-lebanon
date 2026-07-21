<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap');
  @include('_fonts')

  :root{
    --ink:#0D5A33; --paper:#FAF7EF; --cedar:#146B3F; --gold:#9C8654;
    --red:#A8342B; --amber:#B8862F; --text:#20302A; --mut:#6B7469; --line:#E7E0CE;
    --display:"Tajawal","IBM Plex Sans Arabic",sans-serif;
    --body:"IBM Plex Sans Arabic","Tajawal",sans-serif;
  }
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:var(--paper);font-family:var(--body);color:var(--text)}
  .page{width:1080px;background:var(--paper);padding-bottom:6px}

  /* ===== الترويسة ===== */
  .mast{background:var(--ink);color:#fff;padding:36px 56px 30px;display:flex;justify-content:space-between;align-items:flex-end;border-bottom:5px solid var(--gold)}
  .mast-title{font-family:var(--display);font-weight:900;font-size:64px;line-height:1}
  .mast-sub{font-weight:500;font-size:22px;color:#CFE3D2;margin-top:10px}
  .mast-l{text-align:left}
  .mast-issue{font-weight:700;font-size:17px;color:#D9C79A}
  .mast-day{font-family:var(--display);font-weight:800;font-size:36px;margin-top:8px;line-height:1}
  .mast-greg{font-weight:500;font-size:20px;color:#EAF3EC;margin-top:8px}
  .mast-hijri{font-weight:400;font-size:16px;color:#A9C6B0;margin-top:4px}

  /* ===== الجسم ===== */
  .wrap{padding:40px 56px 32px}
  .sec{margin-bottom:40px}
  .sec-head{display:flex;align-items:baseline;gap:12px;border-bottom:2px solid var(--ink);padding-bottom:8px;margin-bottom:8px}
  .sec-head h2{font-family:var(--display);font-weight:800;font-size:26px;color:var(--ink)}
  .sec-head .n{font-size:15px;color:var(--mut);font-weight:500}

  /* الأخبار */
  .n-item{display:flex;gap:16px;padding:24px 0;border-bottom:1px solid var(--line)}
  .n-item:last-child{border-bottom:none}
  .n-item.b{border-right:3px solid var(--red);padding-right:16px}
  .n-item.m{border-right:3px solid var(--amber);padding-right:16px}
  .n-num{flex:0 0 auto;width:48px;text-align:center;font-family:var(--display);font-weight:800;font-size:28px;color:#8C7743;padding-top:2px}
  .n-body{flex:1;min-width:0}
  .n-meta{display:flex;align-items:center;gap:10px;margin-bottom:6px}
  .n-tag{font-family:var(--display);font-weight:800;font-size:14px;color:#fff;padding:2px 12px;border-radius:4px}
  .n-tag.b{background:var(--red)} .n-tag.m{background:var(--amber)}
  .n-cat{font-weight:600;font-size:17px;color:var(--gold);overflow-wrap:anywhere}
  .n-title{font-weight:700;font-size:30px;line-height:1.4;color:var(--text);overflow-wrap:anywhere;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
  .n-item.b .n-num{color:var(--red)}
  .n-excerpt{font-weight:400;font-size:20px;line-height:1.55;color:var(--mut);margin-top:8px;overflow-wrap:anywhere;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}

  .n-more{padding:16px 0 0;font-weight:600;font-size:18px;color:var(--mut)}
  .w-hi,.w-lo,.w-day .t,.p-time,.n-num,.mast-issue{font-variant-numeric:tabular-nums}

  /* عمودان */
  .two{display:grid;grid-template-columns:1fr 1fr;gap:44px}

  /* الطقس */
  .w-main{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-top:6px}
  .w-ico{width:58px;height:58px}
  .w-cond{font-weight:600;font-size:19px;color:var(--mut)}
  .w-temps{text-align:left}
  .w-hi{font-family:var(--display);font-weight:800;font-size:24px;color:var(--red)}
  .w-lo{font-family:var(--display);font-weight:800;font-size:24px;color:var(--cedar)}
  .w-now{font-size:16px;color:var(--mut);margin-top:8px}
  .w-days{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-top:16px;padding-top:14px;border-top:1px solid var(--line)}
  .w-day{text-align:center}
  .w-day .d{font-weight:700;font-size:16px}
  .w-day .ic{width:34px;height:34px;margin:3px auto}
  .w-day .t{font-weight:500;font-size:15px;color:var(--mut)}

  /* الصلاة */
  .p-note{font-size:14px;color:var(--mut);margin:2px 0 12px}
  .p-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px 8px;margin-top:6px}
  .p-cell{text-align:center}
  .p-ic{width:26px;height:26px;margin:0 auto 5px}
  .p-name{font-weight:700;font-size:17px;color:var(--cedar)}
  .p-time{font-family:var(--display);font-weight:800;font-size:21px;color:var(--ink);margin-top:1px}

  /* توصيات / فعاليات */
  .li{display:flex;gap:14px;align-items:flex-start;padding:14px 0;border-bottom:1px solid var(--line)}
  .li:last-child{border-bottom:none}
  .li-ic{flex:0 0 auto;width:34px;height:34px;display:flex;align-items:center;justify-content:center;color:var(--cedar)}
  .li-b{flex:1;min-width:0}
  .li-label{font-weight:600;font-size:15px;color:var(--gold)}
  .li-name{font-family:var(--display);font-weight:800;font-size:22px;color:var(--ink);line-height:1.25;margin-top:2px;overflow-wrap:anywhere}
  .li-badge{display:inline-block;margin-top:5px;font-weight:600;font-size:15px;color:var(--cedar)}

  /* التذييل */
  .foot{margin-top:8px;padding:22px 56px 30px;border-top:2px solid var(--ink);display:flex;align-items:center;gap:24px}
  .foot .qr{flex:0 0 auto;width:104px;height:104px;padding:8px;border:1px solid var(--line);border-radius:8px;background:#fff}
  .foot .qr img{width:100%;height:100%;display:block}
  .foot-t{flex:1;text-align:right}
  .foot-scan{font-weight:600;font-size:19px;color:var(--text)}
  .foot-brand{font-size:16px;color:var(--mut);margin-top:3px}
  .foot-quote{font-weight:500;font-size:20px;color:var(--mut);margin-top:10px;line-height:1.55}
</style>
</head>
<body>
@include('_nashra_icons')
<div class="page">

  <div class="mast">
    <div class="mast-r">
      <div class="mast-title">نشرة لبنان</div>
      <div class="mast-sub">الموجز اليومي · مختارات اليوم</div>
    </div>
    <div class="mast-l">
      <div class="mast-issue">العدد {{ $issue }}</div>
      <div class="mast-day">{{ $day }}</div>
      <div class="mast-greg">{{ $greg }}</div>
      @if($hijri)<div class="mast-hijri">{{ $hijri }}</div>@endif
    </div>
  </div>

  <div class="wrap">

    {{-- الأخبار --}}
    <div class="sec">
      <div class="sec-head"><h2>أهم الأخبار</h2></div>
      @php $newsShow = collect($news)->take(6); $extraNews = max(count($news) - 6, 0); @endphp
      @foreach($newsShow as $i => $n)
        @php $pr = $n['priority'] ?? 'normal'; $cls = $pr==='breaking'?'b':($pr==='important'?'m':''); @endphp
        <div class="n-item {{ $cls }}">
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

    {{-- الطقس + الصلاة --}}
    <div class="two">
      <div class="sec">
        <div class="sec-head"><h2>الطقس اليوم</h2></div>
        <div class="w-main">
          <svg class="w-ico" viewBox="0 0 48 48">{!! nashra_weather_icon($weather['icon']) !!}</svg>
          <div class="w-temps">
            <div class="w-cond">{{ $weather['cond'] }}</div>
            <div class="w-hi">العظمى {{ $weather['hi'] }}°</div>
            <div class="w-lo">الصغرى {{ $weather['lo'] }}°</div>
          </div>
        </div>
        <div class="w-now">الآن {{ $weather['now'] }}°</div>
        <div class="w-days">
          @foreach($weather['days'] as $d)
          <div class="w-day"><div class="d">{{ $d['d'] }}</div><svg class="ic" viewBox="0 0 48 48">{!! nashra_weather_icon($d['icon']) !!}</svg><div class="t">{{ $d['hi'] }}°/{{ $d['lo'] }}°</div></div>
          @endforeach
        </div>
      </div>

      <div class="sec">
        <div class="sec-head"><h2>مواقيت الصلاة</h2></div>
        <div class="p-note">بتوقيت دار الفتوى</div>
        <div class="p-grid">
          @foreach($prayers as $name => $time)
          <div class="p-cell"><svg class="p-ic" viewBox="0 0 30 30">{!! nashra_prayer_icon($name) !!}</svg><div class="p-name">{{ $name }}</div><div class="p-time">{{ $time }}</div></div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- توصيات + فعاليات --}}
    <div class="two">
      @if(!empty($recos))
      <div class="sec">
        <div class="sec-head"><h2>توصيات اليوم</h2></div>
        @foreach($recos as $r)
        <div class="li">
          <div class="li-ic"><svg width="26" height="26" viewBox="0 0 30 30">{!! nashra_reco_icon($r['type']) !!}</svg></div>
          <div class="li-b"><div class="li-label">{{ nashra_reco_label($r['type']) }}</div><div class="li-name">{{ $r['name'] }}</div>@if(!empty($r['area']))<span class="li-badge">◍ {{ $r['area'] }}</span>@endif</div>
        </div>
        @endforeach
      </div>
      @endif

      @if(!empty($events))
      <div class="sec">
        <div class="sec-head"><h2>فعاليات لبنان</h2></div>
        @foreach($events as $e)
        <div class="li">
          <div class="li-ic"><svg width="26" height="26" viewBox="0 0 30 30">{!! nashra_reco_icon('event') !!}</svg></div>
          <div class="li-b"><div class="li-label">{{ $e['category'] }}</div><div class="li-name">{{ $e['title'] }}</div>@if(!empty($e['range']))<span class="li-badge">🗓️ {{ $e['range'] }}</span>@endif</div>
        </div>
        @endforeach
      </div>
      @endif
    </div>

  </div>

  <div class="foot">
    @if(!empty($qrUrl))<div class="qr"><img src="{{ $qrUrl }}" alt="QR"></div>@endif
    <div class="foot-t">
      @if(!empty($qrUrl))<div class="foot-scan">امسح للتفاصيل والروابط</div><div class="foot-brand">نشرة لبنان اليومية</div>@endif
      @if(!empty($quote))<div class="foot-quote">« {{ $quote }} »</div>@endif
    </div>
  </div>

</div>
</body>
</html>
