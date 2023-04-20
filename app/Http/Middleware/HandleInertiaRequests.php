<?php

namespace App\Http\Middleware;

use App\Enums\ExpenseCategory;
use App\Enums\ExpensePaymentType;
use App\Enums\ExpenseType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Laracasts\Flash\Message;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        $flashNotification = session('flash_notification', collect())
            ->map(function (Message $message) {
                $message->timestamp = Carbon::now()->timestamp;
                return $message;
            })
            ->toArray();

        return array_merge(parent::share($request), [
            'flash_notification' => $flashNotification,
            'enums' => [
                'expenses' => [
                    'payment_types' => ExpensePaymentType::options(),
                    'categories' => ExpenseCategory::options(),
                    'types' => ExpenseType::options(),
                ]
            ]
        ]);
    }
}
