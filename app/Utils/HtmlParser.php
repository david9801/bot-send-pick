<?php
namespace App\Utils;

use PHPHtmlParser\Dom;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class HtmlParser{
    public static function getPickFromHtml($html){
        $dom = new Dom;
        $dom->loadStr($html);
        $celda = $dom->find('td', 1);
        $fila = $celda->find('tr', 1);
        $celda = $fila->find('td', 0);
        $result = explode('<br />', $celda->innerHtml());
        [$stake, $odd, $bookie] = array_pad(explode(';', $result[4]), 3, null);

        return [
            'time' => $result[0],
            'sport' => $result[1],
            'event' => $result[2],
            'pick' => Str::after($result[3], 'pick: '),
            'stake' => Str::after($stake, 'stake: '),
            'odd' => Str::after($odd, 'odds: '),
            'bookie' => $bookie,
        ];
    }

    public static function getLivePickFromHtml($html){
        $dom = new Dom;
        $dom->loadStr($html);
        $celda = $dom->find('td', 1);
        $fila = $celda->find('tr', 1);
        $celda = $fila->find('td', 0);
        $result = explode('<br />', $celda->innerHtml());
        [$stake, $odd, $bookie] = array_pad(explode(';', $result[3]), 3, null);

        return [
            'sport' => $result[0],
            'event' => $result[1],
            'pick' => Str::after($result[2], 'pick: '),
            'stake' => Str::after($stake, 'stake: '),
            'odd' => Str::after($odd, 'odds: '),
            'bookie' => $bookie,
        ];
    }

    public static function getComboPickFromHtml($html){
        $dom = new Dom;
        $dom->loadStr($html);
        $celda = $dom->find('td', 1);
        $fila = $celda->find('tr', 1);
        $celda = $fila->find('td', 0);
        $result = explode('<br /><br />', $celda->innerHtml());
        $title = Arr::only($result, '0')[0];
        $stakeData = explode('<br />', $title)[1];
        [$totalStake, $totalOdd, $bookie] = array_pad(explode(';', $stakeData), 3, null);
        $picks = Arr::except($result, '0');

        $picksData = [];
        foreach ($picks as $singlePick){
            [$event, $pick, $odd] = array_pad(explode('<br />', $singlePick), 3, null);
            $picksData[] = [
                'event' => $event,
                'pick' => Str::after($pick, 'pick: '),
                'odd' => Str::after($odd, 'odds: '),
            ];
        }

        return [
            'totalOdd' => Str::after($totalOdd, 'odds '),
            'totalStake' => Str::after($totalStake, 'stake '),
            'bookie' => Str::after($bookie, ' '),
            'picks' => $picksData,
        ];

    }




}
