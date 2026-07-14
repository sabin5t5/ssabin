<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Auth;
use Carbon\Carbon;
use Mail;
use App\Helpers\Helper as Helper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController

{

    protected $base_route = 'admin.dashboard';
    protected $view_path = 'admin.dashboard';
    protected $panel = 'Dashboard';

    public function index()
    {
    	$data =[];
    
        $data['activitylogs'] = ActivityLog::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->take(15)->get();
        // Project disk usage (bytes -> human readable)
        try {
            $projectBytes = $this->getDirectorySize(base_path());
            $data['project_size'] = $this->humanFilesize($projectBytes);
        } catch (\Exception $e) {
            $data['project_size'] = 'N/A';
        }

        // Database size
        try {
            $data['database_size'] = $this->getDatabaseSize();
        } catch (\Exception $e) {
            $data['database_size'] = 'N/A';
        }
        return view(parent::loadDefaultDataToView($this->view_path . '.index'), compact('data'));
    }

    public function allActivityLogs()
    {
    	$data = [];

    	$data['activitylogs']= ActivityLog::where('user_id', Auth::user()->id)->where('status', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view(parent::loadDefaultDataToView($this->view_path . '.activity_logs'), compact('data'));

    }

    public function sendDummyMail(Request $request)
    {
        // $data['name']          = 'Mail Checker';
        // $data['email']         = 'vtechnology3@gmail.com';
        // $data['subject']         = 'This is test email';
        // $data['track_number']  = 'TEST_TRACK_NUMBER';
        // $data['lodge_date']    = Carbon::now()->format('M d, Y');

        // Notification::route('mail', $data['email'])
        //     ->notify(new GrievanceLodgeNotificationToPublic($data));

        // ViewHelper::getSessionFlashMsg('Mail Send Successfully', 'success');
        // return redirect('/');
    }

    public function showLog()
    {
        ini_set('memory_limit', '256M');
        $logFile = storage_path('logs/laravel.log');
        $logContent = '';

        if (File::exists($logFile)) {
            $logContent = $this->tailCustom($logFile, 1000);
        } else {
            $logContent = 'Log file not found.';
        }

        // Pass the log content to the view
        return view(parent::loadDefaultDataToView($this->view_path . '.logs'), compact('logContent'));
    }
    public function clearLog(Request $request)
    {
        // Path to the Laravel log file
        $logFile = storage_path('logs/laravel.log');

        // Check if the log file exists and clear it
        if (File::exists($logFile)) {
            File::put($logFile, '');
            $request->session()->flash('success_message', 'Log file cleared successfully.');
        } else {
            $request->session()->flash('success_message', 'Log file not found.');

        }
        // Redirect back with a success message
        return redirect()->route('admin.logs');
    }
    private function tailCustom($file, $lines)
    {
        $buffer = 4096;
        $f = fopen($file, "rb");
        fseek($f, -1, SEEK_END);

        if (fread($f, 1) != "\n") {
            $lines -= 1;
        }

        $output = '';
        $chunk = '';
        while (ftell($f) > 0 && $lines >= 0) {
            $seek = min(ftell($f), $buffer);
            fseek($f, -$seek, SEEK_CUR);
            $chunk = fread($f, $seek);
            $output = $chunk . $output;
            fseek($f, -strlen($chunk), SEEK_CUR);
            $lines -= substr_count($chunk, "\n");
        }

        while ($lines++ < 0) {
            $output = substr($output, strpos($output, "\n") + 1);
        }

        fclose($f);

        return $output;
    }

    public function getVideos(Request $request)
    {
        $data = [
                'title_en'  => config('custom.user_mannual.'.$request->portal_name.'.title_en'),
                'title_np'  => config('custom.user_mannual.'.$request->portal_name.'.title_np'),
                'video_link' => config('custom.user_mannual.'.$request->portal_name.'.video'),
            ];

        return view(parent::loadDefaultDataToView($this->view_path . '.video'), compact('data'));
    }

    public function getVideo($video_link)
    {
        return response()->download(storage_path($video_link), null, [], null);
    }

    public function getManual()
    {
        $data = [
                'link' => 'user_manual.pdf',
            ];
        return view(parent::loadDefaultDataToView($this->view_path . '.manual'), compact('data'));
    }
    public function getManualFile($manual_link)
    {
        return response()->download(storage_path($manual_link), null, [], null);
    }

    private function getDirectorySize($path)
    {
        $size = 0;
        if (!is_dir($path)) {
            return 0;
        }
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS));
        foreach ($it as $file) {
            try {
                $size += $file->getSize();
            } catch (\Exception $e) {
                // skip files we cannot access
            }
        }
        return $size;
    }

    private function humanFilesize($bytes, $decimals = 2)
    {
        if ($bytes <= 0) return '0 B';
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), $decimals) . ' ' . $sizes[$i];
    }

    private function getDatabaseSize()
    {
        $connectionName = config('database.default');
        $driver = config("database.connections.{$connectionName}.driver");
        $database = config("database.connections.{$connectionName}.database");

        if ($driver === 'sqlite') {
            if ($database === ':memory:') return 'In-memory';
            $path = database_path($database);
            if (file_exists($path)) {
                return $this->humanFilesize(filesize($path));
            }
            return 'N/A';
        }

        if ($driver === 'pgsql') {
            $result = DB::select("SELECT pg_database_size(current_database()) AS size");
            $size = isset($result[0]->size) ? (int)$result[0]->size : 0;
            return $this->humanFilesize($size);
        }

        // default: mysql
        if ($driver === 'mysql') {
            $result = DB::select("SELECT SUM(data_length + index_length) AS size FROM information_schema.tables WHERE table_schema = ?", [$database]);
            $size = 0;
            if (isset($result[0]->size)) {
                $size = (int)$result[0]->size;
            }
            return $this->humanFilesize($size);
        }

        return 'N/A';
    }
}
