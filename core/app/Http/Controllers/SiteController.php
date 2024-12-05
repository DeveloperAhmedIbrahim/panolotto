<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\CoinRequest;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Lottery;
use App\Models\Page;
use App\Models\Phase;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;


class SiteController extends Controller
{
    public function index()
    {
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }

        $pageTitle   = 'Home';
        $sections    = Page::where('tempname', activeTemplate())->where('slug', '/')->first();
        $seoContents = $sections->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::home', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function lotteryTickets()
    {
        $pageTitle = 'Lottery Tickets';

        $lotteries = Lottery::active()->whereHas('winningSettings')->whereHas('phases', function ($query) {
            $query->active()->whereDate('draw_date', '>=', now())->where('is_set_winner', Status::NO);
        })->with('winningSettings', 'activePhase')->paginate(getPaginate());

        $sections  = Page::where('tempname', activeTemplate())->where('slug', '/lottery-tickets')->first();

        return view('Template::lottery.list', compact('pageTitle', 'lotteries', 'sections'));
    }

    public function playLottery($slug, $id)
    {
        $lottery = Lottery::with(['activePhase', 'multiDrawOptions' => function ($options) {
            $options->active();
        }])->active()->whereHas('activePhase')->findOrFail($id);

        $pageTitle = 'Play ' . $lottery->name;
        return view('Template::lottery.play', compact('pageTitle', 'lottery'));
    }

    public function getSingleTicket(Request $request)
    {
        $lottery = Lottery::where('id', $request->lottery_id)->first();
        if (!$lottery) {
            return response()->json([
                'status' => false,
                'message' => 'Lottery not found'
            ]);
        }

        if ($lottery->ball_start_from) {
            $normalBallLimit = $lottery->no_of_ball + 1;
        } else {
            $normalBallLimit = $lottery->no_of_ball;
        }

        if ($lottery->pw_ball_start_from) {
            $pwBallLimit = $lottery->no_of_pw_ball + 1;
        } else {
            $pwBallLimit = $lottery->no_of_pw_ball;
        }

        $html = '';
        for ($i = 1; $i <= $request->difference; $i++) {
            $lotteryNumber = $request->last_ticket + $i;
            $html .= view('Template::lottery.single_ticket', compact('lottery', 'lotteryNumber', 'normalBallLimit', 'pwBallLimit'))->render();
        }

        return response()->json([
            'status' => true,
            'message' => 'success',
            'html' => $html
        ]);
    }

    public function pages($slug)
    {
        $page = Page::where('tempname', activeTemplate())->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        $seoContents = $page->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function faqs()
    {
        $pageTitle = 'FAQ';
        $page = Page::where('tempname', activeTemplate())->where('slug', 'faqs')->first();
        $sections = $page->secs;
        $seoContents = $page->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;

        return view('Template::faqs', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function results()
    {
        $pageTitle = 'Lottery Results';
        $results   = Phase::with('lottery:id,name,image,price', 'lottery.winningSettings')->completed()->orderBy('draw_at', 'desc')->paginate(getPaginate());
        $page  = Page::where('tempname', activeTemplate())->where('slug', 'results')->first();
        $sections = $page->secs;
        $seoContents = $page->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::results', compact('pageTitle', 'results', 'sections', 'seoContents', 'seoImage'));
    }


    public function contact()
    {
        $pageTitle = "Contact Us";
        $user = auth()->user();
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'contact')->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::contact', compact('pageTitle', 'user', 'sections', 'seoContents', 'seoImage'));
    }


    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug)
    {
        $policy = Frontend::activeTemplate()->where('slug', $slug)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        $seoContents = $policy->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::policy', compact('policy', 'pageTitle', 'seoContents', 'seoImage'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blogs()
    {
        $pageTitle   = 'Blogs';
        $page        = Page::where('tempname', activeTemplate())->where('slug', 'blogs')->first();
        $sections    = @$page->secs;
        $seoContents = @$page->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;

        $blogs     = Frontend::activeTemplate()->where('data_keys', 'blog.element')->paginate(getPaginate(15));

        return view('Template::blogs', compact('pageTitle', 'sections', 'seoContents', 'seoImage', 'blogs'));
    }

    public function blogDetails($slug)
    {
        $blog = Frontend::activeTemplate()->where('slug', $slug)->where('data_keys', 'blog.element')->firstOrFail();
        $pageTitle = $blog->data_values->title;
        $seoContents = $blog->seo_content;
        $seoImage = @$seoContents->image ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;

        $latestBlogs = Frontend::activeTemplate()->where('slug', '!=', $slug)->where('data_keys', 'blog.element')->orderBy('id', 'desc')->limit(6)->get();
        return view('Template::blog_details', compact('blog', 'pageTitle', 'seoContents', 'seoImage', 'latestBlogs'));
    }


    public function cookieAccept()
    {
        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
    }

    public function cookiePolicy()
    {
        $cookie = Frontend::activeTemplate()->where('data_keys', 'cookie.data')->first();
        abort_if($cookie->data_values->status != Status::ENABLE, 404);
        $pageTitle = 'Cookie Policy';
        return view('Template::cookie', compact('pageTitle', 'cookie'));
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }



    public function requestCoin(Request $request)
    {
        abort_if(!gs('request_for_coin'), 404);

        $request->validate([
            'amount' => 'required|numeric|gt:0'
        ]);

        $maximumCoin = gs('request_amount');

        if ($maximumCoin < $request->amount) {
            $notify[]       = ['error', "You can request maximum " . showAmount($maximumCoin) . ' ' . gs('cur_text')];
            return back()->withNotify($notify);
        }

        $amount = $request->amount;
        $user   = auth()->user();

        $requestCoin                 = new CoinRequest();
        $requestCoin->user_id        = $user->id;
        $requestCoin->request_number = getTrx();
        $requestCoin->amount         = $amount;
        $requestCoin->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = ' ' . $user->username . ' request for ' . getAmount($amount) . ' ' . gs('cur_text') . '';
        $adminNotification->click_url = route('admin.coin.request.log') . "?search=" . $requestCoin->request_number;
        $adminNotification->save();

        $notify[] = ['success', 'Thank you for your request ' . gs('cur_text') . '. Admin will review your request within a short time'];
        return back()->withNotify($notify);
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        if (gs('maintenance_mode') == Status::DISABLE) {
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view('Template::maintenance', compact('pageTitle', 'maintenance'));
    }
}
