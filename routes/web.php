<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FacebookFeedController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return Inertia::render('Home/Index', [
        'stats' => \App\Models\Stat::orderBy('order')->get(['value', 'label', 'icon']),
        'services' => \App\Models\Service::orderBy('order')->get(['title', 'description', 'icon', 'color']),
        'testimonials' => \App\Models\Testimonial::select(['title', 'comment', 'name'])
            ->where('homepage', true)
            ->orderBy('order')
            ->limit(3)
            ->get(),
        'featuredTestimonial' => \App\Models\Testimonial::select(['title', 'comment', 'name'])
            ->where('featured', true)
            ->orderBy('order')
            ->first(),
        'events' => (function () {
            $from = \Carbon\Carbon::today()->startOfDay();
            $to = $from->copy()->addYear();

            $meetings = \App\Models\Meeting::with('activity')
                ->where(function ($q) use ($from, $to) {
                    $q->whereNull('recurrence')->whereBetween('starts_at', [$from, $to]);
                })
                ->orWhere(function ($q) use ($from, $to) {
                    $q->whereNotNull('recurrence')
                        ->where('starts_at', '<=', $to)
                        ->where(function ($q2) use ($from) {
                            $q2->whereNull('recurrence_ends_at')
                                ->orWhere('recurrence_ends_at', '>=', $from);
                        });
                })
                ->get();

            return $meetings
                ->flatMap(fn($m) => $m->occurrences($from, $to))
                ->sortBy('starts_at')
                ->take(4)
                ->values();
        })(),
    ]);
})->name('home');

Route::get('/' . env('POSTS_SLUG_PREFIX'), [PostController::class, 'index'])->name('posts.index');
Route::get('/' . env('POSTS_SLUG_PREFIX') . '/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/competitions', [\App\Http\Controllers\CompetitionController::class, 'index'])->name('competitions.index');
Route::get('/competitions/{slug}', [\App\Http\Controllers\CompetitionController::class, 'show'])->name('competitions.show');
Route::middleware('auth')->post('/competitions/{slug}/submit', [\App\Http\Controllers\CompetitionController::class, 'submit'])->name('competitions.submit');

Route::get('/api/facebook-feed', [FacebookFeedController::class, 'index'])->name('facebook.feed');

Route::get('/calendar', [\App\Http\Controllers\CalendarController::class, 'index'])->name('calendar');
Route::get('/meetings', [\App\Http\Controllers\CalendarController::class, 'week'])->name('meetings');

Route::get('/privacy-policy', function () {
    return Inertia::render('Policy/Show', [
        'title' => 'Privacy Policy',
        'intro' => 'This Privacy Policy explains how Woodvale & Ainsdale Community Association collects, uses, stores, and protects personal information when you use our website or engage with our services.',
        'sections' => [
            [
                'heading' => 'What information we collect',
                'body' => 'We may collect basic contact details, enquiry information, and technical information such as IP addresses and browser data when you visit our website or contact us.',
            ],
            [
                'heading' => 'How we use your information',
                'list' => [
                    'To respond to enquiries and provide information about our services.',
                    'To manage events, bookings, volunteering, and community activities.',
                    'To improve the website and ensure it remains secure and functional.',
                ],
            ],
            [
                'heading' => 'Sharing your information',
                'body' => 'We do not sell your personal data. We may share information with trusted service providers who support our website and communications where this is necessary to provide our services.',
            ],
            [
                'heading' => 'Your choices',
                'body' => 'You may contact us to ask for access to, correction of, or deletion of your personal information where applicable.',
            ],
        ],
    ]);
})->name('privacy.policy');

Route::get('/cookie-policy', function () {
    return Inertia::render('Policy/Show', [
        'title' => 'Cookie Policy',
        'intro' => 'Our website uses cookies and similar technologies to improve your browsing experience and understand how visitors use the site.',
        'sections' => [
            [
                'heading' => 'What cookies we use',
                'list' => [
                    'Essential cookies that keep the website secure and functional.',
                    'Preference cookies that remember your choices on the site.',
                    'Analytics cookies that help us understand website performance and usage.',
                ],
            ],
            [
                'heading' => 'Managing cookies',
                'body' => 'Most browsers allow you to manage or disable cookies through your settings. Disabling some cookies may affect the functionality of parts of the website.',
            ],
        ],
    ]);
})->name('cookie.policy');

