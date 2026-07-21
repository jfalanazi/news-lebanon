<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>نشرة لبنان — العدد {{ $edition->issue_number }}</title>
@php
    $ogImg = url('/storage/newsletters/edition-' . $edition->issue_number . '.png');
    $ogDesc = 'أهم أخبار لبنان ليوم ' . \Carbon\Carbon::parse($edition->edition_date)->format('Y/m/d') . ' — مع الطقس والصلاة والتوصيات والفعاليات.';
@endphp
<meta name="description" content="{{ $ogDesc }}">
<meta property="og:type" content="article">
<meta property="og:title" content="نشرة لبنان — العدد {{ $edition->issue_number }}">
<meta property="og:description" content="{{ $ogDesc }}">
<meta property="og:image" content="{{ $ogImg }}">
<meta property="og:url" content="{{ url('/n/' . $edition->issue_number) }}">
<meta property="og:site_name" content="نشرة لبنان">
<meta property="og:locale" content="ar_LB">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="نشرة لبنان — العدد {{ $edition->issue_number }}">
<meta name="twitter:description" content="{{ $ogDesc }}">
<meta name="twitter:image" content="{{ $ogImg }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=IBM+Plex+Sans+Arabic:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#0D5A33; --ink2:#11713F; --paper:#F4F0E5; --cedar:#146B3F;
    --gold:#9C8654; --goldSoft:#C9B47E; --saffron:#C1A45C; --red:#A8342B;
    --text:#20302A; --mut:#66705F; --line:#E3DAC4; --card:#FDFBF3;
  }
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:var(--paper);color:var(--text);font-family:"IBM Plex Sans Arabic","Tajawal",sans-serif;line-height:1.6}
  .wrap{max-width:680px;margin:0 auto;padding:0 0 40px}
  /* غير لاصقة: على الجوال مساحة القراءة أهم من حضور العلامة الدائم */
  .header{background:var(--ink);color:#fff;padding:22px 20px;text-align:center}
  .h-title{font-family:"Tajawal",sans-serif;font-weight:800;font-size:26px}
  .h-sub{font-weight:500;font-size:15px;color:#CFE3D2;margin-top:4px}
  .strip{display:flex;gap:8px;overflow-x:auto;padding:12px 16px 0;scrollbar-width:none}
  .strip::-webkit-scrollbar{display:none}
  .s-chip{flex:0 0 auto;background:#FDFBF3;border:1px solid var(--line);border-radius:20px;padding:6px 14px;font-size:13px;font-weight:600;color:var(--cedar);white-space:nowrap}
  .sec{padding:18px 16px 4px}
  .sec-title{font-family:"Tajawal",sans-serif;font-weight:800;font-size:20px;color:var(--cedar);
    display:flex;align-items:center;gap:8px;margin-bottom:10px}
  .sec-title::before{content:"";width:10px;height:10px;background:var(--gold);transform:rotate(45deg);display:inline-block}
  .card{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:14px 16px;margin:0 16px 12px;
    box-shadow:0 2px 8px rgba(0,0,0,.04)}
  .n-top{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:5px}
  .badge{font-family:"Tajawal",sans-serif;font-weight:700;font-size:12px;color:#fff;padding:2px 10px;border-radius:6px}
  .b-breaking{background:var(--red)} .b-important{background:var(--saffron)}
  .n-cat{font-weight:600;font-size:13px;color:var(--gold)}
  .n-title{font-family:"Tajawal",sans-serif;font-weight:700;font-size:18px;color:var(--text);line-height:1.35}
  .n-excerpt{font-size:14px;color:var(--mut);margin-top:5px}
  .n-link{display:inline-block;margin-top:9px;font-weight:600;font-size:13px;color:var(--cedar);text-decoration:none;
    border:1px solid var(--goldSoft);border-radius:8px;padding:5px 12px}
  .n-link:active{background:#EFEADF}
  .n-more{margin-top:8px}
  .n-more summary{cursor:pointer;font-weight:600;font-size:13px;color:var(--cedar);list-style:none;display:inline-block}
  .n-more summary::-webkit-details-marker{display:none}
  .n-more summary::before{content:"▾ ";}
  .n-more[open] summary::before{content:"▴ ";}
  .n-bodyfull{font-size:16px;color:var(--text);margin-top:10px;line-height:1.9;white-space:pre-line;border-right:2px solid var(--line);padding-right:12px}
  .n-foot{display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-top:10px}
  .src-badge{display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:600;background:#F1EAD5;color:var(--cedar);padding:4px 12px;border-radius:20px;border:1px solid var(--goldSoft)}
  .chip{display:inline-block;font-size:12px;font-weight:600;background:#EBE2C9;color:var(--cedar);padding:2px 10px;border-radius:12px;margin-top:6px}
  .foot{text-align:center;color:var(--mut);font-size:14px;padding:22px 24px 0;line-height:1.9}
  .foot b{color:var(--cedar)}
  .empty{color:var(--mut);font-size:13px;padding:0 16px 8px}
</style>
</head>
<body>
@php
    $recoType = fn($t) => match($t){'restaurant'=>'مطعم','landmark'=>'معلم','park'=>'منتزه','cafe'=>'مقهى',default=>$t};
@endphp
<div class="wrap">

  <div class="header">
    <div class="h-title">🇱🇧 نشرة لبنان — العدد {{ $edition->issue_number }}</div>
    <div class="h-sub">{{ \App\Support\ArabicDate::full($edition->edition_date) }}</div>
  </div>

  {{-- شريط اليوم: الطقس ومواقيت الصلاة من لقطة العدد --}}
  @php $w = is_array($edition->weather) ? $edition->weather : null; $pr = is_array($edition->prayers) ? $edition->prayers : null; @endphp
  @if($w || $pr)
  <div class="strip">
    @if($w)<span class="s-chip">⛅ {{ $w['cond'] ?? 'الطقس' }} · {{ $w['hi'] ?? '—' }}° / {{ $w['lo'] ?? '—' }}°</span>@endif
    @if($pr)
      @foreach(['الفجر','الظهر','العصر','المغرب','العشاء'] as $pn)
        @if(!empty($pr[$pn]))<span class="s-chip">{{ $pn }} {{ $pr[$pn] }}</span>@endif
      @endforeach
    @endif
  </div>
  @endif

  @php
    $pageUrl = url('/n/' . $edition->issue_number);
    $shareText = 'نشرة لبنان — العدد ' . $edition->issue_number;
  @endphp
  <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;padding:14px 16px 2px">
    {{-- مشاركة النظام (تظهر فقط حيث تُدعم — أغلب الجوالات) --}}
    <button type="button" id="nshare" hidden
       onclick="navigator.share({title:'{{ $shareText }}',url:'{{ $pageUrl }}'})"
       style="background:#0D5A33;color:#fff;border:none;font-weight:700;font-size:13px;padding:8px 18px;border-radius:10px;cursor:pointer">مشاركة</button>
    <a href="https://wa.me/?text={{ urlencode($shareText . ' ' . $pageUrl) }}" target="_blank" rel="noopener"
       style="background:#25D366;color:#fff;text-decoration:none;font-weight:700;font-size:13px;padding:8px 18px;border-radius:10px">مشاركة واتساب</a>
    <button type="button" onclick="navigator.clipboard.writeText('{{ $pageUrl }}');this.textContent='✓ نُسخ';setTimeout(()=>this.textContent='نسخ الرابط',1500)"
       style="background:#EFEADF;color:#20302A;border:none;font-weight:700;font-size:13px;padding:8px 16px;border-radius:10px;cursor:pointer">نسخ الرابط</button>
  </div>

  {{-- الأخبار: مرتّبة تلقائيًا (عاجل ← مهم ← عادي) --}}
  @php
    $rank = ['breaking' => 0, 'important' => 1, 'normal' => 2];
    $newsList = $edition->news
        ->filter(fn ($n) => $n->active !== false)
        ->sortBy(fn ($n) => sprintf('%d_%09d', $rank[$n->priority] ?? 3, (int) $n->position));
  @endphp
  <div class="sec"><div class="sec-title">أهم الأخبار</div></div>
  @forelse ($newsList as $n)
    <div class="card">
      <div class="n-top">
        @if($n->priority === 'breaking')<span class="badge b-breaking">عاجل</span>
        @elseif($n->priority === 'important')<span class="badge b-important">مهم</span>@endif
        <span class="n-cat">{{ $n->category }}</span>
      </div>
      <div class="n-title">{{ $n->title }}</div>
      @if($n->excerpt)<div class="n-excerpt">{{ $n->excerpt }}</div>@endif
      @if($n->body)
        <details class="n-more">
          <summary>اقرأ المزيد</summary>
          <div class="n-bodyfull">{{ $n->body }}</div>
        </details>
      @endif
      <div class="n-foot">
        {{-- عنصر واحد يجمع الفعل والمصدر — الشارة المنفصلة فقط إن لم يوجد رابط --}}
        @if($n->url)
          <a class="n-link" href="{{ $n->url }}" target="_blank" rel="noopener">اقرأ من {{ $n->source_name ?: 'المصدر' }} ↗</a>
        @elseif($n->source_name)
          <span class="src-badge">🗞️ {{ $n->source_name }}</span>
        @endif
      </div>
    </div>
  @empty
    <div class="empty">لا توجد أخبار في هذا العدد.</div>
  @endforelse

  {{-- التوصيات --}}
  @if($edition->recommendations->isNotEmpty())
    <div class="sec"><div class="sec-title">توصيات اليوم</div></div>
    @foreach ($edition->recommendations as $r)
      <div class="card">
        <div class="n-cat">{{ $recoType($r->type) }}</div>
        <div class="n-title">{{ $r->name }}</div>
        @if($r->description)<div class="n-excerpt">{{ $r->description }}</div>@endif
        @if($r->area)<span class="chip">◍ {{ $r->area }}</span>@endif
      </div>
    @endforeach
  @endif

  {{-- الفعاليات --}}
  @if($edition->events->isNotEmpty())
    <div class="sec"><div class="sec-title">فعاليات لبنان</div></div>
    @foreach ($edition->events as $e)
      <div class="card">
        @if($e->category)<div class="n-cat">{{ $e->category }}</div>@endif
        <div class="n-title">{{ $e->title }}</div>
        @if($e->start_date)
          <span class="chip">🗓️ {{ \App\Support\ArabicDate::short($e->start_date) }}@if($e->end_date) — {{ \App\Support\ArabicDate::short($e->end_date) }}@endif</span>
        @endif
      </div>
    @endforeach
  @endif

  @php $footQuote = $edition->quote ?: \App\Models\Setting::get('default_quote', ''); @endphp
  @if($footQuote)
    <div class="foot">« {{ $footQuote }} »<br><b>نشرة لبنان اليومية</b></div>
  @else
    <div class="foot"><b>نشرة لبنان اليومية</b></div>
  @endif

</div>
<script>if (navigator.share) { document.getElementById('nshare').hidden = false; }</script>
</body>
</html>
