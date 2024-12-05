<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('cron', 'CronController@cron')->name('cron');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});


Route::controller('SiteController')->group(function () {
    Route::post('request/coin', 'requestCoin')->name('request.coin')->middleware('auth');
    Route::get('contact', 'contact')->name('contact');
    Route::post('contact-submit', 'contactSubmit')->name('contact.submit');
    Route::get('change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('cookie/accept', 'cookieAccept')->name('cookie.accept');


    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');
    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode', 'maintenance')->withoutMiddleware('maintenance')->name('maintenance');

    Route::get('lottery-ticket', 'getSingleTicket')->name('lottery.ticket.single');
    Route::get('lottery-tickets', 'lotteryTickets')->name('lottery.tickets');
    Route::get('{slug}/{id}/play', 'playLottery')->name('lottery.play');

    Route::get('results', 'results')->name('results');
    Route::get('blogs', 'blogs')->name('blogs');
    Route::get('faqs', 'faqs')->name('faqs');

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});
