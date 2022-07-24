<?php

declare(strict_types=1);

namespace Src\Shared\Infrastructure;

final class Utils
{
    public static function getCurlXmlFromUrl(string $url): string|bool
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $outputHtml = curl_exec($ch);
        curl_close($ch);

        return $outputHtml;
    }
}