Route::get('/terms-of-use', function () {
    return Inertia::render('Policy/Show', [
        'title' => 'Terms of Use',
        'intro' => 'These Terms of Use set out the conditions for using our website and the information and services available through it.',
        'sections' => [
            [
                'heading' => 'Use of the website',
                'body' => 'You may use this website for lawful purposes only and should not interfere with its operation, security, or content.',
            ],
            [
                'heading' => 'Intellectual property',
                'body' => 'All website content, branding, and materials are owned by Woodvale & Ainsdale Community Association unless otherwise stated.',
            ],
            [
                'heading' => 'Liability',
                'body' => 'We aim to keep the website accurate and up to date, but we cannot guarantee that all information will always be complete, current, or error-free.',
            ],
        ],
    ]);
})->name('terms.of.use');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //  INTERNAL ROUTES
    Route::get('/dashboard', function () {
        return Inertia::render('Internal/Dashboard');
    })->name('dashboard');
    Route::name('internal.')->prefix('internal')->group(function () {
        Route::resource('media', \App\Http\Controllers\Internal\MediaController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('menu-items', \App\Http\Controllers\Internal\MenuItemController::class);
        Route::resource('competitions', \App\Http\Controllers\Internal\CompetitionController::class);
        Route::prefix('competitions/{competition}')->name('competitions.')->group(function () {
            Route::delete('submissions/{submission}', [\App\Http\Controllers\Internal\CompetitionSubmissionController::class, 'destroy'])->name('submissions.destroy');
            Route::post('submissions/{submission}/winner', [\App\Http\Controllers\Internal\CompetitionSubmissionController::class, 'winner'])->name('submissions.winner');
        });
        Route::resource('pages', \App\Http\Controllers\Internal\PageController::class);
        Route::resource('posts', \App\Http\Controllers\Internal\PostController::class);
        Route::resource('activities', \App\Http\Controllers\Internal\ActivityController::class);
        Route::get('meetings/week', [\App\Http\Controllers\Internal\MeetingController::class, 'week'])->name('meetings.week');
        Route::resource('meetings', \App\Http\Controllers\Internal\MeetingController::class)->except(['show']);
        Route::resource('stats', \App\Http\Controllers\Internal\StatController::class)->except(['show']);
        Route::resource('services', \App\Http\Controllers\Internal\ServiceController::class)->except(['show']);
        Route::resource('testimonials', \App\Http\Controllers\Internal\TestimonialController::class)->except(['show']);
        Route::get('page-views', [\App\Http\Controllers\Internal\PageViewController::class, 'index'])->name('page-views.index');
        Route::get('field-changes', [\App\Http\Controllers\Internal\FieldChangeController::class, 'index'])->name('field-changes.index');
    });

    //  ADMIN ROUTES
    Route::get('/admin', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::name('admin.')->prefix('admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::get('users/{user}/roles', [\App\Http\Controllers\Admin\UserRoleController::class, 'index'])->name('user_roles.index');
        Route::put('users/{user}/roles', [\App\Http\Controllers\Admin\UserRoleController::class, 'update'])->name('user_roles.update');

        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
        Route::get('roles/{role}/rights', [\App\Http\Controllers\Admin\RoleRightController::class, 'index'])->name('role_rights.index');
        Route::post('roles/{role}/rights', [\App\Http\Controllers\Admin\RoleRightController::class, 'store'])->name('role_rights.store');
        Route::put('roles/{role}/rights', [\App\Http\Controllers\Admin\RoleRightController::class, 'update'])->name('role_rights.update');

    });
});

require __DIR__ . '/auth.php';

// Catch-all for dynamic page slugs — must remain last
Route::get('/{slug}', [\App\Http\Controllers\PageController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('pages.show');
