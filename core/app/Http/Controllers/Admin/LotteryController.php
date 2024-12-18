<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Lottery;
use App\Models\MultiDrawOption;
use App\Models\Phase;
use App\Models\PhaseCreationSchedule;
use App\Models\WinningSetting;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use Illuminate\Support\Carbon;

class LotteryController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Lotteries';
        $lotteries = Lottery::searchable(['name'])->paginate(getPaginate());
        return view('admin.lottery.index', compact('pageTitle', 'lotteries'));
    }

    public function create($id = 0)
    {
        if ($id) {
            $lottery = Lottery::with('phaseCreationSchedules')->findOrFail($id);
            $pageTitle = 'Update Lottery';
        } else {
            $lottery = null;
            $pageTitle = 'Create Lottery';
        }

        return view('admin.lottery.create', compact('pageTitle', 'lottery'));
    }

    public function store(Request $request, $id = 0)
    {

        $this->lotteryValidation($request, $id);

        $lineNumbers = explode(',', $request->line_variations);
        $numbers = [];
        foreach ($lineNumbers as $number) {
            if (intval($number) <= 0) {
                $notify[] = ['error', 'Invalid line number found'];
                return back()->withNotify($notify);
            } else {
                $numbers[] = intval($number);
            }
        }

        if ($request->auto_creation_phase && hasDuplicateValues($request->days)) {
            $message = 'Draw day should be different';
            if ($request->phase_type == 2) {
                $message = 'Draw date should be different';
            }

            $notify[] = ['error', $message];
            return back()->withNotify($notify);
        }


        if ($id) {
            $lottery      = Lottery::findOrFail($id);
            $notification = 'Lottery updated successfully';
        } else {
            $lottery      = new Lottery();
            $notification = 'Lottery created successfully';
        }

        $lottery->name                     = $request->name;
        $lottery->price                    = $request->price;
        $lottery->line_variations          = $numbers;
        $lottery->no_of_ball               = $request->no_of_ball;
        $lottery->ball_start_from          = $request->ball_start_from;
        $lottery->ball_start_from          = $request->ball_start_from;
        $lottery->total_picking_ball       = $request->total_picking_ball;
        $lottery->has_multi_draw           = $request->has_multi_draw ? Status::YES : Status::NO;
        $lottery->auto_creation_phase      = $request->auto_creation_phase ? Status::YES : Status::NO;
        $lottery->has_power_ball           = $request->has_power_ball ? Status::YES : Status::NO;
        $lottery->no_of_pw_ball            = $request->no_of_pw_ball ?? 0;
        $lottery->pw_ball_start_from       = $request->pw_ball_start_from ?? 0;
        $lottery->total_picking_power_ball = $request->total_picking_power_ball ?? 0;

        if ($request->hasFile('image')) {
            try {
                $lottery->image = fileUploader($request->image, getFilePath('lottery'), getFileSize('lottery'), @$lottery->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the image'];
                return back()->withNotify($notify);
            }
        }

        $lottery->save();

        PhaseCreationSchedule::where('lottery_id', $lottery->id)->delete();
        if ($lottery->auto_creation_phase) {
            foreach ($request->days as $key => $day) {
                $phaseCreationSchedule             = new PhaseCreationSchedule();
                $phaseCreationSchedule->lottery_id = $lottery->id;
                $phaseCreationSchedule->phase_type = $request->phase_type;
                $phaseCreationSchedule->day        = $day;
                $phaseCreationSchedule->time       = @$request->draw_times[$key];
                $phaseCreationSchedule->save();
            }

            if (!$id) {
                $schedule = $lottery->phaseCreationSchedules()->first();
                self::createAutoPhase($schedule);
            }
        }

        $notify[] = ['success', $notification];

        if ($lottery->has_multi_draw && !$id) {
            return to_route('admin.lottery.multi.draw.setting', $lottery->id)->withNotify($notify);
        } else {
            return  back()->withNotify($notify);
        }
    }

    public static function createAutoPhase($schedule)
    {
        $lastPhase         = Phase::where('lottery_id', $schedule->lottery_id)->orderBy('draw_date', 'desc')->first();
        $phase             = new Phase();
        $phase->lottery_id = $schedule->lottery_id;
        $phase->type       = 2;
        $phase->phase_no   = @$lastPhase->id + 1;

        $currentTime = Carbon::now();

        //calculate of the draw date;
        if ($schedule->phase_type == 1) {
            $drawTime = (clone $currentTime)->startOfWeek()->next($schedule->day);
            if ($currentTime > $drawTime) {
                $drawTime = $currentTime->addWeek(1)->startOfWeek()->next($schedule->day);
            }
        } else {
            $drawTime = (clone $currentTime)->startOfMonth()->addDays($schedule->day - 1);
            if ($currentTime > $drawTime) {
                $drawTime = $currentTime->addMonth(1)->startOfMonth($schedule->day - 1);
            }
        }
        // end calculation of the draw date;

        $phase->draw_date = Carbon::parse($drawTime)->setTimeFromTimeString($schedule->time)->format('Y-m-d H:i:s');
        $phase->save();
    }

    protected function lotteryValidation($request, $id)
    {
        $imgValidation = $id ? 'nullable' : 'required';
        $rules = [
            'name'                      => 'required|string|max:40',
            'price'                     => 'required|numeric|min:0',
            'line_variations'           => 'required',
            'no_of_ball'                => 'required|integer|min:1',
            'ball_start_from'           => 'required|integer|in:0,1',
            'total_picking_ball'        => 'required|integer|min:1|lt:no_of_ball',
            'has_multi_draw'            => 'required|integer|in:0,1',
            'image'                     => [$imgValidation, new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'has_power_ball'            => 'nullable|in:1',
            'no_of_pw_ball'             => 'nullable|required_if:has_power_ball,==,1|integer|min:0',
            'pw_ball_start_from'        => 'nullable|required_if:has_power_ball,==,1|integer|in:0,1',
            'total_picking_power_ball'  => 'nullable|required_if:has_power_ball,==,1|integer|min:1|lt:no_of_pw_ball',
            'auto_creation_phase'       => 'nullable|in:1',
            'phase_type'                => 'nullable|required_if:auto_creation_phase,==,1|numeric|in:1,2',
            'days'                      => 'nullable|required_if:auto_creation_phase,==,1|array',
            'draw_times'                => 'nullable|required_if:auto_creation_phase,==,1|array'
        ];


        if ($request->phase_type == 1) {
            $days = days(true);
            $dayValidation = ['days.*' => 'in:' . implode(',', $days)];
        } else {
            $dayValidation = ['days.*' => 'integer|between:1,31'];
        }

        $rules = array_merge($rules, $dayValidation);
        $messages = [
            'days.*.between' => 'Draw dates should be between 1 to 31',
            'days.*.in'     => 'Draw dates should be a day between ' . implode(', ', days())
        ];

        $request->validate($rules, $messages);
    }

    public function updateStatus($id)
    {
        return Lottery::changeStatus($id);
    }

    public function winningSetting($id)
    {
        $lottery   = Lottery::with('winningSettings')->findOrFail($id);
        $pageTitle = "Winning Settings Of $lottery->name";
        return view('admin.lottery.winning_setting', compact('lottery', 'pageTitle'));
    }

    public function winningSettingStore(Request $request, $id)
    {
        $request->validate([
            'winning'               => 'required|array',
            'winning.*.lottery_id'  => 'required|integer',
            'winning.*.power_ball'  => 'required|integer',
            'winning.*.normal_ball' => 'required|integer',
            'winning.*.prize_money' => 'required|numeric|gte:0',
        ], [
            'winning.*.lottery_id.power_ball'  => 'The no of power ball field is required',
            'winning.*.lottery_id.normal_ball' => 'The no of normal ball field is required',
            'winning.*.lottery_id.prize_money' => 'The prize money field is required',
        ]);

        $winnings = array();
        foreach($request->winning as $key => $value)
        {
            if(isset($value["status"]))
            {
                $value["status"] = 1;
            }
            else
            {
                $value["status"] = 0;
                $value["win_times"] = 0;
            }
            $winnings[$key] = $value;
        }

        $lottery = Lottery::findOrFail($id);
        WinningSetting::where('lottery_id', $lottery->id)->delete();
        WinningSetting::insert($winnings);

        $notify[] = ['success', 'Winning setting completed successfully'];
        return back()->withNotify($notify);
    }

    public function phases()
    {
        $pageTitle = 'All Phases';
        $phases    = Phase::searchable(['lottery:name', 'phase_no'])->dateFilter('draw_date')->with('lottery')->orderBy('draw_date', 'desc')->paginate(getPaginate());
        $lotteries = Lottery::manual()->orderBy('name')->get();
        return view('admin.lottery.phases', compact('pageTitle', 'phases', 'lotteries'));
    }

    public function savePhase(Request $request, $id = 0)
    {

        $request->validate([
            'lottery_id' => 'required|exists:lotteries,id',
            'draw_date' => 'required|after_or_equal:today|date_format:Y-m-d h:i A'
        ]);


        if (Carbon::parse($request->draw_date) <= now()) {
            $notify[] = ['error', 'Draw time must be greater than current time'];
            return back()->withNotify($notify);
        }

        $drawDate = Carbon::parse($request->draw_date)->format('Y-m-d H:i:s');
        $isExist  = Phase::where('lottery_id', $request->lottery_id)->where('id', '!=', $id)->whereDate('draw_date', '>=', $drawDate)->exists();

        if ($isExist) {
            $notify[] = ['error', 'One phase already running in this date range'];
            return back()->withNotify($notify);
        }

        if ($id) {
            $phase        = Phase::findOrFail($id);
            $notification = "Phase updated successfully";
        } else {
            $phase           = new Phase();
            $totalPhase      = Phase::where('lottery_id', $request->lottery_id)->count();
            $phase->phase_no = $totalPhase + 1;
            $notification    = "Phase added successfully";
        }

        $phase->lottery_id = $request->lottery_id;
        $phase->draw_date  = $drawDate;
        $phase->save();

        $notify[] = ['success', $notification];
        return  back()->withNotify($notify);
    }

    public function updatePhaseStatus($id)
    {
        return Phase::changeStatus($id);
    }

    public function multiDrawSetting($id)
    {
        $lottery = Lottery::with('multiDrawOptions')->findOrFail($id);
        $pageTitle = 'Multi Draw Options of ' . $lottery->name;
        return view('admin.lottery.multi_draw_options', compact('pageTitle', 'lottery'));
    }

    public function multiDrawSettingStore(Request $request, $id)
    {
        $request->validate([
            'total_draw' => 'required|array',
            'total_draw.*' => 'numeric|gt:0',
            'discount' => 'required|array',
            'discount.*' => 'numeric|gte:0|lt:100'
        ]);

        $lottery = Lottery::findOrFail($id);

        foreach ($request->total_draw as $key => $totalDraw) {
            $option = new MultiDrawOption();
            $option->lottery_id = $lottery->id;
            $option->total_draw = $totalDraw;
            $option->discount = $request->discount[$key] ?? 0;
            $option->save();
        }

        $notify[] = ['success', 'Multi draw option added successfully'];
        return back()->withNotify($notify);
    }

    public function updateMultiDrawOption(Request $request, $id)
    {
        $request->validate([
            'total_draw' => 'required|numeric|gt:0',
            'discount' => 'required|numeric|gte:0|lt:100'
        ]);

        $option = MultiDrawOption::findOrFail($id);
        $option->total_draw = $request->total_draw;
        $option->discount = $request->discount;
        $option->save();

        $notify[] = ['success', 'Multi draw option updated successfully'];
        return back()->withNotify($notify);
    }

    public function updateMultiDrawStatus($id)
    {
        return MultiDrawOption::changeStatus($id);
    }
}
