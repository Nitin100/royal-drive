<?php

use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\BlogController;
use App\Models\Blog;
use App\Models\Fleet;
use App\Models\Service;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\BlogTagController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ChauffeurController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\FleetController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PromotionPopupController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TourCategoryController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\TourTagController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $services = Service::query()->orderBy('title')->get();
    $featuredFleets = Fleet::query()->orderByDesc('created_at')->take(4)->get();
    $blogPosts = Blog::query()
        ->where('is_featured', true)
        ->orderByDesc('created_at')
        ->take(3)
        ->get();

    return view('home', compact('services', 'featuredFleets', 'blogPosts'));
})->name('home');
Route::get('/blog/{blog:slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
Route::get('/fleet-details/{fleet:slug}', [FrontController::class, 'fleet_details'])->name('fleet.details');
Route::get('/contact-us', function () {
    return view('contact-us');
})->name('contact.us');
Route::post('/contact-us', [FrontController::class, 'store_enquiry'])->name('contact.store');
Route::post('/fleet-booking', [FrontController::class, 'fleet_booking'])->name('booking.store');
Route::get('/booking-success/{bookingNumber}', [FrontController::class, 'booking_success'])->name('booking.success');

Route::get('/pages/{page:slug}', [PageController::class, 'show'])->name('pages.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin/users', [AdminPageController::class, 'users'])->name('admin.users');
    Route::get('/admin/reports', [AdminPageController::class, 'reports'])->name('admin.reports');
    Route::get('/admin/enquiries', [EnquiryController::class, 'index'])->name('admin.enquiries.index');
    Route::get('/admin/chauffeurs/reports', [ChauffeurController::class, 'reports'])->name('admin.chauffeurs.reports');
    Route::get('/admin/chauffeurs/calendar', [ChauffeurController::class, 'calendar'])->name('admin.chauffeurs.calendar');
    Route::get('/admin/blogs/featured', [AdminBlogController::class, 'featured'])->name('admin.blogs.featured');
    Route::get('/admin/tours/featured', [TourController::class, 'featured'])->name('admin.tours.featured');
    Route::resource('admin/blog-categories', BlogCategoryController::class)
        ->except(['create', 'show'])
        ->names('admin.blog-categories');
    Route::resource('admin/blog-tags', BlogTagController::class)
        ->except(['create', 'show'])
        ->names('admin.blog-tags');
    Route::resource('admin/blogs', AdminBlogController::class)
        ->except(['show'])
        ->names('admin.blogs');
    Route::resource('admin/bookings', BookingController::class)
        ->only(['index'])
        ->names('admin.bookings');
    Route::patch('admin/bookings/{booking}/status', [BookingController::class, 'updateStatus'])
        ->name('admin.bookings.status');
    Route::resource('admin/tour-categories', TourCategoryController::class)
        ->except(['create', 'show'])
        ->names('admin.tour-categories');
    Route::resource('admin/tour-tags', TourTagController::class)
        ->except(['create', 'show'])
        ->names('admin.tour-tags');
    Route::resource('admin/tours', TourController::class)
        ->except(['show'])
        ->names('admin.tours');
    Route::resource('admin/chauffeurs', ChauffeurController::class)
        ->except(['show'])
        ->names('admin.chauffeurs');
    Route::resource('admin/fleets', FleetController::class)
        ->except(['show'])
        ->names('admin.fleets');
    Route::resource('admin/amenities', AmenityController::class)
        ->except(['show'])
        ->names('admin.amenities');
    Route::resource('admin/services', ServiceController::class)
        ->except(['show'])
        ->names('admin.services');
    Route::resource('admin/locations', LocationController::class)
        ->except(['show'])
        ->names('admin.locations');
    Route::resource('admin/sliders', SliderController::class)
        ->except(['show'])
        ->names('admin.sliders');
    Route::resource('admin/promotion-popups', PromotionPopupController::class)
        ->except(['show'])
        ->names('admin.promotion-popups');
    Route::resource('admin/pages', CmsPageController::class)
        ->except(['show'])
        ->names('admin.pages');
});

require __DIR__.'/auth.php';
