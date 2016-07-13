<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\Project;
use Illuminate\Console\Command;

use Intervention\Image\Facades\Image;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use Mockery\CountValidator\Exception;
use Nathanmac\Utilities\Parser\Facades\Parser;


class GenerateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logboek:generate-thumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pakt de eerste 100 bestanden waar geen thumbnail van gemaakt is, en maakt de thumbnail';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $files = File::where('thumbnail_created', '=', '0000-00-00 00:00:00')->where('type', '=', 'foto')->orderBy('id')->limit(1000)->get();

        foreach($files as $file) {

            $location = $file->location();
            $clean_location = $file->location(true);

            if(file_exists($location)) {
                try {
                    $img = Image::make($location)->resize(350, 260);
                    $img->save($clean_location . $file->id . '_thumb.jpg', 80);
                } catch (\Intervention\Image\Exception\NotReadableException $e) {
                    $this->info($file->id.': Kan niet gegenereerd worden');
                }


            }

            $file->thumbnail_created = date("Y-m-d H:i:s");
            $file->save();
        }

    }
}
