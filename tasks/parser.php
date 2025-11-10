<?php

declare(strict_types=1);

namespace App\Services\ParserPrices\Sources;

use App\Helpers\PriceHelper;
use App\Models\Parser;
use App\Services\ParserPrices\AbstractParserPrice;
use Carbon\Carbon;
use DiDom\Document;
use Exception;
use Illuminate\Support\Sleep;

final class Avtoset extends AbstractParserPrice
{
    private const PAGE_PARAM = '?PAGEN_1=%d';

    private array $data = [];
    private int $page = 1;
    private int $maxPage;
    private string $startUrl;

    public function __construct(Parser $item)
    {
        parent::__construct($item);

        $this->startUrl = $item->url;
        $document = new Document($this->startUrl, true);
        $pages = $document->find('div.pagination-block__pages a.pagination-block__page');
        $this->maxPage = (int) ($pages[array_key_last($pages)])->text();
    }

    public function rows(): array
    {
        $this->data = [];

        try {
            if ($this->page <= $this->maxPage) {
                Sleep::for(rand(1, self::SLEEP))->seconds();
                $url = $this->startUrl . ($this->page > 1 ? sprintf(self::PAGE_PARAM, $this->page) : '');
                $document = new Document($url, true);
                $this->getPriceRows($document);
                $this->page++;

                return $this->data;
            }
        } catch (Exception $exception) {
            return $this->data;
        }

        return $this->data;
    }

    /**
     * @throws Exception
     */
    private function getPriceRows($document): void
    {
        if ($rows = $document->find('section.product__wrap')) {
            $date = Carbon::now();

            foreach ($rows as $row) {
                $full = $row->first('div.product__cost-val span.full');
                $price_left_element = strip_tags($full ? $full->text() : '0');
                $coins = $row->first('div.product__cost-val span.coins');
                $price_right_element = strip_tags($coins ? $coins->text() : '00');
                $price = (float) $price_left_element . '.' . $price_right_element;

                if (! $price) {
                    throw new Exception('Next Row Price is Empty!');
                }

                if (! empty($row->first('div.size a.size-val'))) {
                    $params = $row->first('div.size a.size-val')->text();
                    $params = preg_replace('/ {1,}/', ' ', $params);
                    $params = explode(' ', $params);
                    $size = $params[1] . ' ' . $params[2];
                    $speed_index = $params[3];
                }

                $manufacturer = ! empty($row->first('div.brand a.brand-link'))
                    ? strip_tags($row->first('div.brand a.brand-link')->text())
                    : null;

                $model = ! empty($row->first('div.product__title a.model'))
                    ? strip_tags($row->first('div.product__title a.model')->text())
                    : null;

                $this->data[] = [
                    'parser_id' => $this->item->id,
                    'category_id' => $this->item->category_id,
                    'size' => $size,
                    'speed_index' => $speed_index,
                    'layering' => null,
                    'manufacturer' => trim((string) $manufacturer),
                    'model' => trim((string) $model),
                    'applicability' => null,
                    'price' => $price,
                    'key' => PriceHelper::createKey($size),
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }
        }
    }
}
