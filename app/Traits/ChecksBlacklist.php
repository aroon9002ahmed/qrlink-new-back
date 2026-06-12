<?php

namespace App\Traits;

use App\Models\Blacklist;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ChecksBlacklist
{
    /**
     * Check if the domain of the provided URL is blacklisted.
     *
     * @param string|null $url
     * @throws HttpResponseException
     */
    public function checkDomainBlacklist(?string $url): void
    {
        if (empty($url)) {
            return;
        }

        // Extract host name
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) {
            $host = $url;
        }

        // Clean domain string
        $host = strtolower(trim($host));

        // Strip port number if present (e.g. localhost:8000 -> localhost)
        if (strpos($host, ':') !== false) {
            $host = explode(':', $host)[0];
        }

        // Generate suffixes for checking parent domains/subdomains
        // For sub.domain.com we check ['sub.domain.com', 'domain.com', 'com']
        $parts = explode('.', $host);
        $domainsToCheck = [];
        $count = count($parts);

        for ($i = 0; $i < $count; $i++) {
            $suffix = implode('.', array_slice($parts, $i));
            if (!empty($suffix)) {
                $domainsToCheck[] = $suffix;
            }
        }

        $blacklistEntry = Blacklist::query()
            ->where('is_active', true)
            ->whereIn('domain', $domainsToCheck)
            ->first();

        if ($blacklistEntry) {
            $reason = "Reason : " . $blacklistEntry->reason ?: __('The domain of the provided URL is blacklisted.');

            throw new HttpResponseException(response()->json([
                'status'  => false,
                'message' => $reason,
                'errors'  => [
                    'url'          => [$reason],
                    'original_url' => [$reason],
                ]
            ], 422));
        }
    }
}
