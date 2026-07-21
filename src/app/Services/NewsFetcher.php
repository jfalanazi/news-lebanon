<?php

namespace App\Services;

use App\Models\NewsCandidate;
use App\Models\Source;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewsFetcher
{
    /** أسماء عرض موحّدة: النطاق/الاسم اللاتيني ← الاسم العربي المعتمد */
    private const SOURCE_NAMES = [
        'annahar.com'       => 'النهار',
        'annahar'           => 'النهار',
        'elnashra'          => 'النشرة',
        'elnashra.com'      => 'النشرة',
        'almodon'           => 'المدن',
        'almodon.com'       => 'المدن',
        'al-akhbar.com'     => 'الأخبار',
        'al-akhbar'         => 'الأخبار',
        'nna-leb.gov.lb'    => 'الوكالة الوطنية',
        'nna'               => 'الوكالة الوطنية',
        'lbcgroup.tv'       => 'LBCI',
        'lbci'              => 'LBCI',
        'lbci lebanon'      => 'LBCI',
        'mtv lebanon'       => 'MTV',
        'mtv.com.lb'        => 'MTV',
        'aljadeed.tv'       => 'الجديد',
        'al jadeed'         => 'الجديد',
        'naharnet'          => 'نهارنت',
        'naharnet.com'      => 'نهارنت',
        "l'orient-le jour"  => 'لوريان لو جور',
        'lorientlejour.com' => 'لوريان لو جور',
    ];

    public static function displayName(string $name): string
    {
        return self::SOURCE_NAMES[mb_strtolower(trim($name))] ?? trim($name);
    }

    // يسحب من كل المصادر المفعّلة التي لها رابط RSS ويعيد ملخص النتائج
    // ويسجّل صحة كل مصدر (آخر سحب، عدد الأخبار، آخر خطأ) لعرضها في اللوحة
    public function fetchAll(): array
    {
        $report = [];
        $sources = Source::where('is_active', true)->whereNotNull('url')->get();

        foreach ($sources as $source) {
            try {
                $count = $this->fetchSource($source->url, $source->name);
                $report[$source->name] = $count;
                $source->update([
                    'last_fetched_at'  => now(),
                    'last_fetch_count' => $count,
                    'last_error'       => null,
                ]);
            } catch (\Throwable $e) {
                $report[$source->name] = 'خطأ: ' . $e->getMessage();
                $source->update([
                    'last_fetched_at' => now(),
                    'last_error'      => Str::limit($e->getMessage(), 180),
                ]);
            }
        }

        return $report;
    }

    private function fetchSource(string $url, string $sourceName): int
    {
        $body = Http::timeout(20)->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (NashraLebanon RSS Reader)',
        ])->get($url)->body();

        $xml = @simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (! $xml) {
            throw new \RuntimeException('تعذّر قراءة التغذية');
        }

        $items = [];
        if (isset($xml->channel->item)) {
            foreach ($xml->channel->item as $i) {
                $items[] = [
                    'title' => trim((string) $i->title),
                    'link'  => trim((string) $i->link),
                    'desc'  => trim(strip_tags((string) $i->description)),
                ];
            }
        } elseif (isset($xml->entry)) {
            foreach ($xml->entry as $i) {
                $items[] = [
                    'title' => trim((string) $i->title),
                    'link'  => trim((string) ($i->link['href'] ?? '')),
                    'desc'  => trim(strip_tags((string) ($i->summary ?? ''))),
                ];
            }
        }

        $added = 0;
        foreach (array_slice($items, 0, 15) as $it) {
            if ($it['title'] === '') {
                continue;
            }

            $title = $it['title'];
            $srcName = $sourceName;
            // عناصر Google News تكون بصيغة «العنوان - المصدر»
            if (str_contains($title, ' - ')) {
                $pos = strrpos($title, ' - ');
                $maybeSource = trim(substr($title, $pos + 3));
                if (mb_strlen($maybeSource) <= 30) {
                    $srcName = $maybeSource;
                    $title = trim(substr($title, 0, $pos));
                }
            }

            $exists = NewsCandidate::where('title', $title)
                ->orWhere(fn ($q) => $q->where('url', $it['link'])->whereNotNull('url')->where('url', '!=', ''))
                ->exists();
            if ($exists) {
                continue;
            }

            NewsCandidate::create([
                'for_date'    => now()->toDateString(),
                'category'    => 'لبنان',
                'url'         => $it['link'],
                'source_name' => self::displayName($srcName),
                'title'       => Str::limit($title, 200, ''),
                'excerpt'     => $it['desc'] ? Str::limit(html_entity_decode($it['desc']), 200) : null,
                'used'        => false,
            ]);
            $added++;
        }

        return $added;
    }
}
