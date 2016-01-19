<?php

namespace App\Console\Commands;

use App\Models\Floorplan;
use App\Models\Project;
use GrahamCampbell\Flysystem\Facades\Flysystem;
use Illuminate\Console\Command;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use Nathanmac\Utilities\Parser\Facades\Parser;


class HandleFloorplans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logboek:handle-floorplans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Maakt van pdf/png documenten automatisch leaflet maps';

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
        //lijst met te bewerken bestanden ophalen
        $floorplan = Floorplan::where('ready', 0)->first();

        if(!empty($floorplan)){

            $project = Project::findOrFail($floorplan->project_id);
            $year = date("Y", strtotime($project->created_at));

            $adapter = new Local(base_path().'/public/documenten');
            $filesystem = new Filesystem($adapter);

            $file_dir = base_path().'/public/documenten/'.$year.'/'.$floorplan->project_id.'/plattegrond/'.$floorplan->location_id.'/'.$floorplan->floor_id.'/';
            $file_location = $file_dir.$floorplan->filename;

            //eerst opruimen
            if($filesystem->has($year.'/'.$floorplan->project_id.'/plattegrond/'.$floorplan->location_id.'/'.$floorplan->floor_id.'/plattegrond.png')){
                $this->info('Plattegrond.png bestaat al, verwijderen');
                $filesystem->delete($year.'/'.$floorplan->project_id.'/plattegrond/'.$floorplan->location_id.'/'.$floorplan->floor_id.'/plattegrond.png');
            }
            $filetype = $filesystem->getMimetype($year.'/'.$floorplan->project_id.'/plattegrond/'.$floorplan->location_id.'/'.$floorplan->floor_id.'/'.$floorplan->filename);

            //eerst checken of het een pdf is
            if($filetype == 'application/pdf') {
                //pdf omzetten naar png
                $this->info('PDF converteren naar PNG');
                exec('convert -density 150 '.$file_location.' -quality 90 '.$file_dir.'plattegrond.png');
            }

            if($filesystem->has($year.'/'.$floorplan->project_id.'/plattegrond/'.$floorplan->location_id.'/'.$floorplan->floor_id.'/plattegrond.png')){
                $this->info('Plattegrond.png gemaakt');
            }else{
                $this->info('Plattegrond.png niet gevonden.');
                //@Todo: afhandeling
            }

            //eventuele folders met oud kaartmateriaal verwijderen
            for($i = 0; $i < 7; $i++) {
                if($filesystem->has($year.'/'.$floorplan->project_id.'/plattegrond/'.$floorplan->location_id.'/'.$floorplan->floor_id.'/'.$i)){
                    $filesystem->deleteDir($year.'/'.$floorplan->project_id.'/plattegrond/'.$floorplan->location_id.'/'.$floorplan->floor_id.'/'.$i);
                    $this->info('Map '.$i.' verwijderd');
                }
            }


            //leaflet bestanden genereren
            $this->info('Gdal2Tiles uitvoeren');
            //$this->info('Command: gdal2tiles.py -p raster -z 0-6 '.$file_dir.'plattegrond.png '.$file_dir);
            exec(base_path().'/gdal2tiles.py -l -p raster -z 0-6 '.$file_dir.'plattegrond.png '.$file_dir);

            if($filesystem->has($year.'/'.$floorplan->project_id.'/plattegrond/'.$floorplan->location_id.'/'.$floorplan->floor_id.'/0/0/0.png')){
                //als dit bestand bestaat dan is de generatie succesvol geweest
                $xml = $filesystem->read($year.'/'.$floorplan->project_id.'/plattegrond/'.$floorplan->location_id.'/'.$floorplan->floor_id.'/tilemapresource.xml');
                $xml_array = Parser::xml($xml);

                $floorplan->ymax = round($xml_array['BoundingBox']['@attributes']['miny'], 0);
                $floorplan->xmax = round($xml_array['BoundingBox']['@attributes']['maxx'], 0);
                $floorplan->minzoom = 0;
                $floorplan->maxzoom = 6;

                $floorplan->ready = 1;

                $floorplan->save();
                $this->info('Done');
            }



        }

    }
}
