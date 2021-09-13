<?php


namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\ThrottlesLogins as _ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Cache\Repository;
use Illuminate\Support\InteractsWithTime;

trait ThrottlesLogins
{
    use _ThrottlesLogins, InteractsWithTime;

    /**
     * Increment the login attempts for the user.
     *
     * @param Request $request
     * @return void
     */
    protected function incrementLoginAttempts(Request $request)
    {
        $this->hit(
            $this->throttleKey($request), $this->decayMinutes() * 60
        );
    }

    public function hit($key, $decaySeconds = 60) {
        $this->cache()->put(
            $key.':timer', $this->availableAt($decaySeconds), $decaySeconds
        );

        $added = $this->cache()->add($key, 0, $decaySeconds);

        $hits = (int) $this->cache()->increment($key);

        if (! $added && $hits == 1) {
            $this->cache()->put($key, 1, $decaySeconds);
        }

        return $hits;
    }

    public function cache() {
        return app(Repository::class);
    }
}
