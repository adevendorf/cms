<?php
namespace Cms\Managers;

use Illuminate\Http\Request;
use File;
use Storage;
use Carbon\Carbon;
use Image as ImageTool;

class FileManager
{

    protected $extensions;
    protected $disk;
    protected $today;

    public function __construct() {
        $this->extensions = ['png', 'jpg', 'jpeg', 'gif'];
        $this->disk = Storage::disk('local');
        $this->publicDisk = Storage::disk('public');
        $this->today = Carbon::now();
    }


    public function getExtension($file)
    {
        $filename = explode('.', $file->getClientOriginalName());
        return end($filename);
    }


    public function getFilename($file)
    {
        $filename = str_slug($file->getClientOriginalName());
        return str_replace($this->getExtension($file), '', $filename);
    }

    public function getSiteFolder()
    {
        return '0';
    }

    public function getPath($filename)
    {
        $md5Name = md5($filename);

        $year = $this->today->year;
        $month= $this->today->month;
        $day = $this->today->day;

        $d1 = substr($md5Name, 0, 2);

        return  $this->getSiteFolder().'/'.$year.'/'.$month.'/'.$day.'/'.$d1.'/';
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');

        if (!$file->isValid())  {
            return false;
        }

        $maxDimension = null;
        $extension = $this->getExtension($file);
        $filename = $this->getFilename($file);
        $filenameWithExtension = md5($filename).'-'.time().'.'.$extension;
        $path = $this->getPath($filename);

        $completePath = $path.$filenameWithExtension;


        if (in_array($extension, $this->extensions)) {

            $this->disk->put('asset/'.$completePath, File::get($file));

            $img = ImageTool::make($this->disk->get('asset/'.$completePath));

            $width = $img->width();
            $height = $img->height();

            $maxDimension = $width > $height ? $width : $height;
        }

        return [
            'original_filename' => $filename.'.'.$extension,
            'cms_filename' => $filenameWithExtension,
            'path' => $path,
            'max_dimension' => $maxDimension
        ];
    }

    public function delete($filepath)
    {
        $disk = Storage::disk('local');
        $disk->delete($filepath);
    }

    public function saveFile($path, $file)
    {
        $this->publicDisk->put($path, $file);
    }

}
