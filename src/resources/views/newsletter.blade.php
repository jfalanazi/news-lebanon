<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<style>
  @include('_fonts')

  :root{
    --ink:#0D5A33; --ink2:#11713F; --paper:#F4F0E5; --cedar:#146B3F; --cedarDeep:#0E5230;
    --gold:#9C8654; --goldSoft:#C9B47E; --saffron:#C1A45C; --red:#A8342B;
    --text:#20302A; --mut:#66705F; --line:#E3DAC4; --block:#F9F5EA; --item:#FDFBF3;
    --display:"Al-Awwal","Tajawal","IBM Plex Sans Arabic",sans-serif;
    --body:"Al-Awwal","IBM Plex Sans Arabic",sans-serif;
  }
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:var(--paper);font-family:var(--body);color:var(--text)}
  .page{width:1080px;background:var(--paper)}

  /* ============ الترويسة ============ */
  .header{position:relative;background:var(--ink);padding:26px 56px 30px;overflow:hidden;border-top:7px solid var(--ink2)}
  .header .palm{position:absolute;left:70px;top:20px;opacity:.07}
  .h-row{display:flex;justify-content:space-between;align-items:flex-start}
  .h-right{text-align:right}
  .h-title{font-family:var(--display);font-weight:900;font-size:50px;color:#fff;line-height:1}
  .h-sub{font-weight:500;font-size:22px;color:#CFE3D2;margin-top:8px}
  .h-chain{margin-top:12px}
  .h-left{text-align:left}
  .h-issue{font-weight:700;font-size:19px;color:var(--goldSoft);letter-spacing:1px}
  .h-day{font-family:var(--display);font-weight:900;font-size:40px;color:#fff;margin-top:10px;line-height:1}
  .h-greg{font-weight:600;font-size:24px;color:#EAF3EC;margin-top:8px}
  .h-hijri{font-weight:400;font-size:19px;color:#A9C6B0;margin-top:5px}
  .h-border{margin-top:22px}

  /* ============ الجسم ============ */
  .body{padding:26px 22px}

  /* إطار موحّد بزوايا وريدات */
  .block{position:relative;background:var(--block);border-radius:15px;padding:2px}
  .block-inner{border:2px solid var(--cedar);border-radius:11px;margin:7px}
  .block-inner2{border:1px solid var(--goldSoft);border-radius:8px;padding:26px 30px}
  .corner{position:absolute;width:26px;height:26px}
  .corner.tl{top:0;right:0} .corner.tr{top:0;left:0}
  .corner.bl{bottom:0;right:0} .corner.br{bottom:0;left:0}

  .block-title{display:flex;align-items:center;justify-content:center;gap:16px;margin-bottom:6px}
  .block-title h2{font-family:var(--display);font-weight:800;font-size:28px;color:var(--cedarDeep)}
  .block-title .dia{width:16px;height:16px}
  .news-date{position:absolute;top:30px;left:34px;font-weight:500;font-size:19px;color:var(--mut)}

  /* عناصر الأخبار */
  .news-item{position:relative;display:flex;gap:18px;padding:16px 14px 18px;align-items:flex-start;border-radius:10px}
  .news-item .num{flex:0 0 auto;width:48px;height:48px;border-radius:50%;background:#E4EDE2;border:1px solid var(--gold);
    display:flex;align-items:center;justify-content:center;font-family:var(--display);font-weight:800;font-size:27px;color:var(--cedar)}
  .news-body{flex:1;padding-top:2px}
  .news-top{display:flex;align-items:center;gap:10px;margin-bottom:6px}
  .news-cat{font-weight:700;font-size:20px;color:var(--gold);overflow-wrap:anywhere}
  .news-badge{font-family:var(--display);font-weight:800;font-size:17px;color:#fff;padding:3px 12px;border-radius:6px}
  .news-title{font-weight:700;font-size:34px;line-height:1.25;color:var(--text);overflow-wrap:anywhere;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
  .news-excerpt{font-weight:400;font-size:24px;line-height:1.4;color:var(--mut);margin-top:6px;overflow-wrap:anywhere;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}

  .news-item.m:before,.news-item.b:before{content:"";position:absolute;top:6px;bottom:8px;right:-2px;width:5px;border-radius:3px}
  .news-item.b{background:rgba(168,52,43,.05)} .news-item.b:before{background:var(--red)}
  .news-item.m{background:rgba(193,164,92,.08)} .news-item.m:before{background:var(--saffron)}
  .news-item.b .num{background:#F6E3E1;border-color:var(--red);color:var(--red)}
  .news-item.m .num{background:#F1EAD5;border-color:var(--saffron);color:var(--gold)}
  .news-item.b .news-cat{color:var(--red)}
  .news-item.b .news-title{color:#7A1F19}
  .badge-b{background:var(--red)} .badge-m{background:var(--saffron)}

  .divider{display:flex;align-items:center;gap:14px;padding:0 30px}
  .divider .ln{flex:1;height:1px;background:var(--line)}
  .divider .dd{width:20px;height:14px}

  /* ============ صف بعمودين ============ */
  .two-col{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px}

  /* عناوين البلوكات الفرعية */
  .sub-title{display:flex;align-items:center;justify-content:center;gap:14px;margin-bottom:4px}
  .sub-title h3{font-family:var(--display);font-weight:800;font-size:24px;color:var(--cedarDeep)}
  .sub-title .dia{width:14px;height:14px}
  .sub-note{text-align:center;font-weight:500;font-size:14px;color:var(--mut);margin-bottom:14px}

  /* الطقس */
  .weather-main{display:flex;align-items:center;gap:16px;justify-content:space-between;margin-top:6px}
  .weather-temps{text-align:right}
  .weather-cond{font-weight:600;font-size:20px;color:var(--mut);margin-bottom:6px}
  .weather-hi{font-family:var(--display);font-weight:800;font-size:26px;color:var(--red)}
  .weather-lo{font-family:var(--display);font-weight:800;font-size:26px;color:var(--cedar)}
  .weather-now{font-weight:500;font-size:17px;color:var(--mut);text-align:center;margin-top:4px}
  .weather-days{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-top:14px;padding-top:12px;border-top:1px dashed var(--goldSoft)}
  .wday{text-align:center}
  .wday .d{font-weight:700;font-size:18px;color:var(--text)}
  .wday .t{font-weight:600;font-size:17px;color:var(--mut);margin-top:4px}
  .wicon{width:44px;height:44px;margin:0 auto}
  .wicon.sm{width:34px;height:34px}

  /* الصلاة */
  .prayer-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:0;margin-top:8px}
  .pcell{text-align:center;padding:12px 4px;position:relative}
  .pcell:not(:nth-child(3n)):after{content:"";position:absolute;top:14px;bottom:14px;left:0;width:1px;background:#E0D4B4}
  .pcell .picon{width:30px;height:30px;margin:0 auto 6px}
  .pcell .pname{font-weight:700;font-size:19px;color:var(--cedar)}
  .pcell .ptime{font-family:var(--display);font-weight:800;font-size:23px;color:var(--cedarDeep);margin-top:2px}

  /* بطاقات التوصيات/الفعاليات */
  .cards{display:flex;flex-direction:column;gap:11px;margin-top:6px}
  .card{position:relative;display:flex;gap:14px;align-items:flex-start;background:var(--item);border:1px solid #E4D9BC;border-radius:11px;padding:14px 16px 14px 14px}
  .card:before{content:"";position:absolute;top:0;bottom:0;right:0;width:5px;background:var(--cedar);border-radius:0 2.5px 2.5px 0}
  .card .cicon{flex:0 0 auto;width:38px;height:38px;border-radius:50%;background:#E4EDE2;border:1px solid var(--gold);display:flex;align-items:center;justify-content:center}
  .card .cbody{flex:1}
  .card .clabel{font-weight:700;font-size:16px;color:var(--cedar)}
  .card .cname{font-family:var(--display);font-weight:800;font-size:22px;color:var(--cedarDeep);margin-top:3px;line-height:1.25;overflow-wrap:anywhere}
  .card .cbadge{display:inline-block;margin-top:8px;font-weight:700;font-size:16px;padding:3px 14px;border-radius:13px}
  .cbadge.area{background:#EBE2C9;color:var(--cedarDeep)}
  .cbadge.date{background:var(--saffron);color:#33290F}

  /* التذييل */
  .footer{margin-top:18px;padding:0 8px}
  .foot-chain{display:flex;justify-content:center;margin-bottom:16px}
  .foot-row{display:flex;align-items:center;gap:20px}
  .qr{flex:0 0 auto;width:118px;height:118px;background:var(--item);border:1px solid var(--gold);border-radius:12px;padding:11px}
  .qr img{width:100%;height:100%;display:block}
  .foot-text{flex:1;text-align:right}
  .foot-scan{font-weight:600;font-size:21px;color:var(--text)}
  .foot-brand{font-weight:400;font-size:19px;color:var(--mut);margin-top:4px}
  .foot-quote{font-weight:500;font-size:21px;color:var(--mut);margin-top:12px;line-height:1.5}
</style>
</head>
<body>
@include('_nashra_icons')
<div class="page">

  <div class="header">
    <svg class="palm" width="120" height="120" viewBox="0 0 100 100"><g stroke="#fff" stroke-width="3" fill="none"><path d="M50 90 L50 44"/><path d="M50 44 Q30 36 16 48"/><path d="M50 44 Q26 40 12 54"/><path d="M50 44 Q70 36 84 48"/><path d="M50 44 Q74 40 88 54"/><path d="M50 44 Q40 30 34 16"/><path d="M50 44 Q60 30 66 16"/><path d="M50 44 Q50 28 50 12"/></g></svg>
    <div class="h-row">
      <div class="h-right">
        <div class="h-title">نشرة لبنان</div>
        <div class="h-sub">الموجز اليومي · مختارات اليوم</div>
        <div class="h-chain"><svg width="180" height="18" viewBox="0 0 180 18"><g transform="translate(18,9)"><circle r="1.6" fill="#9C8654"/><g stroke="#9C8654" stroke-width="1.4"><line x1="0" y1="-8" x2="0" y2="-3.5"/><line x1="0" y1="8" x2="0" y2="3.5"/><line x1="-8" y1="0" x2="-3.5" y2="0"/><line x1="8" y1="0" x2="3.5" y2="0"/><line x1="-5.6" y1="-5.6" x2="-2.5" y2="-2.5"/><line x1="5.6" y1="5.6" x2="2.5" y2="2.5"/><line x1="5.6" y1="-5.6" x2="2.5" y2="-2.5"/><line x1="-5.6" y1="5.6" x2="-2.5" y2="2.5"/></g></g><g transform="translate(50,9)" fill="#9C8654"><circle cx="0" cy="-5" r="1.5"/><circle cx="-2.5" cy="-2.5" r="1.5"/><circle cx="2.5" cy="-2.5" r="1.5"/><circle cx="-5" cy="0" r="1.5"/><circle cx="0" cy="0" r="1.5"/><circle cx="5" cy="0" r="1.5"/><circle cx="-2.5" cy="2.5" r="1.5"/><circle cx="2.5" cy="2.5" r="1.5"/><circle cx="0" cy="5" r="1.5"/></g><g transform="translate(82,9)"><circle r="1.6" fill="#9C8654"/><g stroke="#9C8654" stroke-width="1.4"><line x1="0" y1="-8" x2="0" y2="-3.5"/><line x1="0" y1="8" x2="0" y2="3.5"/><line x1="-8" y1="0" x2="-3.5" y2="0"/><line x1="8" y1="0" x2="3.5" y2="0"/><line x1="-5.6" y1="-5.6" x2="-2.5" y2="-2.5"/><line x1="5.6" y1="5.6" x2="2.5" y2="2.5"/><line x1="5.6" y1="-5.6" x2="2.5" y2="-2.5"/><line x1="-5.6" y1="5.6" x2="-2.5" y2="2.5"/></g></g><g transform="translate(114,9)" fill="#9C8654"><circle cx="0" cy="-5" r="1.5"/><circle cx="-2.5" cy="-2.5" r="1.5"/><circle cx="2.5" cy="-2.5" r="1.5"/><circle cx="-5" cy="0" r="1.5"/><circle cx="0" cy="0" r="1.5"/><circle cx="5" cy="0" r="1.5"/><circle cx="-2.5" cy="2.5" r="1.5"/><circle cx="2.5" cy="2.5" r="1.5"/><circle cx="0" cy="5" r="1.5"/></g><g transform="translate(146,9)"><circle r="1.6" fill="#9C8654"/><g stroke="#9C8654" stroke-width="1.4"><line x1="0" y1="-8" x2="0" y2="-3.5"/><line x1="0" y1="8" x2="0" y2="3.5"/><line x1="-8" y1="0" x2="-3.5" y2="0"/><line x1="8" y1="0" x2="3.5" y2="0"/><line x1="-5.6" y1="-5.6" x2="-2.5" y2="-2.5"/><line x1="5.6" y1="5.6" x2="2.5" y2="2.5"/><line x1="5.6" y1="-5.6" x2="2.5" y2="-2.5"/><line x1="-5.6" y1="5.6" x2="-2.5" y2="2.5"/></g></g></svg></div>
      </div>
      <div class="h-left">
        <div class="h-issue">العدد {{ $issue }}</div>
        <div class="h-day">{{ $day }}</div>
        <div class="h-greg">{{ $greg }}</div>
        @if($hijri)<div class="h-hijri">{{ $hijri }}</div>@endif
      </div>
    </div>
    <svg class="h-border" width="968" height="16" viewBox="0 0 968 16" preserveAspectRatio="none"><rect width="968" height="2" fill="#9C8654"/><g fill="#9C8654"><pattern id="tri" x="0" y="0" width="20" height="10" patternUnits="userSpaceOnUse"><polygon points="4,6 16,6 10,15"/></pattern><rect x="0" y="6" width="968" height="10" fill="url(#tri)"/></g></svg>
  </div>

  <div class="body">

    <div class="block">
      <svg class="corner tl" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner tr" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner bl" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner br" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg>
      <div class="block-inner"><div class="block-inner2">
        <div class="news-date">{{ $greg }}</div>
        <div class="block-title"><svg class="dia" viewBox="0 0 16 16"><rect x="3.5" y="3.5" width="9" height="9" transform="rotate(45 8 8)" fill="#9C8654"/></svg><h2>أهم الأخبار</h2><svg class="dia" viewBox="0 0 16 16"><rect x="3.5" y="3.5" width="9" height="9" transform="rotate(45 8 8)" fill="#9C8654"/></svg></div>
        @foreach($news as $i => $n)
          @php $pr = $n['priority'] ?? 'normal'; $cls = $pr==='breaking'?'b':($pr==='important'?'m':''); @endphp
          <div class="news-item {{ $cls }}">
            <div class="num">{{ $i + 1 }}</div>
            <div class="news-body">
              <div class="news-top">
                @if($pr==='breaking')<span class="news-badge badge-b">عاجل</span>
                @elseif($pr==='important')<span class="news-badge badge-m">مهم</span>@endif
                <span class="news-cat">{{ $n['category'] }}@if(!empty($n['source_name'])) — {{ $n['source_name'] }}@endif</span>
              </div>
              <div class="news-title">{{ $n['title'] }}</div>
              @if(!empty($n['excerpt']))<div class="news-excerpt">{{ $n['excerpt'] }}</div>@endif
            </div>
          </div>
          @if(!$loop->last)<div class="divider"><span class="ln"></span><svg class="dd" viewBox="0 0 20 14"><g fill="#9C8654"><circle cx="10" cy="2" r="1.3"/><circle cx="7" cy="5" r="1.3"/><circle cx="13" cy="5" r="1.3"/><circle cx="4" cy="8" r="1.3"/><circle cx="10" cy="8" r="1.3"/><circle cx="16" cy="8" r="1.3"/><circle cx="7" cy="11" r="1.3"/><circle cx="13" cy="11" r="1.3"/></g></svg><span class="ln"></span></div>@endif
        @endforeach
      </div></div>
    </div>

    <div class="two-col">
      <div class="block">
        <svg class="corner tl" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner tr" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner bl" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner br" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg>
        <div class="block-inner"><div class="block-inner2" style="padding:22px 26px">
          <div class="sub-title"><svg class="dia" viewBox="0 0 16 16"><rect x="3.5" y="3.5" width="9" height="9" transform="rotate(45 8 8)" fill="#9C8654"/></svg><h3>طقس بيروت اليوم</h3><svg class="dia" viewBox="0 0 16 16"><rect x="3.5" y="3.5" width="9" height="9" transform="rotate(45 8 8)" fill="#9C8654"/></svg></div>
          <div class="weather-main">
            <svg class="wicon" viewBox="0 0 48 48">{!! nashra_weather_icon($weather['icon']) !!}</svg>
            <div class="weather-temps">
              <div class="weather-cond">{{ $weather['cond'] }}</div>
              <div class="weather-hi">العظمى {{ $weather['hi'] }}°</div>
              <div class="weather-lo">الصغرى {{ $weather['lo'] }}°</div>
            </div>
          </div>
          <div class="weather-now">الآن {{ $weather['now'] }}°</div>
          <div class="weather-days">
            @foreach($weather['days'] as $d)
            <div class="wday"><div class="d">{{ $d['d'] }}</div><svg class="wicon sm" viewBox="0 0 48 48">{!! nashra_weather_icon($d['icon']) !!}</svg><div class="t">{{ $d['hi'] }}°/{{ $d['lo'] }}°</div></div>
            @endforeach
          </div>
        </div></div>
      </div>

      <div class="block">
        <svg class="corner tl" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner tr" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner bl" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner br" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg>
        <div class="block-inner"><div class="block-inner2" style="padding:22px 26px">
          <div class="sub-title"><svg class="dia" viewBox="0 0 16 16"><rect x="3.5" y="3.5" width="9" height="9" transform="rotate(45 8 8)" fill="#9C8654"/></svg><h3>مواقيت الصلاة</h3><svg class="dia" viewBox="0 0 16 16"><rect x="3.5" y="3.5" width="9" height="9" transform="rotate(45 8 8)" fill="#9C8654"/></svg></div>
          <div class="sub-note">بتوقيت دار الفتوى — بيروت</div>
          <div class="prayer-grid">
            @foreach($prayers as $name => $time)
            <div class="pcell"><svg class="picon" viewBox="0 0 30 30">{!! nashra_prayer_icon($name) !!}</svg><div class="pname">{{ $name }}</div><div class="ptime">{{ $time }}</div></div>
            @endforeach
          </div>
        </div></div>
      </div>
    </div>

    <div class="two-col">
      <div class="block">
        <svg class="corner tl" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner tr" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner bl" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner br" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg>
        <div class="block-inner"><div class="block-inner2" style="padding:22px 24px">
          <div class="sub-title"><svg class="dia" viewBox="0 0 16 16"><rect x="3.5" y="3.5" width="9" height="9" transform="rotate(45 8 8)" fill="#9C8654"/></svg><h3>توصيات اليوم</h3><svg class="dia" viewBox="0 0 16 16"><rect x="3.5" y="3.5" width="9" height="9" transform="rotate(45 8 8)" fill="#9C8654"/></svg></div>
          <div class="cards">
            @foreach($recos as $r)
            <div class="card">
              <div class="cicon"><svg width="22" height="22" viewBox="0 0 30 30">{!! nashra_reco_icon($r['type']) !!}</svg></div>
              <div class="cbody"><div class="clabel">{{ nashra_reco_label($r['type']) }}</div><div class="cname">{{ $r['name'] }}</div>@if(!empty($r['area']))<span class="cbadge area">◍ {{ $r['area'] }}</span>@endif</div>
            </div>
            @endforeach
          </div>
        </div></div>
      </div>

      <div class="block">
        <svg class="corner tl" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner tr" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner bl" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg><svg class="corner br" viewBox="0 0 26 26"><g stroke="#9C8654" fill="#9C8654" stroke-width="1.4"><circle cx="13" cy="13" r="11.5" fill="none"/><g><ellipse cx="13" cy="6" rx="2" ry="4.8"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(60 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(120 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(180 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(240 13 13)"/><ellipse cx="13" cy="6" rx="2" ry="4.8" transform="rotate(300 13 13)"/></g><circle cx="13" cy="13" r="1.7"/></g></svg>
        <div class="block-inner"><div class="block-inner2" style="padding:22px 24px">
          <div class="sub-title"><svg class="dia" viewBox="0 0 16 16"><rect x="3.5" y="3.5" width="9" height="9" transform="rotate(45 8 8)" fill="#9C8654"/></svg><h3>فعاليات لبنان</h3><svg class="dia" viewBox="0 0 16 16"><rect x="3.5" y="3.5" width="9" height="9" transform="rotate(45 8 8)" fill="#9C8654"/></svg></div>
          <div class="cards">
            @foreach($events as $e)
            <div class="card">
              <div class="cicon"><svg width="22" height="22" viewBox="0 0 30 30">{!! nashra_reco_icon('event') !!}</svg></div>
              <div class="cbody"><div class="clabel">{{ $e['category'] }}</div><div class="cname">{{ $e['title'] }}</div>@if(!empty($e['range']))<span class="cbadge date">{{ $e['range'] }}</span>@endif</div>
            </div>
            @endforeach
          </div>
        </div></div>
      </div>
    </div>

    <div class="footer">
      <div class="foot-chain"><svg width="180" height="18" viewBox="0 0 180 18"><g transform="translate(18,9)"><circle r="1.6" fill="#9C8654"/><g stroke="#9C8654" stroke-width="1.4"><line x1="0" y1="-8" x2="0" y2="-3.5"/><line x1="0" y1="8" x2="0" y2="3.5"/><line x1="-8" y1="0" x2="-3.5" y2="0"/><line x1="8" y1="0" x2="3.5" y2="0"/><line x1="-5.6" y1="-5.6" x2="-2.5" y2="-2.5"/><line x1="5.6" y1="5.6" x2="2.5" y2="2.5"/><line x1="5.6" y1="-5.6" x2="2.5" y2="-2.5"/><line x1="-5.6" y1="5.6" x2="-2.5" y2="2.5"/></g></g><g transform="translate(50,9)" fill="#9C8654"><circle cx="0" cy="-5" r="1.5"/><circle cx="-2.5" cy="-2.5" r="1.5"/><circle cx="2.5" cy="-2.5" r="1.5"/><circle cx="-5" cy="0" r="1.5"/><circle cx="0" cy="0" r="1.5"/><circle cx="5" cy="0" r="1.5"/><circle cx="-2.5" cy="2.5" r="1.5"/><circle cx="2.5" cy="2.5" r="1.5"/><circle cx="0" cy="5" r="1.5"/></g><g transform="translate(82,9)"><circle r="1.6" fill="#9C8654"/><g stroke="#9C8654" stroke-width="1.4"><line x1="0" y1="-8" x2="0" y2="-3.5"/><line x1="0" y1="8" x2="0" y2="3.5"/><line x1="-8" y1="0" x2="-3.5" y2="0"/><line x1="8" y1="0" x2="3.5" y2="0"/><line x1="-5.6" y1="-5.6" x2="-2.5" y2="-2.5"/><line x1="5.6" y1="5.6" x2="2.5" y2="2.5"/><line x1="5.6" y1="-5.6" x2="2.5" y2="-2.5"/><line x1="-5.6" y1="5.6" x2="-2.5" y2="2.5"/></g></g><g transform="translate(114,9)" fill="#9C8654"><circle cx="0" cy="-5" r="1.5"/><circle cx="-2.5" cy="-2.5" r="1.5"/><circle cx="2.5" cy="-2.5" r="1.5"/><circle cx="-5" cy="0" r="1.5"/><circle cx="0" cy="0" r="1.5"/><circle cx="5" cy="0" r="1.5"/><circle cx="-2.5" cy="2.5" r="1.5"/><circle cx="2.5" cy="2.5" r="1.5"/><circle cx="0" cy="5" r="1.5"/></g><g transform="translate(146,9)"><circle r="1.6" fill="#9C8654"/><g stroke="#9C8654" stroke-width="1.4"><line x1="0" y1="-8" x2="0" y2="-3.5"/><line x1="0" y1="8" x2="0" y2="3.5"/><line x1="-8" y1="0" x2="-3.5" y2="0"/><line x1="8" y1="0" x2="3.5" y2="0"/><line x1="-5.6" y1="-5.6" x2="-2.5" y2="-2.5"/><line x1="5.6" y1="5.6" x2="2.5" y2="2.5"/><line x1="5.6" y1="-5.6" x2="2.5" y2="-2.5"/><line x1="-5.6" y1="5.6" x2="-2.5" y2="2.5"/></g></g></svg></div>
      <div class="foot-row">
        @if(!empty($qrUrl))<div class="qr"><img src="{{ $qrUrl }}" alt="QR"></div>@endif
        <div class="foot-text">
          @if(!empty($qrUrl))<div class="foot-scan">امسح للوصول إلى الرابط</div><div class="foot-brand">نشرة لبنان اليومية</div>@endif
          @if(!empty($quote))<div class="foot-quote">« {{ $quote }} »</div>@endif
        </div>
      </div>
    </div>

  </div>
</div>
</body>
</html>