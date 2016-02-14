<?php

namespace App\Http\Controllers\Logboek;

use Mail;
use App\Models\Log;
use App\Models\Project;
use App\Models\Qrcode;
use App\Models\Report;
use Hashids\Hashids;
use Illuminate\Http\Request;
use League\Flysystem\Filesystem;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrGenerator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function start($code) {

        $log = Log::where('qrcode', $code)->first();

        if($log === NULL) {

            return \View::make('qr.start')
                ->withCode($code)
                ->with('message', 'Deze code bestaat nog niet');

        } else {

            return \View::make('qr.show-log')
                ->withCode($code)
                ->withLog($log);

        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $code)
    {
        //selecteer het project
        $actieve_projecten = Project::where('datum_oplevering', '>', date("Y-m-d"))->lists('naam', 'id');

        $project_id = null;
        if($request->session()->has('project_id')) {
            $project_id = $request->session()->get('project_id');
        }

        return \View::make('qr.select-project')
            ->withCode($code)
            ->withProjectId($project_id)
            ->with('actieve_projecten', $actieve_projecten);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $code)
    {
        $request->session()->put('project_id', \Input::get('project_id'));
        $request->session()->put('qrcode', $code);

        return redirect()->route('log.create', \Input::get('project_id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function report($code)
    {
        $log = Log::where('qrcode', $code)->first();

        return \View::make('qr.report')
            ->withCode($code)
            ->withLog($log);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeReport(Request $request, $code)
    {
        $log = Log::where('qrcode', $code)->first();
        $project = $log->project;

        $report = new Report();
        $report->log_id = $log->id;
        $report->naam = $request->input('naam');
        $report->organisatie = $request->input('organisatie');

        $mailAddresses = preg_split("/(,|;)/", $project->email);

        if($report->save()) {

            foreach($mailAddresses as $to) {
                $to = trim($to);

                Mail::send('emails.report', ['project' => $project, 'log' => $log, 'report' => $report], function ($m) use ($to) {
                    $m->from('info@davelaar.nl', 'Davelaarbouw B.V.');
                    $m->to($to)->subject('Er is een brandscheiding verbroken');
                });
            }

            return \View::make('qr.thanks');
        } else {
            return redirect()->route('qr-code.report', $code)->with('error', 'Niet alle velden zijn juist ingevoerd.');
        }
    }

    public function selectNumberOfCodes()
    {

        $selected = \DB::select('SELECT created_at, count(id) as number FROM qrcodes GROUP BY created_at');
        $col = collect($selected);
        $total = $col->sum('number');
        $total_rows = $col->count();
        if($total_rows > 0) {
            $average = $total / $total_rows;
        } else {
            $average = "onbekend";
        }

        return \View::make('qr.create-form')->withAverage($average);

    }

    public function generateCodes(Request $request)
    {

        set_time_limit(600); //10 minutes!

        $count = $request->input('count');

        $codes = array();

        $zip_file = storage_path('app/temp-qr-codes/qr-codes.zip');

        if(file_exists($zip_file)) {
            unlink($zip_file);
        }

        $zip = new Filesystem(new ZipArchiveAdapter($zip_file));

        for($i = 0; count($codes) < $count; $i++) {
            try {
                $code = $this->generateQR();

                $codes[] = $code;

                $random = rand(0, 1000);

                $file = storage_path('app/temp-qr-codes/'.$code.'-'.$random.'.eps');

                QrGenerator::size(100)
                    ->format('eps')
                    ->errorCorrection('H')
                    ->generate(\URL::route('qr-code.start', $code), $file);

                $content = fopen($file, 'r');
                $zip->putStream($code.'-'.$random.'.eps', $content);
                fclose($content);

                unlink($file);

            } catch(\Exception $e) {

            }


        }

        $zip->getAdapter()->getArchive()->close();

        return response()->download($zip_file);


    }

    private function generateQR() {
        $qrcode = new Qrcode();

        $qrcode->save();

        $hashids = new Hashids('davelaarbouw');

        $code = $hashids->encode($qrcode->id);

        $qrcode->qrcode = $code;

        $qrcode->save();

        return $code;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function restore($code)
    {
        $log = Log::where('qrcode', $code)->first();

        $report = $log->reports->last();

        $report->completed = 1;

        if($report->save()) {
            return redirect()->route('qr-code.start', $code)->with('status', 'Deze brandscheiding is als hersteld gemeld');
        } else {
            return redirect()->route('qr-code.start', $code)
                ->with('error', 'Deze brandscheiding kon niet als hersteld gemeld worden. Als deze
                    foutmelding terug blijft komen, neem dan contact op met kantoor');
        }

    }

    public function generateAdHoc() {

        $string = 'http://www.davelaar.nl/specialisme/brandveiligheid';

        $file = storage_path('app/temp-qr-codes/adhoc.eps');

        QrGenerator::size(100)
            ->format('eps')
            ->errorCorrection('H')
            ->generate($string, $file);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
