<?php

namespace App\Services;

use App\Models\NewsCandidate;
use App\Models\Source;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewsFetcher
{
    // يسحب من كل المصادر المفعّلة التي لها رابط RSS ويعيد ملخص النتائج
    public function fetchAll(): array
    {
        $report = [];
        $sources = Source::where('is_active', true)->whereNotNull('url')->get();

        foreach ($sources as $source) {
            try {
                $count = $this->fetchSource($source->url, $source->name);
                $report[$source->name] = $count;
            } catch (\Throwable $e) {
                $report[$source->name] = 'خطأ: ' . $e->getMessage();
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
                'source_name' => $srcName,
                'title'       => Str::limit($title, 200, ''),
                'excerpt'     => $it['desc'] ? Str::limit(html_entity_decode($it['desc']), 200) : null,
                'used'        => false,
            ]);
            $added++;
        }

        return $added;
    }
}
