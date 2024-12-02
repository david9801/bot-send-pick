<?php
namespace App\Utils;

class Telegram{

    public static function getMessageForLivePick($pickData, $pickTispter){
        $title = "🔴 LIVE";
        $tipster = $pickTispter ?? null;
        $sport = $pickData['sport'] ?? null;
        $event = $pickData['event'] ?? null;
        $pick = $pickData['pick'] ?? null;
        $stake = $pickData['stake'] ?? null;
        $odd = $pickData['odd'] ?? null;
        $bookie = $pickData['bookie'] ?? null;
        $content = "🗣 Tipster: $tipster\n\n📍 Sport: $sport\n\n🏆 Event: $event\n\n👉 Pick: $pick\n\n💎 Odd: $odd\n\n 💰 Stake: $stake\n\n 🏠 Bookie: $bookie";

        return "$title\n\n\n$content";
    }

    public static function getMessageForPick($pickData, $pickTispter){
        $time = $pickData['time'] ?? null;
        $title ="⏰ Time: $time";

        $tipster = $pickTispter ?? null;
        $sport = $pickData['sport'] ?? null;
        $event = $pickData['event'] ?? null;
        $pick = $pickData['pick'] ?? null;
        $stake = $pickData['stake'] ?? null;
        $odd = $pickData['odd'] ?? null;
        $bookie = $pickData['bookie'] ?? null;
        $content = "🗣 Tipster: $tipster\n\n📍 Sport: $sport\n\n🏆 Event: $event\n\n👉 Pick: $pick\n\n💎 Odd: $odd\n\n 💰 Stake: $stake\n\n 🏠 Bookie: $bookie";
        return "$title\n\n\n$content";
    }

    public static function getMessageForComboPick($pickData, $pickTispter){
        $title ="🚀 Combo pick";

        $tipster = $pickTispter ?? null;
        $totalOdd = $pickData['totalOdd'] ?? null;
        $totalStake = $pickData['totalStake'] ?? null;
        $bookie = $pickData['bookie'] ?? null;

        $message = "$title\n\n\n🗣 Tipster: $tipster\n\n💎 Odd: $totalOdd\n\n 💰 Stake: $totalStake\n\n 🏠 Bookie: $bookie\n\n\n";

        foreach ($pickData['picks'] as $singlePick){
            $event = $singlePick['event'];
            $pick = $singlePick['pick'];
            $odd = $singlePick['odd'];
            $singlePickMessage = "--------\n🏆 Event: $event\n\n👉 Pick: $pick\n\n💎 Odd: $odd\n\n";
            $message .= $singlePickMessage;
        }
        return $message;
    }

    public static function getMessageForAsianPick($pickData, $pickTispter){
        $message = self::getMessageForPick($pickData, $pickTispter);
        return "✴️ Asian Odds\n\n$message";
    }

    public static function getMessageForAsianLivePick($pickData, $pickTispter){
        $message = self::getMessageForLivePick($pickData, $pickTispter);
        return "✴️ Asian Odds\n\n$message";
    }
}
